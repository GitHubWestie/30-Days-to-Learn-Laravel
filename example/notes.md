# Pivot Tables and belongsToMany Relationships

Pivot tables are commonly used to connect two or more tables together. In this instance the job_tags table will be used to connect the jobs table and the tags table. A common naming convention when creating a pivot table is to use the singular of the tables being connected in alphabetical order.

## Create the Model, Migration and Factory
```php
php artisan make:model -mf
```

## Configure the Migration
There are no rules that say that a migration file can only contain a single table schema. Although this is often how migration files end up it depends on the needs of the file/project at the time. If it suits there is nothing wrong with having more than one schema in a migration.

```php
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('job_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Job::class, 'job_listings_id'); // 'job_listings_id' ensures that Laravel uses our job listings table and NOT the jobs table
            $table->foreignIdFor(App\Models\Tag::class);
            $table->timestamps();
        });
    }
```

## Constraints
One issue with the schemas above is there are no constraints on them. This means that if a tag was to be deleted from it's table it will still remain connected to the jobs table, even though it no longer exists.

This can be fixed with by using constraints defined in the schema.

```php
	Schema::create('job_tags', function (Blueprint $table) {
		$table->id();
		$table->foreignIdFor(App\Models\Job::class, 'job_listings_id')->constrained()->cascadeOnDelete();
		$table->foreignIdFor(App\Models\Tag::class)->constrained()->cascadeOnDelete();
		$table->timestamps();
	});
```

## SQLite Nuance
And even though everything is now correct, it still wont work. By default SQLite does not enforce constraints. To change this behaviour execute this line as a query in table plus or whatever.

```sql
PRAGMA foreign_keys=ON
```

Now, whenever a tag or job is removed from the database it will cascade and be removed from the job_listings table and job_tags pivot table too.

## Relationships
Defining the relationship between these works the same way as other relationships only they have a slightly different relationship. The syntax remains the same though.

```php
// App\Models\Tag.php
public function job()
{
	return $this->belongsToMany(Job::class);
}

// App\Models\Job.php
public function tags()
{
	return $this->belongsToMany(Tag::class);
}
```

A `belongsToMany()` relationship is just a `manyToMany` relationship. A job can have many tags and a tag can have many jobs.

## Test in Tinker
If we test the relationships in tinker we'll get an error

```php
Illuminate\Database\QueryException  SQLSTATE[HY000]: General error: 1 no such table: job_tag (Connection: sqlite, SQL: select "tags".*, "job_tag"."job_id" a
s "pivot_job_id", "job_tag"."tag_id" as "pivot_tag_id" from "tags" inner join "job_tag" on "tags"."id" = "job_tag"."tag_id" where "job_tag"."job_id" = 7).
```

The important bit is this `no such table: job_tag`.

This happened because Laravel has made an assumption about what the column will be called based on the name of the class `Job`. But because the table isn't called job it's throwing an error. To fix this we can provide additional arguments to the `belongsToMany` method in the `tags()` method.

```php
    public function tags()
    {
        return $this->belongsToMany(
			Tag::class,
			table:"job_tags",
			foreignPivotKey:"job_listings_id"
		);
    }
```

*In my case it also made an incorrect assumption about the table name and the same incorrect assumptions for the jobs() method* 🤷🏻‍♂️

```php
    public function jobs()
    {
        return $this->belongsToMany(Job::class, table:'job_tags', relatedPivotKey:'job_listings_id');
    }
```

## More tinkering
In tinker there are a few ways that we can interact with these methods. 

### Attach
```php
// Will attach the tag in the $tag var to job_listing 7
$tag->jobs()->attach(7);
```
*Note how the job() method is called as a function here and NOT as a property*

### get()
But if you try and get this collection again the newly tagged job wont show up. This is because that query has already been loaded into tinkers memory so it wont actually run the query again. To get around this use `get()`.

```php
$tag->jobs()->get();
```

### pluck()
Tinker can even pluck specific values from a record using the `pluck()` method
```php
$tag->job()->get()-pluck('title');
```