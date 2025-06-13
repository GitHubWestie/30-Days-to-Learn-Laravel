# Introduction to Migrations
Migration files are essentially blueprints for database tables. 

```php
public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
```

Once a migration file has been created or updated the migrations must be run in order for the changes to be reflected in the database. There are many migration options available through `php artisan`. Some are not suitable for use on a production database, for example `php artisan migrate:refresh`, as this would drop all data and rebuild the database from scratch so be aware when running migrations.

Other commands such as `php artisan migrate:rollback` allow the most recent migration to be undone. 

**Migration commands list**
```php
 migrate
  migrate:fresh             Drop all tables and re-run all migrations
  migrate:install           Create the migration repository
  migrate:refresh           Reset and re-run all migrations
  migrate:reset             Rollback all database migrations
  migrate:rollback          Rollback the last database migration
  migrate:status            Show the status of each migration
```
*Run php artisan in the terminal to see the list of available commands*

## Creating a Migration
To create a new migration file run `php artisan make:migration`. This will prompt for a name. Be aware of the names of existing tables when creating new ones. Avoid duplications!

Laravel will create a boilerplate migration file with an `up()` method closure and a `down()` method closure. The up method will contain the changes to apply to the database. The `down()` method should essentially undo whatever the `up()` method does.

```php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            // Add table columns in here...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
```

Then run `php artisan migrate` to push the new table to the database.