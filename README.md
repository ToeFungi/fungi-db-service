# Fungi DB Service

Build up SQL queries quickly and easily

#### Register provider in `bootstrap/app.php`
```php
$app->register(ToeFungi\QueryBuilder\Laravel\Providers\QueryBuilderServiceProvider::class);
```

#### Usage example
Simple get
```php
$queryBuilder->select()
            ->setTable('users')
            ->setColumns(['id', 'firstname'])
            ->generate();
```

Multiple Tables
```php
$queryBuilder->select()
             ->setTables(['locations', 'prefixes'])
             ->setColumns([['id', 'location'], ['prefix']])
             ->joinOn('locations', 'prefix_id', 'prefixes', 'id')
             ->whereEquals('prefixes', 'prefix', $prefix)
             ->generate();
```