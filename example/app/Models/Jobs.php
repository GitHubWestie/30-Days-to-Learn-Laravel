<?php

namespace App\Models;

use Illuminate\Support\Arr;

class Jobs
{
    public static function all(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Director',
                'salary' => '$50,000',
            ],
            [   
                'id' => 2,
                'title' => 'Programmer',
                'salary' => '$10,000',
            ],
            [
                'id' => 3,
                'title' => 'Teacher',
                'salary' => '$40,000',
            ],
        ];
    }

    public static function find(int $id): array
    {
        // Returns the first job that matches the given $id
        $job = Arr::first(Jobs::all(), fn($job)=> $job['id'] == $id);

        if(!$job) {
            abort(404);
        }

        return $job;
    }
}