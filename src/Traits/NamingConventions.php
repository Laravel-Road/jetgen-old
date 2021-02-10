<?php

namespace LaravelRoad\JetGen\Traits;

use Illuminate\Support\Str;

trait NamingConventions
{
    /**
     * @var string
     */
    public string $namespace;

    /**
     * @var string
     */
    public string $model;

    /**
     * @var string
     */
    public string $controller;

    /**
     * @var string
     */
    public string $table;

    /**
     * @var string
     */
    public string $route;

    /**
     * @var string
     */
    public string $param;

    /**
     * @var string
     */
    public string $object;

    /**
     * @var string
     */
    public string $collection;

    /**
     * @param string $name
     * @return void
     */
    public function setNames(string $name): void
    {
        $this->namespace = 'App';

        $this->model = Str::studly(Str::singular($name));

        $this->controller = $this->model.'Controller';

        $this->table = Str::snake(Str::plural($this->model));

        $this->route = Str::kebab($this->table);
        $this->param = Str::kebab($this->model);

        $this->object = Str::camel($this->model);
        $this->collection = Str::camel($this->table);
    }
}
