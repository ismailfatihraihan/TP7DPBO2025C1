Saya Ismail Fatih Raihan dengan NIM 2307840 mengerjakan Tugas Praktikum 6 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

# Hospital Management System

## Deskripsi Program

Hospital Management System adalah aplikasi berbasis web yang dirancang untuk membantu pengelolaan data pasien, dokter, dan janji temu (appointment) di rumah sakit. Aplikasi ini memudahkan staf rumah sakit untuk mencatat, melacak, dan mengelola informasi penting terkait pasien dan layanan medis.

### Fitur Utama

1. **Manajemen Pasien**
   - Menambah, melihat, mengedit, dan menghapus data pasien
   - Pencarian pasien berdasarkan nama

2. **Manajemen Dokter**
   - Menambah, melihat, mengedit, dan menghapus data dokter
   - Pencarian dokter berdasarkan nama atau spesialisasi

3. **Manajemen Janji Temu (Appointment)**
   - Membuat, melihat, mengedit, dan menghapus janji temu
   - Pencarian janji temu berdasarkan nama pasien, dokter, atau alasan kunjungan

4. **Dashboard**
   - Tampilan ringkasan jumlah pasien, dokter, dan janji temu
   - Daftar janji temu yang akan datang dalam 7 hari ke depan

5. **Keamanan**
   - Implementasi PDO dengan prepared statements untuk mencegah SQL injection
   - Sanitasi input untuk mencegah XSS (Cross-Site Scripting)

## Desain Program

### Struktur Direktori

```
/hospital-management/
├── class/                  # Kelas-kelas entitas
│   ├── Patient.php         # Kelas untuk mengelola data pasien
│   ├── Doctor.php          # Kelas untuk mengelola data dokter
│   └── Appointment.php     # Kelas untuk mengelola data janji temu
├── config/                 # Konfigurasi aplikasi
│   └── config.php          # Konfigurasi database dan fungsi helper
├── database/               # File database
│   └── hospital.sql        # Skema SQL dan data sampel
├── view/                   # Tampilan (UI)
│   ├── patients/           # Tampilan untuk manajemen pasien
│   │   ├── list.php        # Daftar pasien
│   │   ├── add.php         # Form tambah pasien
│   │   ├── edit.php        # Form edit pasien
│   │   └── view.php        # Detail pasien
│   ├── doctors/            # Tampilan untuk manajemen dokter
│   │   ├── list.php        # Daftar dokter
│   │   ├── add.php         # Form tambah dokter
│   │   ├── edit.php        # Form edit dokter
│   │   └── view.php        # Detail dokter
│   └── appointments/       # Tampilan untuk manajemen janji temu
│       ├── list.php        # Daftar janji temu
│       ├── add.php         # Form tambah janji temu
│       ├── edit.php        # Form edit janji temu
│       └── view.php        # Detail janji temu
├── includes/               # File yang disertakan (header, footer, dll.)
│   ├── header.php          # Header halaman
│   ├── footer.php          # Footer halaman
│   └── navigation.php      # Menu navigasi
├── index.php               # Halaman utama (dashboard)
└── style.css               # File CSS untuk styling
```

### Kelas-kelas Entitas

1. **Patient.php**
   - Mengelola operasi CRUD untuk data pasien
   - Metode pencarian pasien berdasarkan nama
   - Validasi data pasien sebelum disimpan ke database

2. **Doctor.php**
   - Mengelola operasi CRUD untuk data dokter
   - Metode pencarian dokter berdasarkan nama atau spesialisasi
   - Validasi data dokter sebelum disimpan ke database

3. **Appointment.php**
   - Mengelola operasi CRUD untuk data janji temu
   - Metode pencarian janji temu berdasarkan nama pasien, dokter, atau alasan
   - Metode untuk mendapatkan janji temu berdasarkan ID pasien atau dokter
   - Validasi data janji temu sebelum disimpan ke database
  
ERD  
![image](https://github.com/user-attachments/assets/b727b68c-66ac-4117-8c75-9645b9ae5784)

RECORD 
https://github.com/user-attachments/assets/45f4f48b-c319-49d3-819b-01a68cfcce92


