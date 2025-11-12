# Blade Component Usage Guide - CampusMarket

## 📚 Struktur Blade yang Sudah Dibuat

### 1. **Layouts**

#### `layouts/auth.blade.php` - Master Layout untuk Authentication

Digunakan untuk halaman Login dan Register.

**Cara Pakai:**

```blade
@extends('layouts.auth')

@section('title', 'Login')
@section('body-class', 'min-h-screen bg-gray-50') {{-- Optional --}}

@section('content')
    <!-- Your page content here -->
@endsection

@push('scripts')
    <script>
        // Your custom JavaScript
    </script>
@endpush

@push('styles')
    <style>
        /* Your custom CSS */
    </style>
@endpush
```

#### `admin/layout.blade.php` - Master Layout untuk Admin Dashboard

Sudah include sidebar dan navbar dengan Alpine.js.

**Cara Pakai:**

```blade
@extends('admin.layout')

@section('title', 'Dashboard')
@section('breadcrumb', 'Home > Dashboard')

@section('content')
    <!-- Your admin page content -->
@endsection
```

---

### 2. **Blade Components yang Tersedia**

#### **Alert Component** - `<x-alert>`

Untuk menampilkan notifikasi/pesan.

**Props:**

-   `type` (string): 'success', 'error', 'warning', 'info' (default: 'info')
-   `message` (string): Pesan yang akan ditampilkan

**Cara Pakai:**

1. **Dengan message prop:**

```blade
<x-alert type="success" message="Data berhasil disimpan!" />
<x-alert type="error" message="Terjadi kesalahan!" />
<x-alert type="warning" message="Perhatian!" />
```

2. **Dengan slot (untuk HTML kompleks):**

```blade
<x-alert type="error">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</x-alert>
```

3. **Dengan session flash:**

```blade
@if(session('success'))
    <x-alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-alert type="error" :message="session('error')" />
@endif
```

**Icon dan Warna Otomatis:**

-   `success`: ✓ hijau
-   `error`: ✗ merah
-   `warning`: ⚠ kuning
-   `info`: ℹ biru

---

#### **Input Component** - `<x-forms.input>`

Form input field dengan label, icon, dan error message otomatis.

**Props:**

-   `name` (string, required): Nama input field
-   `label` (string, required): Label field
-   `type` (string): Type input (default: 'text')
-   `icon` (string): Font Awesome icon name (tanpa 'fa-')
-   `placeholder` (string): Placeholder text
-   `value` (mixed): Default value

**Cara Pakai:**

1. **Input biasa:**

```blade
<x-forms.input
    name="name"
    label="Full Name"
    placeholder="Enter your name"
    required
/>
```

2. **Input dengan icon:**

```blade
<x-forms.input
    name="email"
    label="Email Address"
    type="email"
    icon="envelope"
    placeholder="your@email.com"
    required
/>

<x-forms.input
    name="password"
    label="Password"
    type="password"
    icon="lock"
    required
/>
```

3. **Input dengan default value (untuk edit form):**

```blade
<x-forms.input
    name="name"
    label="Full Name"
    :value="$user->name"
/>
```

4. **Attributes tambahan:**

```blade
<x-forms.input
    name="phone"
    label="Phone Number"
    icon="phone"
    pattern="^(\+62|62|0)[0-9]{9,12}$"
    minlength="10"
    required
/>
```

**Fitur Otomatis:**

-   ✅ Old input (jika validation gagal)
-   ✅ Error message dari validation
-   ✅ Focus state dengan ring blue
-   ✅ Icon support

---

### 3. **Contoh Implementasi**

#### **Login Page (Blade Components)**

```blade
@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="max-w-md w-full mx-auto bg-white p-8 shadow-xl rounded-xl">
    <div class="text-center mb-6">
        <h1 class="text-4xl font-bold text-blue-600">🛒 CampusMarket</h1>
        <p class="text-gray-600 mt-2">Welcome back!</p>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
        <x-alert type="error">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    {{-- Login Form --}}
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <x-forms.input
            name="email"
            label="Email Address"
            type="email"
            icon="envelope"
            placeholder="Enter your email"
            required
        />

        <x-forms.input
            name="password"
            label="Password"
            type="password"
            icon="lock"
            placeholder="Enter your password"
            required
        />

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded">
                <span class="ml-2 text-sm">Remember me</span>
            </label>
            <a href="#" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg">
            Login
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">Register here</a>
        </p>
    </div>
</div>
@endsection
```

#### **Admin Dashboard Page**

```blade
@extends('admin.layout')

@section('title', 'Dashboard')
@section('breadcrumb', 'Home > Dashboard')

@section('content')
<div>
    <h1 class="text-3xl font-bold">Dashboard</h1>

    {{-- Success Alert --}}
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-4 gap-6 mt-6">
        <!-- Your stats cards -->
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl shadow-md p-6 mt-6">
        <h2 class="text-xl font-bold mb-4">Recent Users</h2>
        <!-- Your table -->
    </div>
</div>
@endsection
```

#### **User Create/Edit Form (Admin)**

```blade
@extends('admin.layout')

@section('title', isset($user) ? 'Edit User' : 'Create User')
@section('breadcrumb', 'Home > Users > ' . (isset($user) ? 'Edit' : 'Create'))

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <h1 class="text-2xl font-bold mb-6">
        {{ isset($user) ? 'Edit User' : 'Create New User' }}
    </h1>

    @if($errors->any())
        <x-alert type="error">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="grid grid-cols-2 gap-6">
            <x-forms.input
                name="name"
                label="Full Name"
                icon="user"
                :value="$user->name ?? null"
                required
            />

            <x-forms.input
                name="email"
                label="Email Address"
                type="email"
                icon="envelope"
                :value="$user->email ?? null"
                required
            />

            <x-forms.input
                name="phone"
                label="Phone Number"
                icon="phone"
                :value="$user->phone ?? null"
                pattern="^(\+62|62|0)[0-9]{9,12}$"
                required
            />

            @if(!isset($user))
            <x-forms.input
                name="password"
                label="Password"
                type="password"
                icon="lock"
                required
            />
            @endif
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                {{ isset($user) ? 'Update User' : 'Create User' }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
```

---

### 4. **Flash Messages di Controller**

```php
// Success message
return redirect()->route('admin.users.index')
    ->with('success', 'User created successfully!');

// Error message
return redirect()->back()
    ->with('error', 'Failed to delete user!');

// Warning message
return redirect()->route('admin.dashboard')
    ->with('warning', 'Please complete your profile!');
```

---

### 5. **Validation Errors**

Laravel otomatis menangani validation errors. Cukup gunakan:

```blade
@if($errors->any())
    <x-alert type="error">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
```

Atau untuk error per field (sudah otomatis di `<x-forms.input>`):

```blade
@error('email')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@enderror
```

---

## ✨ Keuntungan Menggunakan Blade Components

1. **Reusable** - Buat sekali, pakai berkali-kali
2. **Consistent** - UI/UX yang konsisten di seluruh aplikasi
3. **Clean Code** - View lebih bersih dan mudah dibaca
4. **Easy Maintenance** - Update di satu tempat, apply ke semua
5. **Type Safety** - Props dengan type hints
6. **Auto Features** - Error handling, old input, dll sudah built-in

---

## 📂 Struktur File

```
resources/views/
├── layouts/
│   └── auth.blade.php              # Auth layout
├── admin/
│   ├── layout.blade.php             # Admin layout
│   ├── dashboard.blade.php          # Dashboard page
│   └── users/
│       ├── index.blade.php          # User list
│       ├── create.blade.php         # Create user
│       └── edit.blade.php           # Edit user
├── auth/
│   ├── login.blade.php              # Login page
│   └── register.blade.php           # Register page
└── components/
    ├── alert.blade.php              # Alert component
    └── forms/
        └── input.blade.php          # Input component

app/View/Components/
├── Alert.php                        # Alert component class
└── Forms/
    └── Input.php                    # Input component class
```

---

## 🎯 Next Steps - Component Lain yang Bisa Dibuat

1. **Button Component** - `<x-button>`
2. **Card Component** - `<x-card>`
3. **Modal Component** - `<x-modal>`
4. **Select Component** - `<x-forms.select>`
5. **Textarea Component** - `<x-forms.textarea>`
6. **Badge Component** - `<x-badge>`
7. **Table Component** - `<x-table>`

---

Dengan Blade Components ini, kode menjadi lebih **clean**, **reusable**, dan **maintainable**! 🚀
