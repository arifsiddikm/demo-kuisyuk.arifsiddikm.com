# CLAUDE PROMPT — KuisYuk! (PRD Lengkap)

Gunakan prompt ini untuk membangun ulang website **KuisYuk!** dari nol. Upload file ini di awal sesi Claude baru, lalu minta Claude untuk mulai build.

---

## 🎯 Konsep Proyek

**Nama Web:** KuisYuk!  
**Tagline:** Buat kuis, share ke siapa saja — semudah itu!  
**Demo Online:** https://demo-kuisyuk.arifsiddikm.com  
**Tipe:** Web aplikasi kuis publik berbasis Laravel, kreator bisa buat kuis dan share via link unik pendek.

---

## 🛠️ Tech Stack

- **Backend:** Laravel (versi terbaru, gunakan MVC biasa — tanpa Filament)
- **Database:** MySQL
- **Frontend:** Tailwind CSS via CDN (`cdn.tailwindcss.com`) — **PENTING: `@apply` tidak bisa digunakan. Semua custom CSS ditulis native di `<style>` tag dalam blade layout.**
- **Rich Text Editor:** CKEditor (untuk textarea konten panjang seperti deskripsi kuis)
- **Alert/Confirm:** SweetAlert2 (untuk logout, hapus, toggle, dan semua konfirmasi)
- **Export:** Excel & PDF (untuk data hasil kuis)
- **Chart:** Chart.js (untuk visualisasi statistik)
- **PHPMailer:** smtp.hostinger.com, SSL, port 465, email: `noreply@arifsiddikm.com`, pass: `SatuDua345!!`, email admin: `arifsiddikmuharam@gmail.com`

---

## 🗄️ Struktur Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | string | |
| email | string unique | |
| email_verified_at | timestamp nullable | |
| password | string | hashed |
| role | enum('creator','admin') | default: creator |
| avatar | string nullable | path file |
| bio | text nullable | |
| is_active | boolean | default: true |
| remember_token | string | |
| created_at / updated_at | timestamps | |

### Tabel `password_reset_tokens`
| Kolom | Tipe |
|---|---|
| email (PK) | string |
| token | string |
| created_at | timestamp nullable |

### Tabel `sessions` (Laravel default)
| Kolom | Tipe |
|---|---|
| id (PK) | string |
| user_id | foreignId nullable |
| ip_address | string(45) nullable |
| user_agent | text nullable |
| payload | longText |
| last_activity | integer |

> **Gabungkan** tabel users, password_reset_tokens, dan sessions dalam **1 file migration** yang sama agar tidak terhapus saat revisi.

### Tabel `quizzes`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| user_id | foreignId | cascade delete |
| title | string | judul kuis |
| description | text nullable | deskripsi kuis |
| cover_image | string nullable | path gambar cover |
| slug | string(10) unique | kode link pendek (< 10 karakter) |
| is_active | boolean | default: true |
| primary_key_label | string | default: 'Nama' (misal: NIS, NISN, KTP) |
| primary_key_enabled | boolean | apakah field primary key diaktifkan |
| primary_key_unique | boolean | apakah hanya boleh submit sekali per primary key |
| time_limit | integer nullable | batas waktu dalam menit, null = tanpa batas |
| created_at / updated_at | timestamps | |

### Tabel `questions`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| quiz_id | foreignId | cascade delete |
| question_text | text | teks soal |
| question_image | string nullable | path gambar soal |
| question_type | enum('multiple_choice','essay') | default: multiple_choice |
| order | integer | urutan soal |
| created_at / updated_at | timestamps | |

### Tabel `options`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| question_id | foreignId | cascade delete |
| option_text | text | teks pilihan jawaban |
| option_image | string nullable | path gambar pilihan |
| is_correct | boolean | default: false |
| order | integer | urutan pilihan |
| created_at / updated_at | timestamps | |

### Tabel `quiz_responses`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| quiz_id | foreignId | cascade delete |
| respondent_name | string | nama pengisi |
| primary_key_value | string nullable | nilai NIS/NISN/KTP |
| ip_address | string(45) nullable | |
| submitted_at | timestamp nullable | |
| created_at / updated_at | timestamps | |

### Tabel `answers`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| quiz_response_id | foreignId | cascade delete |
| question_id | foreignId | cascade delete |
| option_id | foreignId nullable | set null on delete |
| essay_answer | text nullable | jawaban esai |
| created_at / updated_at | timestamps | |

---

## 👥 Role & Akses

| Role | Akses |
|---|---|
| **Creator** | Register/login, buat kuis, kelola soal, lihat hasil, export PDF/Excel, kelola profil |
| **Admin** | Panel `/webmin`, monitoring statistik global, kelola daftar user (creator), CRUD akun admin, export data |
| **Publik** | Buka halaman kuis via slug/link, isi kuis, submit |

---

## 🔐 Autentikasi

- Register & login untuk kreator
- Middleware `role:creator` untuk prefix `/dashboard`
- Middleware `role:admin` untuk prefix `/webmin`
- Logout dengan konfirmasi SweetAlert2
- Halaman login `/webmin/login` untuk admin dengan **tombol autofill** (isi form email+password otomatis, login tetap manual klik tombol Login)

---

## 🌐 Routes

```
GET  /                          → Home/Landing page publik
GET  /kuis                      → Info page (jika akses tanpa slug)
GET  /kuis/{slug}               → Halaman kuis publik
POST /kuis/{slug}/validate-identity → Validasi identitas (nama + primary key)
POST /kuis/{slug}/submit        → Submit jawaban kuis

GET  /login                     → Form login kreator
POST /login
GET  /register                  → Form register kreator
POST /register
POST /logout

GET  /dashboard                 → Dashboard kreator
GET  /dashboard/profile         → Profil kreator
POST /dashboard/profile         → Update profil
GET  /dashboard/kuis            → List kuis kreator
GET  /dashboard/kuis/buat       → Form buat kuis baru
POST /dashboard/kuis            → Simpan kuis baru
GET  /dashboard/kuis/{id}/edit  → Form edit kuis
POST /dashboard/kuis/{id}       → Update kuis
POST /dashboard/kuis/{id}/delete → Hapus kuis
POST /dashboard/kuis/{id}/toggle → Toggle aktif/nonaktif
GET  /dashboard/kuis/{id}/soal  → Kelola soal kuis
POST /dashboard/kuis/{id}/soal  → Tambah soal
POST /dashboard/soal/{id}/delete → Hapus soal
GET  /dashboard/kuis/{id}/hasil → Lihat hasil/respons
GET  /dashboard/kuis/{id}/export → Export Excel hasil

GET  /webmin                    → Dashboard admin
GET  /webmin/pengguna           → List user/kreator
POST /webmin/pengguna/{id}/toggle → Toggle aktif/nonaktif user
POST /webmin/pengguna/{id}/delete → Hapus user
GET  /webmin/pengguna/export    → Export data user
GET  /webmin/admin              → List admin
POST /webmin/admin              → Tambah admin
POST /webmin/admin/{id}/delete  → Hapus admin
```

---

## 📱 Halaman & Fitur Detail

### 1. Landing Page (Publik — `/`)

Desain keren, nuansa merah sebagai warna utama, aksen putih dan warna-warni harmonis. Tampilan "bubble" playful.

Sections:
- **Hero:** Animasi masuk, gradasi warna, background image, judul hook menarik, CTA "Buat Kuis Sekarang"
- **Preview Kuis:** Animasi contoh pertanyaan kuis (timeline scroll ke bawah)
- **Fitur Utama:** Grid card fitur unggulan
- **Testimoni:** Carousel/list testimonial
- **Statistik:** Jumlah user terdaftar, jumlah kuis dibuat, jumlah jawaban masuk
- **CTA Section:** Gradasi keren, ajakan segera buat kuis
- **Footer:** Link navigasi, copyright, sosmed

### 2. Register & Login

- Form register: name, email, password, konfirmasi password
- Form login: email, password
- Validasi client-side dan server-side
- Redirect ke dashboard setelah login

### 3. Halaman Kuis Publik (`/kuis/{slug}`)

Flow:
1. Tombol **"Buka Kuis"** di halaman awal
2. Form identitas: **Nama** (wajib, bebas isi ulang) + **Primary Key** (opsional, tergantung setting kuis — bisa NIS/NISN/KTP, bisa diset unik/berulang)
3. Soal tampil **satu per satu** (slider/next page dalam 1 halaman tanpa reload)
4. Ada tombol **Prev** dan **Next**
5. Soal **pilihan ganda**: klik pilihan → **auto next** ke soal berikutnya
6. Soal **esai**: textarea, lanjut manual klik Next
7. Jawaban tersimpan di **localStorage** (agar tidak hilang jika browser refresh sebelum submit)
8. Progress bar / indikator soal ke-N dari total
9. Timer countdown jika `time_limit` diset
10. Halaman konfirmasi sebelum submit
11. Submit → halaman terima kasih

Kondisi khusus:
- Kuis tidak aktif → halaman `quiz-inactive.blade.php`
- Kuis tidak ditemukan → halaman `quiz-not-found.blade.php`
- Primary key unik: cek duplikat sebelum izinkan isi kuis

### 4. Dashboard Kreator

**Sidebar** dengan desain rapi, logo KuisYuk!, menu navigasi dengan ikon.

Menu:
- Dashboard (statistik: total kuis, total respons, kuis aktif)
- Daftar Kuis
- Profil

**Daftar Kuis:**
- Tabel: judul, slug/link, jumlah soal, jumlah respons, status aktif, tanggal dibuat
- Tombol: Edit, Kelola Soal, Lihat Hasil, Toggle Aktif/Nonaktif, Hapus (SweetAlert confirm)
- Tombol salin link kuis
- Tombol buat kuis baru

**Buat/Edit Kuis:**
- Judul, deskripsi (CKEditor), gambar cover (upload)
- Slug (auto-generate atau manual, maks 10 karakter)
- Toggle aktif/nonaktif
- Primary key: aktifkan, label kustom, unik/berulang
- Batas waktu (menit, opsional)

**Kelola Soal:**
- List soal yang sudah ada
- Form tambah soal: tipe (pilihan ganda / esai), teks soal, gambar soal (upload)
- Untuk pilihan ganda: tambah pilihan jawaban (min 2), tandai jawaban benar
- Hapus soal (SweetAlert confirm)
- Urutan soal (order)

**Lihat Hasil:**
- Tabel respons: nama, primary key value (jika aktif), waktu submit
- Klik respons → detail jawaban per soal
- Tombol export Excel
- Ringkasan statistik per soal (chart pilihan ganda)

**Profil:**
- Update nama, bio, avatar (upload gambar)
- Ganti password

### 5. Panel Admin (`/webmin`)

Tampilan dashboard admin dengan sidebar.

Halaman:
- **Dashboard:** Total kreator terdaftar, total kuis, total respons — hanya angka global, tanpa isi data kuis (data krusial)
- **Pengguna (Kreator):** Tabel list kreator, toggle aktif/nonaktif, hapus, export Excel
- **Admin:** List akun admin, form tambah admin (nama, email, password), hapus admin
- **Login `/webmin/login`:** Tombol autofill email+password testing, login manual

---

## 🎨 Panduan Desain

- Warna utama: **Merah** (primary), putih (background/teks), aksen warna-warni harmonis
- Gaya: **Bubble/playful** — rounded corners besar, shadow lembut, font friendly
- **Tailwind CDN** — tidak ada @apply, semua override CSS native di `<style>`
- Form input, button, checkbox, radio **WAJIB** punya styling yang konsisten dan jelas di semua halaman
- Sidebar dashboard: desain rapi, ada ikon, ada highlight menu aktif
- Setiap button punya styling (jangan ada button tanpa class/desain)
- Responsive: mobile-friendly

**Wajib ada di setiap halaman:**
- Logo & favicon KuisYuk!
- `<title>` yang relevan
- Meta SEO (description, og:title, og:description, og:image)

---

## 🔔 Notifikasi & Konfirmasi

- **Logout:** SweetAlert2 confirm sebelum logout
- **Hapus (kuis, soal, user, admin):** SweetAlert2 confirm
- **Toggle aktif/nonaktif:** SweetAlert2 confirm
- Flash message sukses/error setelah aksi

---

## 📦 Seeder Default

Jalankan `php artisan db:seed` untuk data awal:

**Admin:**
- Email: `admin@kuisyuk.com` / Password: `admin123`

**Creator Demo:**
- Email: `creator@kuisyuk.com` / Password: `creator123`

**Kuis Demo (4 kuis):**
1. Kuis Sejarah Indonesia (slug: `SEJARAH`) — aktif, primary key NIS unik, 5 soal pilihan ganda
2. Matematika Dasar Kelas 7 (slug: `MATDAS`) — aktif, 6 soal pilihan ganda
3. Survei Kepuasan Layanan 2025 (slug: `SURVEY1`) — aktif, 2 soal pilihan ganda + 2 esai
4. Kuis Pengetahuan Umum (slug: `UMUM01`) — **nonaktif**, 3 soal pilihan ganda

Masing-masing kuis sudah ada data respons/jawaban demo.

---

## ⚙️ Konfigurasi `.env` Utama

```env
APP_NAME="KuisYuk!"
APP_URL=http://localhost:8000
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kuisyuk
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=noreply@arifsiddikm.com
MAIL_PASSWORD=SatuDua345!!
MAIL_FROM_ADDRESS=noreply@arifsiddikm.com
MAIL_FROM_NAME="KuisYuk!"
```

---

## 📋 Checklist Fitur

### Auth
- [x] Register kreator
- [x] Login kreator
- [x] Login admin (`/webmin/login`) + autofill button
- [x] Logout dengan SweetAlert confirm
- [x] Middleware role (creator / admin)

### Publik
- [x] Landing page dengan semua sections
- [x] Halaman kuis publik via slug
- [x] Form identitas (nama + primary key opsional)
- [x] Soal satu per satu (slider)
- [x] Auto-next untuk pilihan ganda
- [x] Jawaban tersimpan di localStorage
- [x] Timer countdown (jika diset)
- [x] Submit & halaman terima kasih
- [x] Halaman kuis nonaktif
- [x] Halaman kuis tidak ditemukan

### Creator Dashboard
- [x] Statistik singkat di dashboard
- [x] CRUD kuis (buat, edit, hapus, toggle aktif)
- [x] Salin link kuis
- [x] Kelola soal (tambah, hapus, urutan)
- [x] Upload gambar soal
- [x] Lihat hasil/respons
- [x] Export Excel hasil kuis
- [x] Update profil & avatar
- [x] Ganti password

### Admin Panel
- [x] Dashboard statistik global (tanpa isi kuis)
- [x] List kreator + toggle + hapus + export
- [x] CRUD akun admin
- [x] Autofill login button

### Teknis
- [x] Slug unik < 10 karakter
- [x] Primary key unik (cek duplikat sebelum isi kuis)
- [x] Semua tabel migrations lengkap (users + sessions + password_reset_tokens dalam 1 file)
- [x] DatabaseSeeder terpusat
- [x] SweetAlert2 untuk semua konfirmasi
- [x] CKEditor untuk textarea konten
- [x] Favicon, logo, meta SEO di semua halaman
- [x] Desain form, button, input, radio, checkbox konsisten di semua halaman
- [x] Sidebar dengan desain rapi dan highlight aktif

---

## 📁 Struktur Folder Penting

```
app/
  Http/
    Controllers/
      Auth/AuthController.php
      Creator/DashboardController.php
      Creator/QuizController.php
      Creator/ProfileController.php
      Admin/AdminController.php
      Public/QuizController.php
    Middleware/RoleMiddleware.php
  Models/
    User.php, Quiz.php, Question.php, Option.php, QuizResponse.php, Answer.php
  Policies/QuizPolicy.php

database/
  migrations/
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2024_01_01_000001_create_users_sessions_table.php  ← users + sessions + password_reset_tokens
    2024_01_01_000002_create_quizzes_tables.php        ← semua tabel kuis
  seeders/DatabaseSeeder.php

resources/views/
  layouts/app.blade.php         ← layout publik
  layouts/dashboard.blade.php   ← layout dashboard kreator & admin
  public/home.blade.php         ← landing page
  public/quiz.blade.php         ← halaman kuis
  public/quiz-info.blade.php
  public/quiz-inactive.blade.php
  public/quiz-not-found.blade.php
  auth/login.blade.php
  auth/register.blade.php
  creator/dashboard.blade.php
  creator/profile.blade.php
  creator/quizzes/index.blade.php
  creator/quizzes/create.blade.php
  creator/quizzes/edit.blade.php
  creator/quizzes/questions.blade.php
  creator/quizzes/results.blade.php
  admin/dashboard.blade.php
  admin/users/index.blade.php
  admin/admins/index.blade.php

routes/web.php
```

---

*Generated for KuisYuk! — Last updated: Mei 2026*
