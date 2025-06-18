# Model Factories
Factories allow you to scaffold or generate fake data for your models. For example, you might want to create 10 users for testing or populate your local environment with many job listings without manually entering each record.

Some default models provided by Laravel such as the users model will already have a 

```php
use HasFactory
```

This gives the model access to a bunch of factory methods. One of which being a static function, `factory()`.

Factories can be used just about anywhere you can write PHP. In this case we're using `tinker`. It works very similarly to how the `Job` class was accessed previously.

```php
App\Models\User::factory()->create();
```

The factory method can also be given an integer as an argument. This tells the fatory method how many fake entries we need to generate.

```php
App\Models\User::factory(100)->create();
```

## Creating Factories
You could copy an existing factory but it's almost always better to create a new one. This can be done using artisan.
```php
php artisan make:factory JobFactory
```

As with the models this will also provide extra output if `help` is called before the make:factory instruction

```php
php artisan help make:factory
```

Once the factory is created the return array can be populated with the data we want to generate.

```php
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'salary' => fake()->numberBetween(20000, 90000),
        ];
    }
```
*It is not required that the faker library be used. Hardcoded values are also accepted if that suits the needs of the factory.*

If a factory is called on a model that *doesn't* have `use HasFactory` declared within it the factory will fail. `HasFactory` must be present in a model for the factory to work. If a model is created using artisan `php artisan make:model Job` then Laravel will automatically add `HasFactory` in. If a model class is made manually though, the HasFactory trait must also be added manually.

## Different States
Different states can be called on factories too. For example the user factory has an unverified function.

```php
public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
```

When the 100 users were created earlier, all 100 users had the exact same `created_at` value in the database. If for example we needed this to be `null` the unverified method can be chained on to the factory method call.

```php
App\Model\User::factory()->unverified()->create()
```

## Eloquent Relationships
Imagine that the jobs board gets really popular and a large company signs up with heaps of job openings. We would want/need some way of tracking all of the jobs from that particular employer. This is where an eloquent relationship comes in.

### The relationship
In order to create an eloquent relationship there needs to be a relationship. In the case of this example an employer table would have an `id` column and that id would be *unique to the employer*. So let's use that id column as a `foreign_id` on the *jobs table* to create the relationship.

1. Make a an employer model and migration
    ```php
    php artisan make:model Employer -m -f
    ```
    The `-m` and `-f` flags at the end instructs laravel to make a migration and factory with this model. The great thing about this is it means everything follows the conventions that Laravel likes and we only write one line of code as a command!

2. Add the columns to the migrations
    * In the new `employers migration` add a name column as a string type
    * In the `job_listings migration` add a `employer_id` column as an `foreignIdFor()` or an `unsignedBigInteger()`. <-- *THIS is important* When an id is generated using `$table->id();` it uses the `unsignedBigInteger` type. If the foreign key referencing it isnt the same type it will cause a database error. 

    The alternative `foreignIdFor()` takes an Eloquent model as an argument meaning Laravel will know automagically what the type is.
    ```php
    // Laravel automagically knows where the foreign_id should point because it's given the class
    $table->foreignIdFor(Employer::class);
    ```

3. Migrate changes
    As this is very early days and only dummy data exists in the database run `php artisan migrate:fresh`. This will drop all tables and rebuild them from scratch so the database will be empty.

4. Configure the Employer factory
    Currently the employer table only has one column which is `name` so add the faker for it.
    ```php
    'name' => fake()->company();
    ```

    Then this factory can actually be used by the job_listings table to generate an id for the employer_id column. Update the JobFactory to use the EmployerFactory
    ```php
    // employer_id calls the factory and when the factory creates a record an id is made
    'employer_id' => Employer::factory(),
    ```
