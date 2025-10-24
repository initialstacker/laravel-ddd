<?php declare(strict_types=1);

namespace App\Account\Presentation\Request;

use App\Shared\Presentation\Request;

final class ForgotPasswordRequest extends Request
{
	/**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'bail', 'required', 'email:rfc,strict,spoof', 'max:254', 'exists:users,email' 
            ],
        ];
    }
}
