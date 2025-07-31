<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

final class RoleSeeder extends Seeder
{
    private array $roles = [
        [
            'name' => 'Администратор',
            'slug' => 'admin'
        ],
        [
            'name' => 'Пользователь',
            'slug' => 'user'
        ],
    ];

    public function run(): void
    {
        $data = array_map(fn (array $role): array
            => [
                'id' => str()->uuid()->toString(),
                'name' => $role['name'],
                'slug' => $role['slug'],
                'created_at' => $now = CarbonImmutable::now(),
                'updated_at' => $now
            ],
            $this->roles
        );

        DB::table('roles')->insert($data);
    }
}
