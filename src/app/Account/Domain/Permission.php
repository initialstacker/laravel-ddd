<?php declare(strict_types=1);

namespace App\Account\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Account\Domain\Changer\PermissionStateChange;
use App\Account\Domain\Relationship\PermissionRelationship;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Id\PermissionId;
use App\Shared\Domain\Slug\PermissionSlug;

#[ORM\Entity]
#[ORM\Table(name: '`permissions`')]
#[ORM\HasLifecycleCallbacks]
class Permission extends AggregateRoot
{
    /**
     * Automatically manages created_at and updated_at timestamps.
     */
    use CreatedDateProvider;
    use UpdatedDateProvider;

    /**
     * Provides methods for permission attribute modifications.
     */
    use PermissionStateChange;

    /**
     * Contains setters and mutator methods for permission data management.
     */
    use PermissionRelationship;

    /**
     * Unique identifier for the permission.
     *
     * @var PermissionId
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: PermissionId::class, unique: true)]
    public private(set) PermissionId $id;
    
    /**
     * The permission's name.
     *
     * @var string
     */
    #[ORM\Column(name: 'name', type: Types::STRING, length: 13)]
    public private(set) string $name {
        set (string $value) {
            $value = trim(string: $value);
            $name = mb_convert_case(string: $value, mode: MB_CASE_TITLE);

            $this->name = $name;
        }
    }

    /**
     * The slug associated with the permission.
     *
     * @var PermissionSlug
     */
    #[ORM\Column(name: 'slug', type: PermissionSlug::class, unique: true)]
    public private(set) PermissionSlug $slug;

    /**
     * The guard type for the permission (e.g. 'api', 'web').
     *
     * @var Guard
     */
    #[ORM\Column(name: 'guard', type: Types::ENUM, enumType: Guard::class)]
    public private(set) Guard $guard;

    /**
     * Roles associated with the permission.
     *
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'permissions')]
    public private(set) Collection $roles;

    /**
     * Initializes a new Permission instance.
     *
     * @param PermissionId|null $id
     * @param string $name
     * @param PermissionSlug $slug
     * @param Guard $guard
     */
    public function __construct(
        string $name,
        PermissionSlug $slug,
        Guard $guard,
        ?PermissionId $id = null,
    ) {
        /**
         * Generates a new PermissionId if none is provided.
         */
        $this->id = $id ?? PermissionId::generate();

        $this->name = $name;
        $this->slug = $slug;
        $this->guard = $guard;

        /**
         * Initializes the roles collection.
         */
        $this->roles = new ArrayCollection();

        /**
         * Initialize created_at and updated_at timestamps.
         */
        $this->initializeCreatedAt();
        $this->initializeUpdatedAt();
    }
}
