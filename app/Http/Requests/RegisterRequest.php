<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => ['required'],
            'role' => ['required', 'in:buyer,seller'],
            'phone' => ['nullable', 'string', 'max:20'],
            'store_name' => ['required_if:role,seller', 'nullable', 'string', 'max:255', 'unique:users,store_name'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'role.required' => 'Tipe akun harus dipilih.',
            'role.in' => 'Tipe akun harus buyer atau seller.',
            'store_name.required_if' => 'Nama toko harus diisi untuk penjual.',
            'store_name.unique' => 'Nama toko sudah terdaftar.',
        ];
    }
}
