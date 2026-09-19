<?php

namespace Modules\Pages\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'last_name' => 'required',
            'password' => 'required|min:6',
            'password_confirm' => 'required|min:6|same:password',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'last_name' => 'fullName',
            'password' => 'password',
        ];
    }
}
