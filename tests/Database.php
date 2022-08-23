<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;

$capsule = new Capsule();

$adapter = getenv('ADAPTER');
if ($adapter == false) {
    $adapter = 'pgsql';
}

if ($adapter == 'pgsql') {
    $capsule->addConnection([
        'driver' => 'pgsql',
        'database' => 'tailslide_php_test'
    ]);
} elseif ($adapter == 'mysql') {
    $capsule->addConnection([
        'driver' => 'mysql',
        'database' => 'tailslide_php_test',
        'host' => 'localhost',
        'username' => get_current_user()
    ]);
} elseif ($adapter == 'sqlite') {
    $capsule->addConnection([
        'driver' => 'sqlite',
        'database' => ':memory:'
    ]);
} elseif ($adapter == 'sqlsrv') {
    $capsule->addConnection([
        'driver' => 'sqlsrv',
        'database' => 'tailslide_php_test',
        'host' => 'localhost',
        'username' => 'SA',
        'password' => 'YourStrong!Passw0rd'
    ]);
} else {
    throw new Exception('Invalid adapter');
}

echo "Using $adapter\n";

if (getenv('VERBOSE')) {
    $capsule->getConnection()->enableQueryLog();
    $capsule->getConnection()->setEventDispatcher(new \Illuminate\Events\Dispatcher());
    $capsule->getConnection()->listen(function ($query) {
        echo '[' . $query->time . '] ' . $query->sql . "\n";
    });
}

$capsule->setAsGlobal();
$capsule->bootEloquent();

Capsule::schema()->dropIfExists('users');
Capsule::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->integer('visits_count')->nullable();
    $table->decimal('latitude', 10, 5)->nullable();
    $table->float('rating')->nullable();
    $table->string('name')->nullable();
});

class User extends Model
{
    public $timestamps = false;
    protected $fillable = ['visits_count', 'latitude', 'rating', 'name'];
}

Tailslide\Builder::register();
