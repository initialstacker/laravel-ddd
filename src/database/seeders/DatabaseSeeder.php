<?php declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    private array $seeders = [
        RoleSeeder::class,
        UserSeeder::class,
    ];
    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(class: $this->seeders);
    }
}
