# Quick Reference Guide

## 🚀 Quick Commands

### Setup
```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
php artisan serve
php artisan queue:listen
```

### Testing
```bash
php artisan test
php artisan test tests/Feature/AuthenticationTest.php
```

### Debugging
```bash
php artisan tinker
php artisan cache:clear
tail -f storage/logs/laravel.log
```

---

## 📡 API Endpoints (Quick Reference)

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/auth/register` | ❌ | Register user |
| POST | `/api/auth/login` | ❌ | Login user |
| POST | `/api/auth/verify-email` | ❌ | Verify email |
| POST | `/api/auth/resend-verification-email` | ❌ | Resend verification |
| POST | `/api/auth/logout` | ✅ | Logout user |
| GET | `/api/auth/me` | ✅ | Get current user |
| PUT | `/api/auth/profile` | ✅ | Update profile |

---

## 🗂️ File Locations

| Component | File |
|-----------|------|
| Controller | `app/Http/Controllers/AuthController.php` |
| Model | `app/Models/User.php` |
| Middleware | `app/Http/Middleware/` |
| Notification | `app/Notifications/VerifyEmailNotification.php` |
| Routes | `routes/api.php` |
| Migration | `database/migrations/...` |
| Tests | `tests/Feature/AuthenticationTest.php` |
| Seeder | `database/seeders/UserSeeder.php` |

---

## 🔑 Default Test Credentials

After running seeder:

```
Buyer:
- Email: buyer@test.com
- Password: password123

Seller:
- Email: seller@test.com
- Password: password123
```

---

## 📝 Response Format Examples

### Success
```json
{
  "success": true,
  "message": "Login berhasil!",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "buyer"
  }
}
```

### Error
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

## 🔒 Middleware Usage

### In Routes
```php
// Require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});

// Require verified email
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Only verified users
});

// Seller only
Route::middleware(['auth:sanctum', 'verified', 'seller'])->group(function () {
    // Only sellers
});
```

---

## 🌐 Environment Variables (.env)

### Critical
```env
APP_KEY=
DATABASE_URL=
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```

### Optional
```env
QUEUE_CONNECTION=sync
LOG_CHANNEL=single
CACHE_DRIVER=file
SESSION_DRIVER=cookie
```

---

## 💾 Database Fields

```sql
Users Table:
- id: INT, PK
- name: VARCHAR(255)
- email: VARCHAR(255), UNIQUE
- password: VARCHAR(255)
- role: ENUM('buyer', 'seller')
- email_verified_at: TIMESTAMP
- email_verification_token: VARCHAR(255)
- phone: VARCHAR(20)
- store_name: VARCHAR(255)
- description: TEXT
- store_verified_at: TIMESTAMP
- last_login_at: TIMESTAMP
- created_at, updated_at: TIMESTAMP
```

---

## ✅ Pre-deployment Checklist

- [ ] All tests passing
- [ ] `.env` configured
- [ ] Database migrated
- [ ] Email configured
- [ ] CORS configured
- [ ] Logs working
- [ ] Queue working
- [ ] Security headers set
- [ ] HTTPS enabled
- [ ] Backups configured

---

## 🐛 Quick Fixes

**Queue not working:**
```env
QUEUE_CONNECTION=sync
```

**Email not sending:**
```bash
php artisan cache:clear
php artisan queue:listen
```

**Token invalid:**
```bash
php artisan cache:clear
php artisan config:clear
```

**Database error:**
```bash
php artisan migrate:reset
php artisan migrate
```

---

## 📱 cURL Examples

### Register
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"User","email":"user@test.com","password":"Pass123!","password_confirmation":"Pass123!","role":"buyer"}'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"Pass123!"}'
```

### Verify
```bash
curl -X POST http://localhost:8000/api/auth/verify-email \
  -H "Content-Type: application/json" \
  -d '{"token":"TOKEN","email":"user@test.com"}'
```

### Get User (with token)
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer TOKEN"
```

---

## 📚 Documentation Files

- `API_DOCUMENTATION.md` - Full API docs
- `INSTALLATION_GUIDE.md` - Setup instructions
- `CONFIGURATION_GUIDE.md` - Config & troubleshooting
- `IMPLEMENTATION_SUMMARY.md` - Overview
- `IMPLEMENTATION_CHECKLIST.md` - Checklist
- `DEBUGGING_MONITORING.md` - Debug guide

---

## 🎯 Common Tasks

### Create New Test User
```bash
php artisan tinker

$user = App\Models\User::create([
    'name' => 'Test',
    'email' => 'test@example.com',
    'password' => bcrypt('password123'),
    'email_verified_at' => now(),
    'role' => 'buyer',
]);
```

### Send Verification Email
```bash
php artisan tinker

$user = App\Models\User::first();
$user->notify(new \App\Notifications\VerifyEmailNotification($user->email_verification_token, $user));
```

### Generate API Token
```bash
php artisan tinker

$user = App\Models\User::first();
$token = $user->createToken('api-token')->plainTextToken;
echo $token;
```

### Reset User Password
```bash
php artisan tinker

$user = App\Models\User::where('email', 'user@example.com')->first();
$user->update(['password' => bcrypt('newpassword')]);
```

---

## 🔗 Useful Links

- Laravel Docs: https://laravel.com/docs
- Sanctum: https://laravel.com/docs/sanctum
- Pest: https://pestphp.com
- Mailtrap: https://mailtrap.io
- GitHub Repo: [Your Repo URL]

---

## 📞 Support

For detailed information, see:
1. API_DOCUMENTATION.md (API details)
2. CONFIGURATION_GUIDE.md (Setup & troubleshooting)
3. DEBUGGING_MONITORING.md (Debug guide)

---

Last Updated: November 12, 2024
