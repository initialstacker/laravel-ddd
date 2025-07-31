<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};
use Carbon\CarbonImmutable;

final class UserSeeder extends Seeder
{
    private array $users = [
        [
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'password' => '0>pzX;|3&CRoN8*U_',
        ],
        [
            'name' => 'user',
            'email' => 'user@mail.ru',
            'password' => 'cXdF_s#K^]LvMdUI.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = DB::table(table: 'roles')->pluck('id', 'slug');
        $now = CarbonImmutable::now();

        $users = array_map(
            callback: fn (array $user): array => [
                'id' => str()->uuid()->toString(),
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => $now,
                'role_id' => $roles[$user['name']] ?? null,
                'password' => Hash::make(
                    value: $user['password']
                ),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            array: $this->users
        );

        DB::table(table: 'users')->insert($users);
    }
}
