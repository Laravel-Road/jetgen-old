<?php

namespace LaravelRoad\JetGen\Traits;

use LaravelRoad\JetGen\Contracts\Replaceable;

trait ReplaceConventions
{
    use NamingConventions;

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceNamespace(string &$stub): Replaceable
    {
        $stub = str_replace('{{namespace}}', $this->namespace, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceTestNamespace(string &$stub): Replaceable
    {
        $namespace = $this->namespace === 'App' ? 'Tests' : $this->namespace.'\Tests';

        $stub = str_replace('{{namespace}}', $namespace, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceModelNamespace(string &$stub): Replaceable
    {
        $stub = str_replace('{{modelNamespace}}', $this->namespace.'\\Models', $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceModelName(string &$stub): Replaceable
    {
        $stub = str_replace('{{modelName}}', $this->model, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceControllerName(string &$stub): Replaceable
    {
        $stub = str_replace('{{controllerName}}', $this->controller, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceTableName(string &$stub): Replaceable
    {
        $stub = str_replace('{{tableName}}', $this->table, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceRouteName(string &$stub): Replaceable
    {
        $stub = str_replace('{{routeName}}', $this->route, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceParamName(string &$stub): Replaceable
    {
        $stub = str_replace('{{paramName}}', $this->param, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceObjectName(string &$stub): Replaceable
    {
        $stub = str_replace('{{objectName}}', $this->object, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceCollectionName(string &$stub): Replaceable
    {
        $stub = str_replace('{{collectionName}}', $this->collection, $stub);

        return $this;
    }
}
