# Two Key Eloquent Relationship Types
Eloquent has a powerful feature that allows us to create relationships between models quickly. For example in this case we have a jobs table and an employers table. An employer may have many jobs but a job will only belong to one employer. Eloquent relationships are defined exactly how you might describe it.

```php
class Employer extends Model
{
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}

class Job extends Model
{
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }
}
```

One thing that can be confusing with these methods is that they are not called like methods. Instead they are used as if they were properties. We can test them in tinker like so:

```php
$employer = App\Models\Employer::first();
= App\Models\Employer {#5997
    id: 1,
    name: "Boehm, Schmeler and Gutmann",
    created_at: "2025-06-17 21:25:03",
    updated_at: "2025-06-17 21:25:03",
  }

$employer->jobs;
= Illuminate\Database\Eloquent\Collection {#5236
    all: [
      App\Models\Job {#5232
        id: 1,
        employer_id: 1,
        title: "Dragline Operator",
        salary: "51411",
        created_at: "2025-06-17 21:25:03",
        updated_at: "2025-06-17 21:25:03",
      },
    ],
  }
```
Eloquent will return eloquent collections which are just arrays of data. They can be treated like arrays too which means they can looped over or anything else that can be done with an array. 

Accessing data in the array can be done like an array or an method chaining.

```php
> $employer->jobs->first();
= App\Models\Job {#5232
    id: 1,
    employer_id: 1,
    title: "Dragline Operator",
    salary: "51411",
    created_at: "2025-06-17 21:25:03",
    updated_at: "2025-06-17 21:25:03",
  }

> $employer->jobs[0];
= App\Models\Job {#5232
    id: 1,
    employer_id: 1,
    title: "Dragline Operator",
    salary: "51411",
    created_at: "2025-06-17 21:25:03",
    updated_at: "2025-06-17 21:25:03",
  }
```

Unbelievably simple and *eloquent* 👀

## Lazy Loading
What's actually happening here is known as `lazy loading`. This refers to the act of leaving delaying a SQL query until the last possible moment. The last possible moment being the moment we request that data from the query.

