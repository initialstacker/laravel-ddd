<?php declare(strict_types=1);

namespace App\Account\Domain\Changer;

use App\Account\Domain\Avatar;
use App\Account\Domain\Password\Password;
use App\Shared\Domain\Email\Email;

trait UserStateChange
{
    /**
     * Change the user's avatar.
     *
     * @param Avatar|null $avatar
     */
    public function changeAvatar(?Avatar $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * Change the user's name.
     *
     * @param string $name
     */
    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Change the user's email.
     *
     * @param Email $email
     */
    public function changeEmail(Email $email): void
    {
        $this->email = $email;
    }

    /**
     * Change the user's password.
     *
     * @param Password $password
     */
    public function changePassword(Password $password): void
    {
        $this->password = $password;
    }
}
