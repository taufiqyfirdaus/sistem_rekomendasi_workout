<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Workout;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $workouts = [
            ['Lari 2km', 'Pemula', 'Kardio', 15, 'Sepatu lari'],
            ['Lari 4km', 'Menengah', 'Kardio', 30, 'Sepatu lari'],
            ['Lari 8km', 'Ahli', 'Kardio', 60, 'Sepatu lari'],
            ['Jalan santai 30 menit', 'Pemula', 'Kardio', 30, 'Sepatu lari'],
            ['Jalan santai 60 menit', 'Menengah', 'Kardio', 60, 'Sepatu lari'],
            ['Jalan santai 120 menit', 'Ahli', 'Kardio', 120, 'Sepatu lari'],
            ['Sprint Interval 100m x 4', 'Menengah', 'Kardio', 20, 'Sepatu lari'],
            ['Sprint Interval 200m x 4', 'Ahli', 'Kardio', 30, 'Sepatu lari'],
            ['Lompat Tali 5 menit', 'Pemula', 'Kardio', 5, 'Tali lompat'],
            ['Lompat Tali 10 menit', 'Menengah', 'Kardio', 10, 'Tali lompat'],
            ['Lompat Tali 20 menit', 'Ahli', 'Kardio', 20, 'Tali lompat'],
            ['Bersepeda 30 menit', 'Pemula', 'Kardio', 30, 'Sepeda'],
            ['Bersepeda 60 menit', 'Menengah', 'Kardio', 60, 'Sepeda'],
            ['Bersepeda Statis', 'Pemula', 'Kardio', 20, 'Sepeda statis'],
            ['Push-up 3 set', 'Pemula', 'Bodyweight Training', 5, 'Tidak ada'],
            ['Push-up 5 set', 'Menengah', 'Bodyweight Training', 10, 'Tidak ada'],
            ['Sit-up 3 set', 'Pemula', 'Bodyweight Training', 5, 'Tidak ada'],
            ['Sit-up 5 set', 'Menengah', 'Bodyweight Training', 10, 'Tidak ada'],
            ['Squat 3 set', 'Pemula', 'Bodyweight Training', 5, 'Tidak ada'],
            ['Squat 5 set', 'Menengah', 'Bodyweight Training', 10, 'Tidak ada'],
            ['Pull-up 3 set', 'Menengah', 'Bodyweight Training', 10, 'Tidak ada'],
            ['Pull-up 5 set', 'Ahli', 'Bodyweight Training', 20, 'Tidak ada'],
            ['Plank 3 set', 'Pemula', 'Bodyweight Training', 5, 'Tidak ada'],
            ['Plank 5 set', 'Menengah', 'Bodyweight Training', 10, 'Tidak ada'],
            ['Latihan Inti (Core Training)', 'Pemula', 'Bodyweight Training', 20, 'Tidak ada'],
            ['Bodyweight Circuit', 'Ahli', 'Bodyweight Training', 40, 'Tidak ada'],
            ['Stretching', 'Pemula', 'Fleksibilitas', 15, 'Tidak ada'],
            ['Yoga 30 menit', 'Pemula', 'Fleksibilitas', 30, 'Matras'],
            ['Yoga 60 menit', 'Menengah', 'Fleksibilitas', 60, 'Matras'],
            ['Power Yoga 30 menit', 'Menengah', 'Fleksibilitas', 30, 'Matras'],
            ['Power Yoga 60 menit', 'Ahli', 'Fleksibilitas', 60, 'Matras'],
            ['Tai Chi', 'Pemula', 'Fleksibilitas', 30, 'Tidak ada'],
            ['Zumba', 'Menengah', 'Dance Fitness', 45, 'Tidak ada'],
            ['Aerobik 45 menit', 'Menengah', 'Dance Fitness', 45, 'Tidak ada'],
            ['HIIT 20 menit', 'Menengah', 'HIIT', 20, 'Tidak ada'],
            ['Latihan Peningkatan Agility', 'Menengah', 'HIIT', 25, 'Sepatu lari'],
            ['Cardio Boxing', 'Menengah', 'HIIT', 30, 'Tidak ada'],
            ['Deadlift', 'Ahli', 'Kekuatan', 20, 'Barbell'],
            ['Bench Press', 'Ahli', 'Kekuatan', 30, 'Barbell'],
            ['Squat (Powerlifting)', 'Ahli', 'Kekuatan', 30, 'Barbell'],
            ['Latihan Dumbell', 'Menengah', 'Kekuatan', 20, 'Dumbbell'],
            ['Latihan Beban Komplit', 'Ahli', 'Kekuatan', 50, 'Dumbbell, Barbell'],
        ];

        foreach ($workouts as $w) {
            Workout::create([
                'nama_workout' => $w[0],
                'tingkat_kesulitan' => $w[1],
                'kategori' => $w[2],
                'durasi' => $w[3],
                'alat' => $w[4],
                'ilustrasi' => null,
                'deskripsi' => 'Deskripsi untuk ' . $w[0],
                'instruksi' => 'Langkah-langkah untuk melakukan ' . $w[0],
            ]);
        }
    }
}