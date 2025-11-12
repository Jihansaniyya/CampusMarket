@extends('layouts.auth')

@section('title', 'Register')

@section('body-class', 'min-h-screen bg-gradient-to-br from-blue-50 to-purple-50')

@section('content')
    <div class="min-h-screen flex items-center justify-center p-4">
        <div
            class="max-w-4xl w-full bg-white/90 backdrop-blur-lg shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row">

            <!-- Left Side - Brand Image -->
            <div class="md:w-1/2 bg-linear-to-br from-blue-600 to-purple-600 p-8 flex flex-col justify-center text-white">
                <div class="text-center">
                    <h1 class="text-5xl font-bold mb-4">🛒 CampusMarket</h1>
                    <p class="text-xl mb-6">Your Campus Marketplace</p>
                    <div class="space-y-4 text-left">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                            <span>Buy and Sell Campus Products</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                            <span>Connect with Students</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                            <span>Safe and Trusted Platform</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="md:w-1/2 p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Create Account</h2>
                <p class="text-gray-600 mb-6">Join CampusMarket today!</p>

                <!-- Error Messages -->
                @if ($errors->any())
                    <x-alert type="error" class="text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                <form action="{{ route('register') }}" method="POST" id="registerForm">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-semibold mb-2 text-sm">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Enter your full name" required minlength="3">
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-semibold mb-2 text-sm">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="your@email.com" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 font-semibold mb-2 text-sm">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="081234567890" required pattern="^(\+62|62|0)[0-9]{9,12}$">
                        <p class="text-xs text-gray-500 mt-1">Format: 081234567890</p>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-semibold mb-2 text-sm">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="Create a strong password" required minlength="8"
                                onkeyup="checkPasswordStrength()">
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                <i id="password-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex gap-1 mb-1">
                                <div id="strength-1" class="h-1 flex-1 bg-gray-200 rounded"></div>
                                <div id="strength-2" class="h-1 flex-1 bg-gray-200 rounded"></div>
                                <div id="strength-3" class="h-1 flex-1 bg-gray-200 rounded"></div>
                                <div id="strength-4" class="h-1 flex-1 bg-gray-200 rounded"></div>
                            </div>
                            <p id="strength-text" class="text-xs text-gray-500">Minimum 8 characters, include uppercase,
                                lowercase, and number</p>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2 text-sm">Confirm
                            Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="Re-enter your password" required>
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                <i id="password_confirmation-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">Register as</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label
                                class="border-2 border-gray-300 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition-all">
                                <input type="radio" name="role" value="buyer" class="sr-only peer" checked>
                                <div class="text-center peer-checked:text-blue-600">
                                    <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                                    <p class="font-semibold">Buyer</p>
                                    <p class="text-xs text-gray-500">I want to buy</p>
                                </div>
                            </label>
                            <label
                                class="border-2 border-gray-300 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition-all">
                                <input type="radio" name="role" value="seller" class="sr-only peer">
                                <div class="text-center peer-checked:text-blue-600">
                                    <i class="fas fa-store text-3xl mb-2"></i>
                                    <p class="font-semibold">Seller</p>
                                    <p class="text-xs text-gray-500">I want to sell</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" required
                                class="w-4 h-4 mt-1 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">
                                I agree to the <a href="#" class="text-blue-600 hover:underline">Terms &
                                    Conditions</a> and <a href="#" class="text-blue-600 hover:underline">Privacy
                                    Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-linear-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg hover:scale-105 transform transition-all shadow-lg font-semibold">
                            Create Account
                        </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strength1 = document.getElementById('strength-1');
            const strength2 = document.getElementById('strength-2');
            const strength3 = document.getElementById('strength-3');
            const strength4 = document.getElementById('strength-4');
            const strengthText = document.getElementById('strength-text');

            let strength = 0;

            // Reset
            [strength1, strength2, strength3, strength4].forEach(el => {
                el.className = 'h-1 flex-1 bg-gray-200 rounded';
            });

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;

            const colors = ['bg-gray-200', 'bg-red-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
            const texts = ['', 'Weak', 'Fair', 'Good', 'Strong'];
            const textColors = ['text-gray-500', 'text-red-500', 'text-yellow-500', 'text-blue-500', 'text-green-500'];

            if (strength >= 1) strength1.className = `h-1 flex-1 ${colors[strength]} rounded`;
            if (strength >= 2) strength2.className = `h-1 flex-1 ${colors[strength]} rounded`;
            if (strength >= 3) strength3.className = `h-1 flex-1 ${colors[strength]} rounded`;
            if (strength >= 4) strength4.className = `h-1 flex-1 ${colors[strength]} rounded`;

            strengthText.className = `text-xs ${textColors[strength]}`;
            strengthText.textContent = texts[strength] || 'Enter password';
        }
    </script>
@endpush

@push('styles')
    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>
@endpush
