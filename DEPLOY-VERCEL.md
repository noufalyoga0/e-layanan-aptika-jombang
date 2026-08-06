# Deploy E-Layanan APTIKA Jombang ke Vercel

Panduan deploy aplikasi Laravel ini ke [Vercel](https://vercel.com) (gratis).

## Prasyarat

1. Akun [Vercel](https://vercel.com/signup) (gratis)
2. Akun [Neon](https://neon.tech) untuk PostgreSQL gratis (Vercel tidak punya database built-in)
3. Repo GitHub sudah ter-push: `https://github.com/noufalyoga0/e-layanan-aptika-jombang`

---

## Langkah 1 — Buat Database PostgreSQL (Neon)

1. Buka [console.neon.tech](https://console.neon.tech) → **New Project**
2. Nama project: `aptika-jombang`
3. Copy **Connection string** (format: `postgresql://user:pass@host/db?sslmode=require`)

---

## Langkah 2 — Deploy via Vercel Dashboard

1. Buka [vercel.com/new](https://vercel.com/new)
2. **Import** repo GitHub `e-layanan-aptika-jombang`
3. Vercel akan otomatis mendeteksi `vercel.json`
4. Di **Environment Variables**, tambahkan:

| Variable | Value |
|----------|-------|
| `APP_KEY` | Generate: `php artisan key:generate --show` |
| `APP_NAME` | `E-Layanan APTIKA Jombang` |
| `APP_URL` | `https://your-project.vercel.app` (ganti setelah deploy) |
| `DB_CONNECTION` | `pgsql` |
| `DATABASE_URL` | Connection string dari Neon |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `FILESYSTEM_DISK` | `public` |

5. Klik **Deploy**

Build otomatis menjalankan:
- `composer install`
- `php artisan migrate --force`
- `php artisan db:seed --force`

---

## Langkah 3 — Setelah Deploy

1. Buka URL Vercel (contoh: `https://e-layanan-aptika-jombang.vercel.app`)
2. Cek health: `https://your-url.vercel.app/up` → harus return `200 OK`
3. Update `APP_URL` di Vercel env vars ke URL production yang sebenarnya
4. Redeploy jika perlu

### Akun Demo (dari seeder)

| Role | Email | Password |
|------|-------|----------|
| Super Admin | `noufalyoga0@student.ub.ac.id` | `password` |
| Verifikator APTIKA | `aptika@jombangkab.go.id` | `password` |
| Admin OPD (Dinkes) | `dinkes@jombangkab.go.id` | `password` |
| Teknisi | `agus.teknisi@jombangkab.go.id` | `password` |

---

## Deploy via CLI (opsional)

```bash
npm i -g vercel
vercel login
vercel link
vercel env add APP_KEY
vercel env add DATABASE_URL
vercel deploy --prod
```

---

## Catatan Penting

### Upload file dokumen
Vercel **tidak punya filesystem persisten**. Upload dokumen tiket (`storage/app/public`) **tidak akan tersimpan permanen** di serverless Vercel.

Untuk production penuh, gunakan:
- **AWS S3** / **Cloudflare R2** / **Vercel Blob** sebagai storage
- Atau deploy via **Render/Railway** (Docker) yang sudah dikonfigurasi di repo

### Route debug
Route `/test-db`, `/reset-db-now`, `/clear-all-cache`, `/add-teknisi` sebaiknya **dinonaktifkan** di production.

### Batasan Vercel Hobby (gratis)
- Max 60 detik per request
- Tidak ada queue worker / cron
- Cold start ~1-3 detik saat pertama kali dibuka

---

## Troubleshooting

| Error | Solusi |
|-------|--------|
| 500 Internal Server Error | Cek `APP_KEY` sudah diset |
| Database connection failed | Pastikan `DATABASE_URL` benar & Neon project aktif |
| 502 / Function timeout | Cold start normal, refresh halaman |
| Migration failed | Cek log build di Vercel Dashboard → Deployments → Build Logs |
