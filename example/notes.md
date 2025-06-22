15.-Understanding-Database-Seeders
# Understanding Database Seeders
Seeders are extremely useful for quickly building up records in the database. Laravel supplies a user seeder class.

```php
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
```

To run a seeder run `php artisan db:seed` from the terminal. Another commonly used way is to run the seeders after dropping tables. `php artisan migrate:fresh --seed` This will drop all tables and their data and then immediately build the database back up again.

If required a project can have multiple seeder classes. Sometimes it can be useful to have a test seeder class that builds up the database in a specific way that allows for running a specific test scenario.

Seeders can be created using the `php artisan make:seeder` command.

Seeders can call other seeder classes using the `call()` method.

```php
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this-> call(JobSeeder::class);
    }
}
```

A specific seeder can also be called if needed using the --class flag in the command `php artisan db:seed --class=JobSeeder`