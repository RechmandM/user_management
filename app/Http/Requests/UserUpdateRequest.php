<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $email = $this->route('email');

        return [
            'name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z][A-Za-z ]*$/'],
            'password' => ['nullable', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'Nama harus diisi.',
            'name.regex' => 'Name harus dimulai dengan huruf dan hanya boleh berisi huruf serta spasi.',
            'password.regex' => 'Password harus berisi huruf kecil, huruf kapital, angka, dan karakter spesial.',
            'password.min' => 'Password minimal harus 6 karakter.'
        ];
    }
}
