import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
import joblib
import os

print("Memulai proses training model...")

# --- TAHAP 1: MUAT DATA ---
# Pastikan file dataset_permohonan.csv ada di folder utama proyek
try:
    df = pd.read_csv('dataset_permohonan.csv')
    print("Dataset berhasil dimuat.")
except FileNotFoundError:
    print("Error: File 'dataset_permohonan.csv' tidak ditemukan. Pastikan file ada di folder yang sama dengan skrip ini.")
    exit()

# --- TAHAP 2: REKAYASA FITUR & PEMBUATAN LABEL ---
df['tanggal_lahir'] = pd.to_datetime(df['tanggal_lahir'], errors='coerce')
today = pd.to_datetime('now')
df['umur'] = (today - df['tanggal_lahir']).dt.days / 365.25
df['umur'] = df['umur'].fillna(df['umur'].mean()).astype(int)

conditions = [
    df['nama_surat'].str.contains('Kematian', case=False, na=False),
    df['nama_surat'].str.contains('Usaha', case=False, na=False),
    df['nama_surat'].str.contains('Pindah', case=False, na=False),
    df['nama_surat'].str.contains('Tidak Mampu', case=False, na=False)
]
choices = ['Tinggi', 'Tinggi', 'Sedang', 'Sedang']
df['Prioritas'] = np.select(conditions, choices, default='Rendah')
print("Rekayasa fitur dan pembuatan label selesai.")

# --- TAHAP 3: MENYIAPKAN DATA UNTUK MODEL ---
features = ['nama_surat', 'jenis_kelamin', 'status_perkawinan', 'pekerjaan', 'lingkungan', 'umur']
target = 'Prioritas'
X = df[features]
y = df[target]
X_encoded = pd.get_dummies(X, drop_first=True)
model_columns = X_encoded.columns
print("Data telah di-encode dan siap untuk training.")

# --- TAHAP 4: MEMBANGUN & MELATIH MODEL ---
X_train, X_test, y_train, y_test = train_test_split(X_encoded, y, test_size=0.2, random_state=42, stratify=y)
model = DecisionTreeClassifier(criterion='entropy', max_depth=5, random_state=42)
model.fit(X_train, y_train)
print("Model berhasil dilatih.")

# --- TAHAP 5: MENYIMPAN MODEL & KOLOM ---
# Pastikan folder tujuan sudah ada
output_dir = 'storage/app/ml_model'
os.makedirs(output_dir, exist_ok=True)

# Menyimpan model menggunakan joblib karena lebih efisien untuk model scikit-learn
joblib.dump(model, os.path.join(output_dir, 'model_prioritas.joblib'))
joblib.dump(model_columns, os.path.join(output_dir, 'model_columns.pkl'))

print(f"Model dan kolom berhasil disimpan di folder '{output_dir}'.")
print("\nProses training selesai!")