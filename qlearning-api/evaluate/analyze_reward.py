import os
import pandas as pd
import matplotlib.pyplot as plt
import mysql.connector
from dotenv import load_dotenv

# Load .env
load_dotenv()

# Fungsi koneksi database
def get_db_connection():
    return mysql.connector.connect(
        host=os.getenv("DB_HOST", "localhost"),
        user=os.getenv("DB_USER", "root"),
        password=os.getenv("DB_PASSWORD", ""),
        database=os.getenv("DB_NAME", "fitq"),
    )

# Koneksi database
db = get_db_connection()

# Ambil data dari tabel histories
query = """
SELECT user_id, tanggal, feedback, strategi
FROM histories
WHERE feedback IS NOT NULL
"""
df = pd.read_sql(query, db)

# Tutup koneksi
db.close()

# Pastikan format tanggal benar
df['tanggal'] = pd.to_datetime(df['tanggal'])

# Urutkan berdasarkan user & tanggal
df = df.sort_values(by=['user_id', 'tanggal'])

# Hitung cumulative reward per user
df['cumulative_reward'] = df.groupby('user_id')['feedback'].cumsum()

# Grafik cumulative reward per user
plt.figure(figsize=(10,6))
for user_id, group in df.groupby('user_id'):
    plt.plot(group['tanggal'], group['cumulative_reward'], marker='o', label=f'User {user_id}')

plt.title('Cumulative Reward per User')
plt.xlabel('Tanggal')
plt.ylabel('Cumulative Reward (dari feedback)')
plt.legend()
plt.grid(True)
plt.tight_layout()
plt.savefig("evaluate/cumulative_reward_per_user.png")
plt.show()

# Ekspor cumulative reward ke Excel
df.to_excel("evaluate/cumulative_reward_data.xlsx", index=False)
print("Data cumulative reward berhasil diekspor ke 'evaluate/cumulative_reward_data.xlsx'")

# Tambahkan nomor episode per user berdasarkan urutan waktu
df['episode'] = df.groupby('user_id').cumcount() + 1  # mulai dari 1

# Grafik cumulative reward per user berdasarkan episode
plt.figure(figsize=(10,6))
for user_id, group in df.groupby('user_id'):
    plt.plot(group['episode'], group['cumulative_reward'], marker='o', label=f'User {user_id}')

plt.title('Cumulative Reward per Episode (per User)')
plt.xlabel('Episode / Iterasi')
plt.ylabel('Cumulative Reward (dari feedback)')
plt.legend()
plt.grid(True)
plt.tight_layout()
plt.savefig("evaluate/cumulative_reward_per_episode.png")
plt.show()

# Ekspor cumulative reward ke Excel
df.to_excel("evaluate/cumulative_reward_per_episode.xlsx", index=False)
print("Data cumulative reward per episode berhasil diekspor.")

# Rata-rata feedback berdasarkan strategi
avg_reward_by_strategy = df.groupby('strategi')['feedback'].mean().reset_index()
print("\n Rata-rata Feedback per Strategi:")
print(avg_reward_by_strategy)

# Visualisasi strategi
plt.figure(figsize=(6,4))
plt.bar(avg_reward_by_strategy['strategi'], avg_reward_by_strategy['feedback'], color=['skyblue', 'salmon'])
plt.title("Rata-rata Feedback Berdasarkan Strategi")
plt.ylabel("Rata-rata Feedback")
plt.xlabel("Strategi")
plt.tight_layout()
plt.savefig("evaluate/avg_feedback_by_strategy.png")
plt.show()

# Ringkasan evaluasi per user
summary = df.groupby('user_id').agg({
    'feedback': ['count', 'mean', 'sum'],
    'cumulative_reward': 'max'
}).reset_index()
summary.columns = ['user_id', 'jumlah_rekomendasi', 'rata2_feedback', 'total_feedback', 'cumulative_reward']
print("\n Ringkasan Evaluasi per User:")
print(summary)

summary.to_excel("evaluate/ringkasan_evaluasi_per_user.xlsx", index=False)
print("Ringkasan evaluasi disimpan ke 'evaluate/ringkasan_evaluasi_per_user.xlsx'")
