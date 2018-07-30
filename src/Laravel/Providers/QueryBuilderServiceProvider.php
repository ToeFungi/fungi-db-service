<?php namespace ToeFungi\QueryBuilder\Laravel\Providers;

use ToeFungi\QueryBuilder\QueryBuilder;
use ToeFungi\QueryBuilder\IQueryBuilder;

use Illuminate\Support\ServiceProvider;

class QueryBuilderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IQueryBuilder::class, function () {
            return new QueryBuilder();
        });
    }
}