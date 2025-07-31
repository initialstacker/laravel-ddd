<?php declare(strict_types=1);

namespace App\Account\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Account\Domain\Changer\RoleStateChange;
use App\Account\Domain\Relationship\RoleRelationship;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Id\RoleId;
use App\Shared\Domain\Slug\RoleSlug;

#[ORM\Entity]
#[ORM\Table(name: 'roles')]
#[ORM\HasLifecycleCallbacks]
class Role extends AggregateRoot
{
    /**
     * Automatically manages created_at and updated_at timestamps.
     */
    use CreatedDateProvider;
    use UpdatedDateProvider;

    /**
     * Provides methods for role attribute modifications.
     */
    use RoleStateChange;

    /**
     * Contains setters and mutator methods for role data management.
     */
    use RoleRelationship;

    /**
     * Unique identifier for the role.
     *
     * @var RoleId
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: RoleId::class, unique: true)]
    public private(set) RoleId $id;

    /**
     * The role's name.
     *
     * @var string
     */
    #[Assert\NotBlank(message: 'Name should not be blank.')]
    #[ORM\Column(name: 'name', type: Types::STRING, length: 13)]
    public private(set) string $name {
        set (string $value) {
            $value = trim(string: $value);
            $name = mb_convert_case(string: $value, mode: MB_CASE_TITLE);

            $this->name = $name;
        }
    }

    /**
     * The slug associated with the role.
     *
     * @var RoleSlug
     */
    #[Assert\Valid]
    #[ORM\Column(name: 'slug', type: RoleSlug::class, unique: true)]
    public private(set) RoleSlug $slug;

    /**
     * Permissions assigned to the role.
     *
     * @var Collection<int, Permission>
     */
    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'roles')]
    #[ORM\JoinTable(name: 'role_permission')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'permission_id', referencedColumnName: 'id')]
    public private(set) Collection $permissions;

    /**
     * Collection of users associated with this role.
     *
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'role')]
    public private(set) Collection $users;

    /**
     * Initializes a new Role instance.
     *
     * @param RoleId|null $id
     * @param string $name
     * @param RoleSlug $slug
     */
    public function __construct(
        string $name,
        RoleSlug $slug,
        ?RoleId $id = null,
    ) {
        /**
         * Generates a new RoleId if none is provided.
         */
        $this->id = $id ?? RoleId::generate();

        $this->name = $name;
        $this->slug = $slug;

        /**
         * Initializes the permissions collection.
         */
        $this->permissions = new ArrayCollection();

        /**
         * Initializes the users collection.
         */
        $this->users = new ArrayCollection();

        /**
         * Initialize created_at and updated_at timestamps.
         */
        $this->initializeCreatedAt();
        $this->initializeUpdatedAt();
    }
}
