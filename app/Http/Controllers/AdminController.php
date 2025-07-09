<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $jumlahUser = User::role('user')->count();
        $jumlahAdmin = User::role('admin')->count();
        $jumlahWorkout = Workout::count();
        $jumlahHistory = History::count();
        $recentHistories = History::with(['user', 'workout'])->latest()->take(5)->get();

        return view('admin.index', compact(
            'jumlahUser', 'jumlahAdmin', 'jumlahWorkout','jumlahHistory', 'recentHistories'
        ));

        // return view('admin.index', compact('jumlahWorkout', 'jumlahUser', 'jumlahHistory'));
    }

    public function workout(Request $request)
    {
        $search = $request->input('search');

        $query = Workout::query();

        if ($search) {
            $query->where('nama_workout', 'like', "%$search%")
                ->orWhere('kategori', 'like', "%$search%");
        }

        $workouts = $query->get();

        return view('admin.workout', compact('workouts', 'search'));
    }

    public function storeWorkout(Request $request)
    {
        $request->validate([
            'nama_workout'=>'required|string',
            'tingkat_kesulitan'=>'required|string',
            'kategori'=>'required|string',
            'durasi'=>'required|numeric',
            'alat'=>'nullable|string',
            'ilustrasi'=>'nullable|file|mimes:jpeg,png,gif,mp4,webm,ogg|max:10240', // max 10MB
            'deskripsi'=>'nullable|string',
            'instruksi'=>'nullable|string',
        ]);

        $data = $request->only([
            'nama_workout', 'tingkat_kesulitan', 'kategori', 'durasi',
            'alat', 'deskripsi', 'instruksi'
        ]);

        // Simpan file jika ada
        if ($request->hasFile('ilustrasi')) {
            $file = $request->file('ilustrasi');
            $path = $file->store('ilustrasi', 'public'); // public storage
            $data['ilustrasi'] = 'storage/' . $path; // supaya bisa diakses publik
        }

        Workout::create($data);

        return redirect()->route('adminWorkout')->with('success', 'Workout berhasil ditambahkan.');
    }

    public function updateWorkout(Request $request, $id)
    {
        $request->validate([
            'nama_workout'=>'required|string',
            'tingkat_kesulitan'=>'required|string',
            'kategori'=>'required|string',
            'durasi'=>'required|numeric',
            'alat'=>'nullable|string',
            'ilustrasi'=>'nullable|file|mimes:jpeg,png,gif,mp4,webm,ogg|max:10240',
            'deskripsi'=>'nullable|string',
            'instruksi'=>'nullable|string',
        ]);

        $workout = Workout::findOrFail($id);
        $data = $request->only([
            'nama_workout', 'tingkat_kesulitan', 'kategori', 'durasi',
            'alat', 'deskripsi', 'instruksi'
        ]);

        if ($request->hasFile('ilustrasi')) {
            // Hapus file lama jika ada
            if ($workout->ilustrasi && Storage::exists(str_replace('storage/', 'public/', $workout->ilustrasi))) {
                Storage::delete(str_replace('storage/', 'public/', $workout->ilustrasi));
            }

            // Upload file baru
            $path = $request->file('ilustrasi')->store('ilustrasi', 'public');
            $data['ilustrasi'] = 'storage/' . $path;
        }

        $workout->update($data);

        return redirect()->route('adminWorkout')->with('success', 'Workout berhasil diperbarui.');
    }

    public function deleteWorkout($id)
    {
        $workout = Workout::findOrFail($id);

        // Hapus ilustrasi jika ada
        if ($workout->ilustrasi && Storage::exists(str_replace('storage/', 'public/', $workout->ilustrasi))) {
            Storage::delete(str_replace('storage/', 'public/', $workout->ilustrasi));
        }

        // Hapus gambar dari deskripsi & instruksi di trix
        $this->hapusGambarTrix($workout->deskripsi);
        $this->hapusGambarTrix($workout->instruksi);

        $workout->delete();

        return redirect()->route('adminWorkout')->with('success', 'Workout berhasil dihapus.');
    }

    private function hapusGambarTrix($content)
    {
        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
        foreach ($matches[1] as $src) {
            // Hanya hapus file yang dari /storage/trix-images/
            if (str_contains($src, asset('storage/trix-images/'))) {
                $relativePath = str_replace(asset('storage/'), 'public/', $src);
                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                }
            }
        }
    }

    public function user()
    {
        $users = User::with('roles')->get();
        return view('admin.user', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('admin');

        return redirect()->route('adminUser')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('adminUser')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('adminUser')->with('success', 'User berhasil dihapus.');
    }

    public function history()
    {
        $histories = History::with(['user', 'workout'])->latest()->get();
        return view('admin.history', compact('histories'));
    }
}