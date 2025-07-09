import mysql.connector
from dotenv import load_dotenv
import os
import random

load_dotenv()

# Mengembalikan koneksi mysql ke db berdasarkan .env
def get_db_connection():
    return mysql.connector.connect(
        host=os.getenv("DB_HOST", "localhost"),
        user=os.getenv("DB_USER", "root"),
        password=os.getenv("DB_PASSWORD", ""),
        database=os.getenv("DB_NAME", "fitq"),
    )

# Mengambil 5 workout terakhir dari histories agar tdk merekomendasikan wo yg sama
def get_recent_recommended_workouts(user_id: int, limit: int = 5) -> set:
    db = get_db_connection()
    cursor = db.cursor()
    cursor.execute("""
        SELECT workout_id FROM histories
        WHERE user_id = %s
        ORDER BY tanggal DESC, id DESC
        LIMIT %s
    """, (user_id, limit))
    rows = cursor.fetchall()
    cursor.close()
    db.close()
    return set(row[0] for row in rows)

# Filter untuk mood
def filter_by_mood(workouts, mood):
    if mood == "Buruk":
        return [w for w in workouts if w["tingkat_kesulitan"] == "Pemula"]
    return workouts

# Filter untuk kondisi kesehatan
def filter_by_kesehatan(workouts, kondisi_kesehatan):
    allowed_map = {
        "Cedera": ["Jalan santai", "Bersepeda Statis", "Yoga", "Tai Chi", "Stretching"],
        "Hipertensi": ["Jalan santai", "Bersepeda", "Yoga", "Bersepeda Statis", "Tai Chi", "Aerobik"],
        "Hipotensi": ["Jalan santai", "Bersepeda", "Yoga", "Bersepeda Statis", "Tai Chi"],
        "Diabetes": ["Jalan santai", "Bersepeda", "Yoga", "Bersepeda Statis", "Tai Chi", "Push-up", "Sit-up", "Squat"],
        "Obesitas": ["Jalan santai", "Bersepeda", "Yoga", "Bersepeda Statis", "Tai Chi", "Aerobik", "Push-up", "Sit-up", "Squat", "Latihan Dumbell"],
        "Penyakit Jantung": ["Jalan santai", "Bersepeda", "Yoga", "Bersepeda Statis", "Tai Chi", "Aerobik", "Stretching"],
        "Asma": ["Jalan santai", "Bersepeda", "Yoga", "Bersepeda Statis", "Tai Chi"],
        "Normal": None
    }
    allowed = allowed_map.get(kondisi_kesehatan)
    if not allowed:
        return workouts
    return [w for w in workouts if any(nama.lower() in w["nama_workout"].lower() for nama in allowed)]

# Filter untuk kelengkapan alat
def filter_by_alat(workouts, preferensi_alat):
    allowed_map = {
        "Tidak ada": ["Tidak ada"],
        "Dasar": ["Sepatu lari", "Tali lompat", "Matras", "Sepeda"],
        "Lengkap": None
    }
    allowed = allowed_map.get(preferensi_alat)
    if not allowed:
        return workouts
    return [w for w in workouts if any(alat.strip().lower() == w["alat"].strip().lower() for alat in allowed)]

# Mengidentifikasi state yang akan digunakan
def get_state_identifier(state: dict):
    return (
        state["usia"],
        state["jenis_kelamin"],
        state["kategori_bmi"],
        state["kondisi_kesehatan"],
        state["tingkat_kebugaran"],
        state["jenis_olahraga_favorit"],
        state["tujuan_workout"],
        state["durasi_latihan"],
        state["kelengkapan_alat"],
    )

# Jika state belum ada maka akan membuat 42 data Q-Table
def generate_q_table_entries_if_missing(state):
    db = get_db_connection()
    cursor = db.cursor()

    state_values = get_state_identifier(state)
    cursor.execute("""
        SELECT COUNT(*) FROM q_learning_states
        WHERE usia = %s AND jenis_kelamin = %s AND kategori_bmi = %s
        AND kondisi_kesehatan = %s AND tingkat_kebugaran = %s
        AND jenis_olahraga_favorit = %s AND tujuan_workout = %s
        AND durasi_latihan = %s AND kelengkapan_alat = %s
    """, state_values)

    count = cursor.fetchone()[0]

    if count == 0:
        print("State baru terdeteksi, generate 42 entri q_value = 0")
        cursor.execute("SELECT id FROM workouts")
        all_workouts = cursor.fetchall()
        for (workout_id,) in all_workouts:
            cursor.execute("""
                INSERT INTO q_learning_states (
                    usia, jenis_kelamin, kategori_bmi, kondisi_kesehatan, tingkat_kebugaran,
                    jenis_olahraga_favorit, tujuan_workout, durasi_latihan, kelengkapan_alat,
                    workout_id, q_value
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """, state_values + (workout_id, 0.0))

    db.commit()
    cursor.close()
    db.close()

# Proses rekomendasi mulai dari mengambil state hingga mengirimkan rekomendasi ke laravel
def get_rekomendasi(state, user_id):
    print("State diterima dari Laravel:")
    for k, v in state.items():
        print(f"{k}: {v}")

    generate_q_table_entries_if_missing(state)

    db = get_db_connection()
    cursor = db.cursor(dictionary=True)

    query = """
        SELECT q.workout_id, q.q_value, w.nama_workout, w.tingkat_kesulitan, w.kategori, w.durasi, w.alat
        FROM q_learning_states q
        JOIN workouts w ON q.workout_id = w.id
        WHERE
            usia = %s AND jenis_kelamin = %s AND kategori_bmi = %s AND
            kondisi_kesehatan = %s AND tingkat_kebugaran = %s AND
            jenis_olahraga_favorit = %s AND tujuan_workout = %s AND
            durasi_latihan = %s AND kelengkapan_alat = %s
    """

    state_values = get_state_identifier(state)
    cursor.execute(query, state_values)
    hasil = cursor.fetchall()
    cursor.close()
    db.close()

    mood = state.get("mood", "Bagus")
    hasil = filter_by_mood(hasil, mood)
    hasil = filter_by_kesehatan(hasil, state["kondisi_kesehatan"])
    hasil = filter_by_alat(hasil, state["kelengkapan_alat"])

    # Ambil workout terakhir dari history (maks 5)
    recently_used_ids = get_recent_recommended_workouts(user_id)

    # Filter hasil agar tidak menyertakan workout_id yang sama
    hasil = [w for w in hasil if w["workout_id"] not in recently_used_ids]

    if not hasil:
        print("Semua workout pernah direkomendasikan dalam 5 kali terakhir. Gunakan default.")
        return 1

    # EPSILON GREEDY untuk algoritma memilih antara explore atau exploit
    epsilon = 0.2
    if random.random() < epsilon:
        rekomendasi = random.choice(hasil)
        strategi = "explore"
        print(f"Eksplorasi: pilih workout_id = {rekomendasi['workout_id']}")
    else:
        hasil.sort(key=lambda x: x["q_value"], reverse=True)
        rekomendasi = hasil[0]
        strategi = "exploit"
        print(f"Eksploitasi: pilih workout_id = {rekomendasi['workout_id']}")

    return rekomendasi["workout_id"], strategi

# Proses update q-value yang ada di Q-Table
def update_q_value(state: dict, workout_id: int, reward: int):
    db = get_db_connection()
    cursor = db.cursor()

    state_values = get_state_identifier(state)

    select_query = """
        SELECT id, q_value FROM q_learning_states
        WHERE usia = %s AND jenis_kelamin = %s AND kategori_bmi = %s
        AND kondisi_kesehatan = %s AND tingkat_kebugaran = %s
        AND jenis_olahraga_favorit = %s AND tujuan_workout = %s
        AND durasi_latihan = %s AND kelengkapan_alat = %s
        AND workout_id = %s
    """
    cursor.execute(select_query, state_values + (workout_id,))
    result = cursor.fetchone()

    alpha = 0.1  # learning rate

    if result:
        id_, old_q = result
        # Perhitungan Q-Learning
        new_q = old_q + alpha * (reward - old_q)
        cursor.execute("UPDATE q_learning_states SET q_value = %s WHERE id = %s", (new_q, id_))
    else:
        cursor.execute("""
            INSERT INTO q_learning_states (
                usia, jenis_kelamin, kategori_bmi, kondisi_kesehatan, tingkat_kebugaran,
                jenis_olahraga_favorit, tujuan_workout, durasi_latihan, kelengkapan_alat,
                workout_id, q_value
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """, state_values + (workout_id, reward))

    db.commit()
    cursor.close()
    db.close()

    return True
