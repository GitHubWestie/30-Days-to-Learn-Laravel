<?php

namespace Database\Seeders;

use App\Models\Job as ModelsJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Job extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsJob::factory(10)->create();
    }
}
