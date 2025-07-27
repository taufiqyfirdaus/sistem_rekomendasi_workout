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
def get_recent_recommended_workouts(user_id: int, limit: int = 3) -> set:
    db = get_db_connection()
    cursor = db.cursor()
    cursor.execute("""
        SELECT DISTINCT workout_id FROM histories
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
        "Asma": ["Jalan santai", "Bersepeda", "Yoga", "Bersepeda Statis", "Tai Chi", "Aerobik"],
        "Normal": None
    }
    allowed = allowed_map.get(kondisi_kesehatan)
    if not allowed:
        return workouts
    return [w for w in workouts if any(nama.lower() in w["nama_workout"].lower() for nama in allowed)]
# Filter untuk tujuan workout
def filter_by_tujuan(workouts, tujuan):
    if tujuan == "Menurunkan Berat Badan":
        # Fokus pada kardio, HIIT, dan dance fitness
        return [w for w in workouts if w["kategori"] in ["Kardio", "HIIT", "Dance Fitness"]]
    elif tujuan == "Meningkatkan Massa Otot & Kekuatan":
        # Fokus pada kekuatan dan bodyweight training
        return [w for w in workouts if w["kategori"] in ["Kekuatan", "Bodyweight Training"]]
    elif tujuan == "Meningkatkan Kebugaran Kardiovaskular":
        # Fokus hanya pada kardio
        return [w for w in workouts if w["kategori"] == "Kardio"]
    elif tujuan == "Meningkatkan Fleksibilitas":
        # Fokus hanya pada fleksibilitas (yoga, stretching, tai chi)
        return [w for w in workouts if w["kategori"] == "Fleksibilitas"]
    elif tujuan == "Relaksasi":
        # Relaksasi → nama workout yang menenangkan
        return [w for w in workouts if w["nama_workout"] in ["Stretching", "Yoga 30 menit", "Tai Chi"] or "Yoga" in w["nama_workout"]]
    else:
        return workouts
# Filter untuk kelengkapan alat
def filter_by_alat(workouts, preferensi_alat):
    allowed_map = {
        "Tidak ada": ["Tidak ada"],
        "Dasar": ["Sepatu lari", "Tali lompat", "Matras", "Sepeda", "Tidak ada"],
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
# Proses untuk menurunkan epsilon seiring banyaknya rekomendasi berdasarkan banyak user (TIDAK DIGUNAKAN)
def calculate_epsilon_by_user(user_id):
    db = get_db_connection()
    cursor = db.cursor()

    cursor.execute("SELECT COUNT(*) FROM histories WHERE user_id = %s", (user_id,))
    episode = cursor.fetchone()[0]

    cursor.close()
    db.close()

    # Parameter epsilon decay
    epsilon_initial = 1.0
    min_epsilon = 0.3
    decay_rate = 0.97

    epsilon = max(min_epsilon, epsilon_initial * (decay_rate ** episode))
    return epsilon
# Proses untuk menurunkan epsilon seiring banyaknya rekomendasi berdasarkan banyak state
def calculate_epsilon_by_state(state):
    db = get_db_connection()
    cursor = db.cursor()

    state_values = get_state_identifier(state)

    cursor.execute("""
        SELECT COUNT(*) FROM histories
        WHERE JSON_EXTRACT(state_snapshot, '$.usia') = %s
          AND JSON_EXTRACT(state_snapshot, '$.jenis_kelamin') = %s
          AND JSON_EXTRACT(state_snapshot, '$.kategori_bmi') = %s
          AND JSON_EXTRACT(state_snapshot, '$.kondisi_kesehatan') = %s
          AND JSON_EXTRACT(state_snapshot, '$.tingkat_kebugaran') = %s
          AND JSON_EXTRACT(state_snapshot, '$.jenis_olahraga_favorit') = %s
          AND JSON_EXTRACT(state_snapshot, '$.tujuan_workout') = %s
          AND JSON_EXTRACT(state_snapshot, '$.durasi_latihan') = %s
          AND JSON_EXTRACT(state_snapshot, '$.kelengkapan_alat') = %s
    """, state_values)

    state_count = cursor.fetchone()[0]

    cursor.close()
    db.close()

    epsilon_initial = 1.0
    min_epsilon = 0.3
    decay_rate = 0.97

    epsilon = max(min_epsilon, epsilon_initial * (decay_rate ** state_count))

    print(f"Epsilon saat ini (kemunculan {state_count} kali): {epsilon:.4f}")

    return epsilon


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

    # hasil = filter_by_mood(hasil, state["mood"])
    mood = state.get("mood", "Bagus")  
    hasil = filter_by_mood(hasil, mood)
    print(f"Jumlah setelah filter mood: {len(hasil)}")
    hasil = filter_by_kesehatan(hasil, state["kondisi_kesehatan"])
    print(f"Jumlah setelah filter kesehatan: {len(hasil)}")

    # Simpan hasil awal (sebelum filter tujuan)
    hasil_sebelum_tujuan = hasil.copy()

    hasil = filter_by_tujuan(hasil, state["tujuan_workout"])
    print(f"Jumlah setelah filter tujuan workout: {len(hasil)}")

    # Simpan hasil sementara sebelum filter alat
    hasil_sebelum_alat = hasil.copy()

    hasil = filter_by_alat(hasil, state["kelengkapan_alat"])
    print(f"Jumlah setelah filter alat: {len(hasil)}")

    # Fallback jika hasil kosong
    if not hasil:
        print("Hasil kosong setelah filter alat → fallback ke sebelum filter alat.")
        hasil = hasil_sebelum_alat
        print(f"Jumlah workout setelah longgar: {len(hasil)}")

    if not hasil:
        print("Masih kosong → fallback ke hasil awal tanpa filter.")
        hasil = hasil_sebelum_tujuan
        print(f"Jumlah workout setelah longgar: {len(hasil)}")

    if not hasil:
        print("Semua workout terfilter. Gunakan fallback workout_id=1.")
        return 1, "explore"

    # Ambil workout terakhir dari history (maks 5)
    recently_used_ids = get_recent_recommended_workouts(user_id)

    # Filter hasil agar tidak menyertakan workout_id yang sama
    if len(hasil) > 3:
        hasil = [w for w in hasil if w["workout_id"] not in recently_used_ids]
        print(f"Jumlah setelah filter workout terakhir: {len(hasil)}")
    else:
        print("Jumlah hasil terlalu sedikit, skip filter workout terakhir.")

    if not hasil:
        print("Semua workout terfilter. Gunakan fallback workout_id=1.")
        return 1, "explore"

    # EPSILON GREEDY untuk algoritma memilih antara explore atau exploit
    epsilon = calculate_epsilon_by_state(state)

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
def update_q_value(state: dict, workout_id: int, reward: int, next_state: dict):
    alpha = 0.3
    gamma = 0.8

    db = get_db_connection()
    cursor = db.cursor()

    current_state_values = get_state_identifier(state)
    next_state_values = get_state_identifier(next_state)

    # Q(s, a)
    cursor.execute("""
        SELECT id, q_value FROM q_learning_states
        WHERE usia = %s AND jenis_kelamin = %s AND kategori_bmi = %s
        AND kondisi_kesehatan = %s AND tingkat_kebugaran = %s
        AND jenis_olahraga_favorit = %s AND tujuan_workout = %s
        AND durasi_latihan = %s AND kelengkapan_alat = %s
        AND workout_id = %s
    """, current_state_values + (workout_id,))
    result = cursor.fetchone()

    # max Q(s’, a’)
    cursor.execute("""
        SELECT MAX(q_value) FROM q_learning_states
        WHERE usia = %s AND jenis_kelamin = %s AND kategori_bmi = %s
        AND kondisi_kesehatan = %s AND tingkat_kebugaran = %s
        AND jenis_olahraga_favorit = %s AND tujuan_workout = %s
        AND durasi_latihan = %s AND kelengkapan_alat = %s
    """, next_state_values)
    max_q_next = cursor.fetchone()[0] or 0.0

    if result:
        id_, old_q = result
        new_q = old_q + alpha * (reward + gamma * max_q_next - old_q)
        cursor.execute("UPDATE q_learning_states SET q_value = %s WHERE id = %s", (new_q, id_))
        print(f"Q(s,a) = {old_q} + {alpha} * ({reward} + {gamma} * {max_q_next} - {old_q}) = {new_q}")
    else:
        cursor.execute("""
            INSERT INTO q_learning_states (
                usia, jenis_kelamin, kategori_bmi, kondisi_kesehatan, tingkat_kebugaran,
                jenis_olahraga_favorit, tujuan_workout, durasi_latihan, kelengkapan_alat,
                workout_id, q_value
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """, current_state_values + (workout_id, reward))

    db.commit()
    cursor.close()
    db.close()
    return True