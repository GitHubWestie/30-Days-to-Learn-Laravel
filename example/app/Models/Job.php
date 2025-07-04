<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model // Extending to Model gives access to all of it's methods
{
    use HasFactory;
    
    protected $table = 'job_listings'; // Tells Eloquent the table name if the class name doesn't match

    // protected $guarded = [];
    protected $fillable = [
        'employer_id',
        'title',
        'salary',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, table:"job_tags", foreignPivotKey:"job_listings_id");
    }
}