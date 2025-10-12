<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Admin;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $admin = auth('admin')->user();
        // Superadmins podem editar ou admins com permissão específica
        return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('config_sistema', 'update'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $adminId = $this->route('admin')->id;
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $adminId,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'boolean',
            'is_superadmin' => 'boolean',
            'permissions' => 'array',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_create' => 'boolean',
            'permissions.*.can_update' => 'boolean',
            'permissions.*.can_delete' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está em uso.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'nome',
            'email' => 'email',
            'password' => 'senha',
            'status' => 'status',
            'is_superadmin' => 'super administrador',
        ];
    }
}
