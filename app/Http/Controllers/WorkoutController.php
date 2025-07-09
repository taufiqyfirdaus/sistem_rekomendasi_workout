<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $kategori = $request->input('kategori');

        $query = Workout::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_workout', 'like', "%$search%")
                ->orWhere('kategori', 'like', "%$search%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $kategoriList = Workout::select('kategori')->distinct()->pluck('kategori');

        $workouts = $query->paginate($limit)->appends([
            'search' => $search,
            'limit' => $limit,
            'kategori' => $kategori
        ]);

        return view('user.workout', compact('workouts', 'search', 'limit', 'kategori', 'kategoriList'));
    }
}