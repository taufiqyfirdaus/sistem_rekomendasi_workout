from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Optional
from qlearning_logic import get_rekomendasi, update_q_value
# Inisialisasi FastAPI
app = FastAPI()
# Mendefinisikan struktur data state
class UserState(BaseModel):
    usia: str
    jenis_kelamin: str
    kategori_bmi: str
    kondisi_kesehatan: str
    tingkat_kebugaran: str
    jenis_olahraga_favorit: str
    tujuan_workout: str
    durasi_latihan: int
    kelengkapan_alat: str
    mood: str
    user_id: int
# Menerima data saat diberikan feedback
class FeedbackInput(BaseModel):
    state: UserState
    workout_id: int
    feedback: int
    next_state: Optional[dict] = None
# Proses Rekomendasi
@app.post("/rekomendasi")
def rekomendasi_workout(state: UserState):
    try:
        state_dict = state.dict()
        user_id = state_dict.pop("user_id")  # keluarkan user_id
        workout_id, strategi = get_rekomendasi(state_dict, user_id)
        return {
            "rekomendasi_workout_id": workout_id,
            "strategi": strategi
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
# Proses Feedback
@app.post("/feedback")
def kirim_feedback(data: FeedbackInput):
    print("Feedback diterima:")
    print(data.dict())
    try:
        next_state = data.next_state or data.state.dict()
        print("Feedback diterima:", data.dict()) 
        result = update_q_value(data.state.dict(), data.workout_id, data.feedback, next_state)
        print("Q-value berhasil diupdate.") 
        return {"success": result}
    except Exception as e:
        print("Error update Q-value:", e) 
        raise HTTPException(status_code=500, detail=str(e))
