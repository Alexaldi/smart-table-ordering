<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SeederUserDisc extends Seeder
{
    public function run(): void
    {
        $this->call(DatabaseSeeder::class);
    }
}
