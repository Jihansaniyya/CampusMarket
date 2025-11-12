# Debugging & Monitoring Guide

## 🔍 Enable Debug Mode

### Development
```env
APP_ENV=local
APP_DEBUG=true
LOG_CHANNEL=single
```

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

---

## 🐛 Common Debugging Scenarios

### Scenario 1: Email tidak terkirim

**Check Queue Status:**
```bash
# Terminal 1
php artisan queue:listen

# Terminal 2 - Check jika ada job yang failed
php artisan queue:failed
php artisan queue:retry all
```

**Test Email Manually:**
```bash
php artisan tinker

// Test connection
Mail::raw('Test email', function ($message) {
    $message->to('your-email@example.com');
});

// Or with notification
$user = App\Models\User::first();
$user->notify(new \App\Notifications\VerifyEmailNotification('test-token', $user));
```

**Check MAIL Configuration:**
```bash
php artisan config:show mail
```

---

### Scenario 2: Login gagal dengan pesan error

**Debug Login Process:**
```bash
php artisan tinker

// Check if user exists
$user = App\Models\User::where('email', 'test@example.com')->first();
dd($user);

// Check password
Hash::check('password123', $user->password);

// Check email verification
$user->email_verified_at;
$user->isEmailVerified();
```

---

### Scenario 3: Token tidak valid

**Debug Token Issue:**
```bash
php artisan tinker

// Check current user token
$user = Auth::user();
dd($user->tokens);

// Delete old tokens
$user->tokens()->delete();

// Create new token
$token = $user->createToken('auth_token');
dd($token->plainTextToken);
```

---

### Scenario 4: CORS error

**Debug CORS:**
```bash
# Check CORS config
php artisan config:show cors

# Check middleware
grep -r "CorsMiddleware" app/

# Check response headers
curl -i -X OPTIONS http://localhost:8000/api/auth/login
```

---

## 📊 Monitoring Dashboard Commands

### User Statistics
```bash
php artisan tinker

// Total users
App\Models\User::count();

// Verified users
App\Models\User::whereNotNull('email_verified_at')->count();

// Unverified users
App\Models\User::whereNull('email_verified_at')->count();

// Sellers
App\Models\User::where('role', 'seller')->count();

// Buyers
App\Models\User::where('role', 'buyer')->count();

// Last login activity
App\Models\User::orderBy('last_login_at', 'desc')->first();
```

### Database Health Check
```bash
// Check migration status
php artisan migrate:status

// Check database connection
php artisan db:show

// List all tables
php artisan schema:show

// Check table structure
php artisan schema:show --table=users
```

---

## 🔐 Security Audit

### Check Stored Passwords
```bash
php artisan tinker

// Verify no plaintext passwords
$users = App\Models\User::all();
$users->each(function ($user) {
    echo "User: " . $user->email . " - Password Hash: " . substr($user->password, 0, 10) . "...\n";
});
```

### Check Tokens
```bash
// List active tokens
$user = App\Models\User::first();
dd($user->tokens);

// Check token expiry
$tokens = App\Models\User::first()->tokens;
$tokens->each(function ($token) {
    echo "Token: " . $token->name . " - Expires: " . $token->expires_at . "\n";
});
```

### Verify Email Tokens
```bash
// Check unverified users dengan tokens
App\Models\User::whereNull('email_verified_at')
    ->whereNotNull('email_verification_token')
    ->get();
```

---

## 📈 Performance Monitoring

### Database Query Logging
```bash
php artisan tinker

// Enable query logging
DB::enableQueryLog();

// Run some queries
App\Models\User::where('role', 'seller')->get();

// Check queries
$queries = DB::getQueryLog();
foreach ($queries as $query) {
    echo "Query: " . $query['query'] . "\n";
    echo "Time: " . $query['time'] . "ms\n";
}
```

### Cache Status
```bash
php artisan cache:clear
php artisan config:cache

// Check cache status
php artisan cache:show
```

---

## 🧪 Testing & QA

### Run All Tests
```bash
php artisan test

# With coverage
php artisan test --coverage

# Specific test
php artisan test tests/Feature/AuthenticationTest.php::test_user_can_register

# Verbose output
php artisan test -v
```

### Reset Test Database
```bash
php artisan migrate:refresh --seed
```

---

## 📝 Logging & Error Handling

### Configure Logging
Edit `config/logging.php`:

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
    ],
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],
]
```

### Log Messages
```php
// In your code
Log::info('User registered', ['email' => $user->email]);
Log::warning('Email verification failed', ['user_id' => $user->id]);
Log::error('Database error', ['exception' => $exception]);
```

### Monitor Logs
```bash
# Real-time log watching
tail -f storage/logs/laravel.log

# Filter logs
grep "User registered" storage/logs/laravel.log

# Count errors
grep -c "ERROR" storage/logs/laravel.log
```

---

## 🔧 Maintenance Tasks

### Daily
```bash
# Clear old logs (if needed)
php artisan cache:clear
```

### Weekly
```bash
# Optimize
php artisan optimize

# Clean cache
php artisan cache:clear
php artisan config:clear
```

### Monthly
```bash
# Database cleanup
php artisan tinker

// Delete old unverified users (older than 7 days)
App\Models\User::whereNull('email_verified_at')
    ->where('created_at', '<', now()->subDays(7))
    ->delete();
```

---

## 🆘 Emergency Procedures

### Database Backup
```bash
# MySQL
mysqldump -u username -p database_name > backup.sql

# Or using Laravel
php artisan db:backup  // if package installed
```

### Reset Everything
```bash
# WARNING: This deletes all data!
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Emergency Restart
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan serve
```

---

## 📊 Monitoring Checklist

Daily:
- [ ] Check application logs
- [ ] Verify email sending status
- [ ] Monitor login activity
- [ ] Check database size

Weekly:
- [ ] Review user registrations
- [ ] Check failed login attempts
- [ ] Verify email verification rate
- [ ] Monitor API response times

Monthly:
- [ ] Database backup
- [ ] Performance analysis
- [ ] Security audit
- [ ] User activity report

---

## 🎯 KPIs to Monitor

```
1. User Metrics:
   - Total registrations
   - Email verification rate
   - Login success rate
   - Active users

2. System Metrics:
   - API response time
   - Database query time
   - Error rate
   - Queue job status

3. Business Metrics:
   - Buyer vs Seller ratio
   - Last login timeline
   - Churn rate
   - Growth rate
```

---

## 📞 Support Contacts

For issues, check:
1. `API_DOCUMENTATION.md` - API reference
2. `CONFIGURATION_GUIDE.md` - Config & troubleshooting
3. Laravel Docs - https://laravel.com/docs
4. Sanctum Docs - https://laravel.com/docs/sanctum

---

Generated: November 2024
