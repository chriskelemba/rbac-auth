<?php

namespace RbacAuth\Database\Seeders;

use Illuminate\Database\Seeder;
use RbacAuth\Database\Seeders\BasicSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BasicSeeder::class);
    }
}
