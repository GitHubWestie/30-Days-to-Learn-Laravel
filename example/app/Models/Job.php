<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model // Extending to Model gives access to all of it's methods
{
    protected $table = 'job_listings'; // Tells Eloquent the table name if the class name doesn't match

    protected $fillable = [
        'title',
        'salary',
    ];
}