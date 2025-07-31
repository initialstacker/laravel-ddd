<?php declare(strict_types=1);

namespace App\Account\Presentation\Request;

use Illuminate\Validation\Rules\Password;
use App\Shared\Presentation\Request;

final class UpdateProfileRequest extends Request
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
            'name' => ['bail', 'required', 'string', 'min:2', 'max:46'],
            'email' => [
                'bail', 'required', 'email:rfc,strict,spoof,dns', 'max:254', 'unique:users,email,' . $auth->user->id
            ],
            'password' => ['bail', 'required', 'string', $pwdRules, 'confirmed'],
        ];
    }
}
