<?php

namespace App\Http\Controllers;

use App\Models\CalendarProgress;
use App\Models\Workout;
use App\Models\History;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class QLearningController extends Controller
{
    public function proses(Request $request)
    {
        $request->validate([
            'mood' => 'required|in:bagus,jelek',
        ]);

        $user = auth()->user();
        $kondisi = $user->kondisiTubuh;
        $preferensi = $user->preferensi;

        if (!$kondisi || !$preferensi) {
            return back()->with('error', 'Mohon lengkapi data kondisi tubuh dan preferensi terlebih dahulu.');
        }

        // Susun state
        $usia = Carbon::parse($kondisi->tanggal_lahir)->age;
        $kategoriUsia = $this->kategoriUsia($usia);
        $bmi = $this->hitungBMI($kondisi->berat, $kondisi->tinggi);
        $kategoriBmi = $this->kategoriBMI($bmi);

        $state = [
            'usia' => $kategoriUsia,
            'jenis_kelamin' => $user->jenis_kelamin === 'Laki-laki' ? 'Pria' : 'Wanita',
            'kategori_bmi' => $kategoriBmi,
            'kondisi_kesehatan' => $kondisi->kondisi_kesehatan,
            'tingkat_kebugaran' => $kondisi->tingkat_kebugaran,
            'jenis_olahraga_favorit' => $preferensi->jenis_olahraga_favorit,
            'tujuan_workout' => $preferensi->tujuan_workout,
            'durasi_latihan' => $preferensi->durasi,
            'kelengkapan_alat' => $preferensi->alat,
            'mood' => $request->mood === 'bagus' ? 'Bagus' : 'Buruk',
            'user_id' => $user->id,
        ];

        // Panggil API
        $response = $this->panggilAPIRekomendasi($state);

        if (isset($response['error'])) {
            return back()->with('error', $response['error']);
        }

        $workoutId = $response['rekomendasi_workout_id'];
        $strategi = $response['strategi'] ?? null;

        $workout = Workout::find($workoutId);
        if (!$workout) {
            return back()->with('error', 'Workout tidak ditemukan.');
        }

        // Simpan history ke database
        $history = History::create([
            'user_id' => $user->id,
            'workout_id' => $workoutId,
            'tanggal' => now()->toDateString(),
            'state_snapshot' => json_encode($state, JSON_PRETTY_PRINT),
            'mood' => $state['mood'],
            'strategi' => $strategi,
        ]);

        // Simpan hasil rekomendasi ke session
        session([
            'last_mood' => $state['mood'],
            'last_history_id' => $history->id,
        ]);

        return redirect()->route('homeUser')->with('rekomendasi', $workout);
    }

    private function hitungBMI($berat, $tinggi)
    {
        if ($tinggi == 0) return 0;
        $tinggiMeter = $tinggi / 100;
        return round($berat / ($tinggiMeter * $tinggiMeter), 1);
    }

    private function kategoriBMI($bmi)
    {
        if ($bmi < 18.5) return 'Berat badan kurang';
        elseif ($bmi < 25.0) return 'Berat badan normal';
        elseif ($bmi < 30.0) return 'Berat badan berlebih';
        else return 'Obesitas';
    }
    
    private function kategoriUsia($usia)
    {
        if ($usia < 20) {
            return 'Muda';
        } elseif ($usia <= 59) {
            return 'Dewasa';
        } else {
            return 'Lansia';
        }
    }

    private function panggilAPIRekomendasi(array $state)
    {
        try {
            $response = Http::timeout(10)->post('http://127.0.0.1:8001/rekomendasi', $state);

            if ($response->successful()) {
                return $response->json();
            }

            dd($response->body());

            return ['error' => 'Gagal mendapatkan rekomendasi dari server Python.'];
        } catch (\Exception $e) {
            return ['error' => 'Koneksi ke server Python gagal: ' . $e->getMessage()];
        }
    }

    public function feedback(Request $request)
    {
        $request->validate([
            'feedback' => 'required|integer',
            'workout_id' => 'required|exists:workouts,id'
        ]);

        $user = auth()->user();
        $kondisi = $user->kondisiTubuh;
        $preferensi = $user->preferensi;

        if (!$kondisi || !$preferensi) {
            return back()->with('error', 'Data tidak lengkap.');
        }

        $usia = Carbon::parse($kondisi->tanggal_lahir)->age;
        $kategoriUsia = $this->kategoriUsia($usia);
        $bmi = $this->hitungBMI($kondisi->berat, $kondisi->tinggi);
        $kategoriBmi = $this->kategoriBMI($bmi);

        $state = [
            'usia' => $kategoriUsia,
            'jenis_kelamin' => $user->jenis_kelamin === 'Laki-laki' ? 'Pria' : 'Wanita',
            'kategori_bmi' => $kategoriBmi,
            'kondisi_kesehatan' => $kondisi->kondisi_kesehatan,
            'tingkat_kebugaran' => $kondisi->tingkat_kebugaran,
            'jenis_olahraga_favorit' => $preferensi->jenis_olahraga_favorit,
            'tujuan_workout' => $preferensi->tujuan_workout,
            'durasi_latihan' => $preferensi->durasi,
            'kelengkapan_alat' => $preferensi->alat,
            'user_id' => $user->id,
            'mood' => session('last_mood') ?? 'Bagus',
        ];

        $data = [
            'state' => $state,
            'workout_id' => (int) $request->workout_id,
            'feedback' => (int) $request->feedback
        ];

        Log::debug('Payload feedback ke Python:', $data);

        try {
            $response = Http::timeout(10)->post('http://127.0.0.1:8001/feedback', $data);

            if ($response->successful()) {
                $berhasil = $this->simpanFeedbackKeHistory($user->id, $request->workout_id, $request->feedback);
                if (!$berhasil) {
                    Log::warning("Feedback berhasil dikirim ke Python, tapi gagal disimpan di tabel histories.");
                }
                $tanggalLokal = $request->input('local_date', now()->toDateString());

                CalendarProgress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'tanggal' => $tanggalLokal
                    ],
                    [
                        'is_done' => true
                    ]
                );
                session()->forget(['rekomendasi', 'last_history_id', 'last_mood']);
                return redirect()->route('homeUser')->with('success', 'Feedback berhasil dikirim.');
            } else {
                Log::error("Feedback gagal. Response:", $response->json());
                return back()->with('error', 'Gagal mengirim feedback ke server.');
            }
        } catch (\Exception $e) {
            Log::error('Koneksi ke server Python gagal: ' . $e->getMessage());
            return back()->with('error', 'Koneksi ke server feedback gagal: ' . $e->getMessage());
        }
    }

    
    private function simpanFeedbackKeHistory($userId, $workoutId, $feedback)
    {
        $historyId = session('last_history_id');

        if ($historyId) {
            $history = History::find($historyId);
            if ($history) {
                $history->update(['feedback' => $feedback]);
                return true;
            } else {
                Log::warning("History dengan ID $historyId tidak ditemukan saat update feedback.");
            }
        } else {
            Log::info("Session 'last_history_id' tidak tersedia. Menggunakan fallback by user/workout/tanggal.");
        }

        // Fallback: cari berdasarkan user, workout, dan tanggal
        return History::where([
            'user_id' => $userId,
            'workout_id' => $workoutId,
            'tanggal' => now()->toDateString(),
        ])->latest()->first()?->update([
            'feedback' => $feedback
        ]);
    }

}