<?php

namespace RbacAuth\Console;

use Illuminate\Console\Command;
use RbacAuth\Database\Seeders\BasicSeeder;

class RbacSeedCommand extends Command
{
    protected $signature = 'rbac:seed';

    protected $description = 'Seed default roles, permissions, and users for RBAC package';

    public function handle(): int
    {
        $this->info('Seeding RBAC data...');

        require __DIR__ . '/../../database/seeders/BasicSeeder.php'; 

        // Run your package seeder
        $seeder = new BasicSeeder();
        $seeder->run();

        $this->info('RBAC seeding completed successfully!');
        return Command::SUCCESS;
    }
}
