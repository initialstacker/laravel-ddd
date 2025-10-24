<?php declare(strict_types=1);

namespace App\Account\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Email\Email;
use App\Shared\Domain\Email\EmailVerification;
use App\Shared\Domain\Id\UserId;
use App\Shared\Domain\Id\RoleId;
use App\Account\Domain\Changer\UserStateChange;
use App\Account\Domain\Relationship\UserRelationship;
use App\Account\Domain\Password\Password;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
class User extends AggregateRoot
{
    /**
     * Provides email verification functionality.
     */
    use EmailVerification;

    /**
     * Handles the generation and management of the user's remember-me token.
     */
    use RememberToken;

    /**
     * Automatically manages created_at and updated_at timestamps.
     */
    use CreatedDateProvider;
    use UpdatedDateProvider;

    /**
     * Provides methods for user attribute modifications.
     */
    use UserStateChange;

    /**
     * Contains setters and mutator methods for user data management.
     */
    use UserRelationship;

    /**
     * Unique identifier for the user.
     *
     * @var UserId
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: UserId::class, unique: true)]
    public private(set) UserId $id;

    /**
     * Avatar image path or filename.
     *
     * @var Avatar|null
     */
    #[ORM\Embedded(class: Avatar::class, columnPrefix: false)]
    public private(set) ?Avatar $avatar = null;

    /**
     * The user's name.
     *
     * @var string
     */
    #[ORM\Column(name: 'name', type: Types::STRING, length: 35)]
    public private(set) string $name {
        set (string $value) {
            $value = trim(string: $value);
            $name = mb_convert_case(string: $value, mode: MB_CASE_TITLE);

            $this->name = $name;
        }
    }

    /**
     * The user's email address.
     *
     * @var Email
     */
    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    public private(set) Email $email;

    /**
     * The user's password.
     *
     * @var Password
     */
    #[ORM\Embedded(class: Password::class, columnPrefix: false)]
    public private(set) Password $password;

    /**
     * The role assigned to the user.
     *
     * @var Role|null
     */
    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'users', cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    public private(set) ?Role $role = null;

    /**
     * Initializes a new user with the given details.
     *
     * @param string $name
     * @param Email $email
     * @param Password $password
     * @param Avatar|null $avatar
     * @param UserId|null $id
     */
    public function __construct(
        string $name,
        Email $email,
        Password $password,
        ?Avatar $avatar = null,
        ?UserId $id = null,
    ) {
        /**
         * Generates a new user ID if none is provided.
         */
        $this->id = $id ?? UserId::generate();

        /**
         * Assigns user personal and account details.
         */
        $this->avatar = $avatar;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;

        /**
         * Initialize created_at and updated_at timestamps.
         */
        $this->initializeCreatedAt();
        $this->initializeUpdatedAt();
    }
}
