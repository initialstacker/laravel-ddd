<?php declare(strict_types=1);

namespace App\Account\Presentation\Request;

use App\Shared\Presentation\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Query\Builder;

final class ResetPasswordRequest extends Request
{
	/**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pwdRules = Password::min(size: 8)->letters()->numbers()->symbols();
        
        /** @var \App\Account\Infrastructure\Auth\AuthUserAdapter|null $auth */
        $auth = $this->user();

        return [
            'email' => [
                'bail', 'required', 'email:rfc,strict,spoof,dns', 'max:254', 'exists:users,email'
            ],
            'password' => ['bail', 'required', 'string', $pwdRules, 'confirmed'],
            'token' => ['bail', 'required', 'string']
        ];
    }
}
