<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-users') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $editing = $this->route('user');
        $roles = array_map(fn (UserRole $role) => $role->value, UserRole::cases());

        if (! $this->user()->isSuperAdmin()) {
            $roles = array_diff($roles, [UserRole::SuperAdmin->value]);
        }

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($editing)],
            'password' => [$editing ? 'nullable' : 'required', 'string', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in($roles)],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
