# Campus Market - Authentication API Documentation

## Overview
Sistem autentikasi lengkap untuk aplikasi Campus Market dengan fitur registrasi, login, dan verifikasi email.

## Environment Configuration

Tambahkan konfigurasi berikut ke file `.env`:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@campusmarket.com
MAIL_FROM_NAME="Campus Market"

# API Configuration
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:8000
SESSION_DOMAIN=localhost
```

## API Endpoints

### 1. REGISTRASI (SRS-MartPlace-01)

**Endpoint:** `POST /api/auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "seller",
  "phone": "081234567890",
  "store_name": "Toko John",
  "description": "Toko elektronik terpercaya"
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Registrasi berhasil! Silakan verifikasi email Anda. Cek folder spam jika email tidak terlihat.",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "seller"
  }
}
```

**Response Error (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["Email sudah terdaftar."],
    "store_name": ["Nama toko sudah terdaftar."]
  }
}
```

**Validasi:**
- Jika role adalah `seller`, `store_name` wajib diisi dan harus unik
- Email harus unik
- Password minimal 8 karakter (termasuk huruf besar, angka, simbol)
- Phone adalah opsional

---

### 2. VERIFIKASI EMAIL (SRS-MartPlace-02)

**Endpoint:** `POST /api/auth/verify-email`

**Request Body:**
```json
{
  "token": "verification_token_from_email",
  "email": "john@example.com"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Email berhasil diverifikasi! Anda sekarang dapat login.",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified": true
  }
}
```

**Response Error (400):**
```json
{
  "success": false,
  "message": "Token verifikasi tidak valid atau telah kadaluarsa."
}
```

**Catatan:**
- Token dikirim melalui email saat registrasi
- Token berlaku selama 24 jam (dapat dikustomisasi)
- Jika token expired, user dapat menggunakan endpoint resend verification email

---

### 3. KIRIM ULANG EMAIL VERIFIKASI

**Endpoint:** `POST /api/auth/resend-verification-email`

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Email verifikasi telah dikirim ulang. Silakan cek email Anda."
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Email tidak terdaftar."
}
```

---

### 4. LOGIN

**Endpoint:** `POST /api/auth/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123",
  "remember": true
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Login berhasil!",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "seller",
    "store_name": "Toko John",
    "email_verified": true
  },
  "token": "1|abcdefghijklmnopqrst..."
}
```

**Response Error - Email belum diverifikasi (403):**
```json
{
  "success": false,
  "message": "Email Anda belum diverifikasi. Silakan cek email untuk link verifikasi.",
  "errors": {
    "email": ["Email belum diverifikasi."]
  }
}
```

**Response Error - Kredensial salah (401):**
```json
{
  "success": false,
  "message": "Email atau password salah.",
  "errors": {
    "email": ["Email tidak terdaftar."]
  }
}
```

---

### 5. LOGOUT

**Endpoint:** `POST /api/auth/logout`

**Headers Required:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Logout berhasil!"
}
```

---

### 6. GET CURRENT USER

**Endpoint:** `GET /api/auth/me`

**Headers Required:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "seller",
    "phone": "081234567890",
    "store_name": "Toko John",
    "description": "Toko elektronik terpercaya",
    "email_verified": true,
    "store_verified": true,
    "last_login_at": "2024-01-15T10:30:00Z"
  }
}
```

---

### 7. UPDATE PROFILE

**Endpoint:** `PUT /api/auth/profile`

**Headers Required:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "name": "John Doe Updated",
  "phone": "089876543210",
  "description": "Toko elektronik dan accessories",
  "store_name": "Toko John - Updated"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Profil berhasil diperbarui!",
  "user": {
    "id": 1,
    "name": "John Doe Updated",
    "email": "john@example.com",
    "phone": "089876543210",
    "store_name": "Toko John - Updated",
    "description": "Toko elektronik dan accessories"
  }
}
```

---

## Protected Routes

Beberapa route memerlukan autentikasi dan/atau verifikasi email:

### Route dengan Verifikasi Email
```php
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Routes di sini
});
```

### Route untuk Seller Saja
```php
Route::middleware(['auth:sanctum', 'verified', 'seller'])->group(function () {
    // Routes khusus penjual
});
```

### Route untuk Buyer Saja
```php
Route::middleware(['auth:sanctum', 'verified', 'buyer'])->group(function () {
    // Routes khusus pembeli
});
```

---

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    email_verification_token VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('buyer', 'seller') DEFAULT 'buyer',
    phone VARCHAR(20) NULL,
    store_name VARCHAR(255) NULL UNIQUE,
    description TEXT NULL,
    store_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## Error Codes

| Code | Deskripsi |
|------|-----------|
| 400 | Bad Request - Data tidak valid |
| 401 | Unauthorized - Kredensial salah atau token invalid |
| 403 | Forbidden - Email belum diverifikasi atau akses ditolak |
| 404 | Not Found - Resource tidak ditemukan |
| 422 | Unprocessable Entity - Validasi gagal |
| 500 | Internal Server Error - Kesalahan server |

---

## Testing

### Menggunakan cURL:

**Registrasi:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!",
    "role": "buyer"
  }'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "Password123!"
  }'
```

---

## Migration & Seeding

Jalankan perintah berikut untuk setup database:

```bash
# Run migrations
php artisan migrate

# Seed database dengan test users
php artisan db:seed --class=UserSeeder
```

---

## Important Notes

1. **Email Verification**: Pastikan konfigurasi email sudah benar di `.env`
2. **Token TTL**: Default token/session berlaku sesuai config Laravel
3. **Password Hashing**: Menggunakan bcrypt
4. **API Authentication**: Menggunakan Laravel Sanctum

---

## Troubleshooting

**Email tidak diterima:**
- Cek folder spam/junk
- Verifikasi konfigurasi MAIL di `.env`
- Pastikan service queue berjalan: `php artisan queue:listen`

**Token Invalid:**
- Regenerate token: POST ke `/api/auth/logout` lalu login ulang
- Pastikan Authorization header format: `Bearer {token}`

**Email sudah diverifikasi tapi masih redirect ke verifikasi:**
- Clear cache: `php artisan cache:clear`
- Pastikan `email_verified_at` field sudah ter-update di database
