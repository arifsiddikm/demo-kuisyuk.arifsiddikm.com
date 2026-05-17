# KuisYuk! — Platform Kuis Online

Platform web untuk membuat dan berbagi kuis secara online. Kreator bisa membuat kuis sendiri dan membagikannya ke siapa saja melalui link unik pendek.

🌐 **Live Demo:** [demo-kuisyuk.arifsiddikm.com](https://demo-kuisyuk.arifsiddikm.com)

---

## Tech Stack

- **Backend:** PHP 8.3 + Laravel 12
- **Database:** MySQL
- **Frontend:** Tailwind CSS CDN · SweetAlert2
- **Rich Text Editor:** CKEditor
- **Chart:** Chart.js

---

## Fitur

**Publik**
- Isi kuis via link unik pendek (< 10 karakter)
- Soal tampil satu per satu dengan navigasi prev/next
- Auto-next untuk soal pilihan ganda
- Jawaban tersimpan sementara di localStorage
- Timer countdown (jika diaktifkan kreator)
- Dukungan soal pilihan ganda & esai
- Field identitas kustom (NIS / NISN / KTP, dll)

**Dashboard Kreator** (`/dashboard`)
- CRUD kuis lengkap dengan cover image
- Kelola soal: pilihan ganda & esai, upload gambar soal
- Toggle aktif / nonaktif kuis
- Salin link kuis dengan satu klik
- Lihat hasil & statistik per kuis
- Export data hasil ke Excel

**Panel Admin** (`/webmin`)
- Monitoring statistik global (jumlah kreator, kuis, jawaban)
- Kelola daftar pengguna/kreator
- CRUD akun admin
- Export data pengguna

---

## Instalasi

```bash
# 1. Clone repo
git clone https://github.com/arifsiddikm/kuisyuk.git
cd kuisyuk

# 2. Install dependencies
composer install

# 3. Copy dan konfigurasi .env
cp file env to .env and setting your password
php artisan key:generate

# 4. Setup database
php artisan migrate
php artisan db:seed

# 5. Storage link
php artisan storage:link

# 6. Jalankan server
php artisan serve
```

Akses di `http://localhost:8000`

---

## Login

**Kreator Demo**
```
URL   : http://localhost:8000/login
Email : creator@kuisyuk.com
Pass  : creator123
```

**Admin**
```
URL   : http://localhost:8000/webmin/login
Email : admin@kuisyuk.com
Pass  : admin123
```

---

## Konfigurasi MySQL

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kuisyuk
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan ulang:
```bash
php artisan migrate
php artisan db:seed
```

---

## Contoh Link Kuis

Setelah seeder berjalan, coba akses kuis demo langsung:

```
http://localhost:8000/kuis/SEJARAH
http://localhost:8000/kuis/MATDAS
http://localhost:8000/kuis/SURVEY1
```

---

### Support me on

<a href="https://saweria.co/arifsiddikm" target="_blank"><img src="https://user-images.githubusercontent.com/26188697/180601310-e82c63e4-412b-4c36-b7b5-7ba713c80380.png" alt="Sawer me" height="41" width="174"></a>
