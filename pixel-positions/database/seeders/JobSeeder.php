<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some tags for the jobs
        $tags = Tag::factory(3)->create();

        // Use the relationship to attach the tags to the jobs
        Job::factory(20)->hasAttached($tags)->create();
    }
}
