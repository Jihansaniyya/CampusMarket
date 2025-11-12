## CORS Configuration untuk Frontend

Jika frontend Anda berada di domain/port yang berbeda, perbarui file `config/cors.php` atau buat middleware khusus.

### Option 1: Update config/cors.php (jika ada)

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:5173',  // Vite dev server
        'http://localhost:8000',
        'https://yourdomain.com',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### Option 2: Middleware Custom (jika belum ada CORS)

Buat file: `app/Http/Middleware/CorsMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        return $response;
    }
}
```

Kemudian daftarkan di `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->api(append: [
        \App\Http\Middleware\CorsMiddleware::class,
    ]);
})
```

---

## Testing dengan Postman Collection

### Import Collection

Buat file `postman_collection.json`:

```json
{
  "info": {
    "name": "Campus Market Auth API",
    "version": "1.0.0"
  },
  "item": [
    {
      "name": "Register",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/api/auth/register",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"name\":\"Test User\",\"email\":\"test@example.com\",\"password\":\"Password123!\",\"password_confirmation\":\"Password123!\",\"role\":\"buyer\"}"
        }
      }
    },
    {
      "name": "Login",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/api/auth/login",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"email\":\"test@example.com\",\"password\":\"Password123!\"}"
        }
      }
    },
    {
      "name": "Verify Email",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/api/auth/verify-email",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"token\":\"token_here\",\"email\":\"test@example.com\"}"
        }
      }
    },
    {
      "name": "Get Current User",
      "request": {
        "method": "GET",
        "url": "{{base_url}}/api/auth/me",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}"
          }
        ]
      }
    },
    {
      "name": "Update Profile",
      "request": {
        "method": "PUT",
        "url": "{{base_url}}/api/auth/profile",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}"
          },
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"name\":\"Updated Name\",\"phone\":\"089876543210\"}"
        }
      }
    },
    {
      "name": "Logout",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/api/auth/logout",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}"
          }
        ]
      }
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000"
    },
    {
      "key": "token",
      "value": ""
    }
  ]
}
```

---

## Troubleshooting Common Issues

### Issue 1: "SQLSTATE[42S21]: Column not found"
**Solution:**
```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed --class=UserSeeder
```

### Issue 2: Email tidak terkirim
**Solutions:**
```bash
# Check queue (jika menggunakan queue)
php artisan queue:listen

# Atau switch ke sync driver di .env
QUEUE_CONNECTION=sync

# Test email configuration
php artisan tinker
> Mail::to('test@example.com')->send(new \App\Notifications\VerifyEmailNotification('token', \App\Models\User::first()))
```

### Issue 3: CORS error di browser
**Solution:**
- Configure CORS di `config/cors.php`
- Atau gunakan middleware custom seperti di atas
- Ensure request headers include `Content-Type: application/json`

### Issue 4: Token tidak bekerja
**Solution:**
```bash
# Regenerate Sanctum encryption key
php artisan generate:sanctum-key

# Clear cache
php artisan cache:clear
php artisan config:clear
```

---

## Performance Optimization Tips

### 1. Database Indexing
```php
Schema::table('users', function (Blueprint $table) {
    $table->index('email');
    $table->index('email_verification_token');
    $table->index('role');
});
```

### 2. Query Optimization
```php
// Use select untuk reduce data
User::where('role', 'seller')
    ->select(['id', 'name', 'email', 'store_name'])
    ->get();
```

### 3. Caching
```php
$user = Cache::remember('user.' . $id, 3600, function () use ($id) {
    return User::find($id);
});
```

### 4. Rate Limiting
```php
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
});
```

---

## Production Deployment Checklist

- [ ] Set `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Generate application key: `php artisan key:generate`
- [ ] Configure production database
- [ ] Configure production mail driver
- [ ] Configure production session/cache driver (Redis recommended)
- [ ] Run `php artisan migrate --force`
- [ ] Setup CORS untuk domain production
- [ ] Configure SSL/HTTPS
- [ ] Setup backup database
- [ ] Setup log monitoring
- [ ] Configure rate limiting
- [ ] Setup scheduled jobs jika ada
- [ ] Test dengan production-like environment
- [ ] Setup monitoring dan alerting

---

## Additional Resources

- Laravel Documentation: https://laravel.com/docs
- Laravel Sanctum: https://laravel.com/docs/sanctum
- Laravel Testing: https://laravel.com/docs/testing
- Mailtrap Setup: https://mailtrap.io/
- Gmail App Password: https://support.google.com/accounts/answer/185833

---

Generated: November 2024
