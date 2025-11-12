## Campus Market - Installation Guide

### 1. Setup Environment

Salin file `.env.example` ke `.env` (jika belum ada):
```bash
cp .env.example .env
```

### 2. Generate Application Key

```bash
php artisan key:generate
```

### 3. Configure Email

Edit file `.env` dan sesuaikan konfigurasi email:

**Menggunakan Mailtrap (Recommended untuk Development):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@campusmarket.com
MAIL_FROM_NAME="Campus Market"
```

**Menggunakan Gmail:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Campus Market"
```

> **Note:** Untuk Gmail, gunakan App Password bukan password akun biasa. Aktifkan 2FA terlebih dahulu.

### 4. Install Dependencies

```bash
composer install
npm install
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. (Optional) Seed Database dengan Test Data

```bash
php artisan db:seed --class=UserSeeder
```

Test credentials:
- **Buyer**: buyer@test.com / password123
- **Seller**: seller@test.com / password123

### 7. Start Development Server

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

### 8. (Optional) Start Queue Worker

Untuk mengirim email secara asynchronous:
```bash
php artisan queue:listen
```

---

## Setup Complete! ✅

API siap digunakan. Lihat `API_DOCUMENTATION.md` untuk dokumentasi lengkap.

---

## Quick Test

Test API registrasi menggunakan Postman atau cURL:

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "testuser@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!",
    "role": "buyer"
  }'
```

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── AuthController.php          # Controller untuk auth
│   ├── Middleware/
│   │   ├── EnsureEmailIsVerified.php  # Middleware untuk verifikasi email
│   │   ├── CheckSellerStatus.php      # Middleware untuk seller only
│   │   └── CheckBuyerStatus.php       # Middleware untuk buyer only
│   └── Requests/
│       ├── RegisterRequest.php         # Form request untuk registrasi
│       └── LoginRequest.php            # Form request untuk login
├── Models/
│   └── User.php                        # User model (updated)
└── Notifications/
    └── VerifyEmailNotification.php    # Email notification

database/
├── migrations/
│   └── 2024_01_01_000000_add_email_verification_fields_to_users_table.php
├── factories/
│   └── UserFactory.php                 # Updated dengan role, phone, dll
└── seeders/
    └── UserSeeder.php                  # Seeder untuk test data

routes/
└── api.php                             # API routes (newly created)
```

---

## Troubleshooting

**1. Migration Error: Table already exists**
```bash
# Reset dan re-run migrations
php artisan migrate:reset
php artisan migrate
```

**2. Queue tidak berjalan, email tidak terkirim**
```bash
# Gunakan sync driver untuk development
# Edit .env: QUEUE_CONNECTION=sync
```

**3. Composer autoload error**
```bash
composer dump-autoload
```

**4. Clear cache dan config**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

Jika ada pertanyaan atau masalah, hubungi developer team.
