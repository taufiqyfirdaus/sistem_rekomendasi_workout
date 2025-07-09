<?php

namespace App\Http\Controllers;

use App\Models\CalendarProgress;
use App\Models\History;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $monthParam = $request->query('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam) : Carbon::now();

        $user = Auth::user();

        $kondisi = $user?->kondisiTubuh;
        $preferensi = $user?->preferensi;

        $progress = [];
        
        if ($user) {
            $lastHistory = History::where('user_id', $user->id)
                ->whereNull('feedback')
                ->latest()
                ->first();

            if ($lastHistory) {
                $rekomendasi = Workout::find($lastHistory->workout_id);

                session([
                    'rekomendasi' => $rekomendasi,
                    'last_history_id' => $lastHistory->id,
                    'last_mood' => $lastHistory->mood,
                ]);
            }
            $progress = CalendarProgress::where('user_id', $user->id)
                ->whereYear('tanggal', $currentMonth->year)
                ->whereMonth('tanggal', $currentMonth->month)
                ->where('is_done', true)
                ->pluck('is_done', 'tanggal')
                ->toArray();
        }
        
        return view('user.index', compact('currentMonth', 'kondisi', 'preferensi', 'progress'));
    }

    public function updateKondisi(Request $request)
    {
        $user = User::find(Auth::id());

        $validated = $request->validate([
            'tanggal_lahir' => 'required|date',
            'berat' => 'required|numeric|min:1',
            'tinggi' => 'required|numeric|min:1',
            'kondisi_kesehatan' => 'required|string',
            'tingkat_kebugaran' => 'required|string',
        ]);

        $validated['user_id'] = $user->id;

        $user->kondisiTubuh()->updateOrCreate(['user_id' => $user->id], $validated);

        return back()->with('success', 'Kondisi tubuh berhasil diperbarui.');
    }

    public function updatePreferensi(Request $request)
    {
        $user = User::find(Auth::id());

        $validated = $request->validate([
            'jenis_olahraga_favorit' => 'required|string',
            'tujuan_workout' => 'required|string',
            'durasi' => 'required|numeric|min:1',
            'alat' => 'required|string',
        ]);

        $validated['user_id'] = $user->id;

        $user->preferensi()->updateOrCreate(['user_id' => $user->id], $validated);

        return back()->with('success', 'Preferensi berhasil diperbarui.');
    }


    public function calendarPartial(Request $request)
    {
        $month = $request->query('month');
        $currentMonth = $month ? Carbon::parse($month) : Carbon::now();

        $user = auth()->user();
        $progress = [];

        if ($user) {
            $progress = CalendarProgress::where('user_id', $user->id)
                ->whereYear('tanggal', $currentMonth->year)
                ->whereMonth('tanggal', $currentMonth->month)
                ->where('is_done', true)
                ->pluck('is_done', 'tanggal')
                ->toArray();
        }

        return view('user.partials.calendar', compact('currentMonth', 'progress'))->render();
    }
    
    public function history()
    {
        $histories = History::with('workout')
            ->where('user_id', auth()->id())
            ->orderByDesc('tanggal')
            ->get();

        return view('user.history', compact('histories'));
    }

    public function workout()
    {
        return view('user.workout');
    }

    public function instruksi()
    {
        return view('user.instruksi');
    }
    
}