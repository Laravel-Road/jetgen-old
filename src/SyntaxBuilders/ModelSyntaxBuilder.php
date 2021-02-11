<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

use Illuminate\Support\Str;

class ModelSyntaxBuilder extends AbstractSintaxBuilder
{
    /**
     * @param array $schema
     * @return string
     */
    public function create(array $schema): array
    {
        $column = $this->createSchemaForColumn($schema);
        $foreign = $this->createSchemaForForeign($schema);

        return compact('column', 'foreign');
    }

    /**
     * @param array $schema
     * @return string
     */
    private function createSchemaForColumn(array $schema): string
    {
        $fields = $this->constructSchema($schema);

        return $this->insert($fields)->into($this->getSchemaWrapper());
    }

    /**
     * Create the schema for the foreign methods.
     *
     * @param  array $schema
     * @return string
     */
    private function createSchemaForForeign(array $schema): string
    {
        return $this->constructSchema($schema, 'addForeign');
    }

    /**
     * @param string $template
     * @return ModelSyntaxBuilder
     */
    private function insert(string $template): ModelSyntaxBuilder
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param string $wrapper
     * @return string
     */
    private function into(string $wrapper): string
    {
        return str_replace('{{column}}', $this->template, $wrapper);
    }

    /**
     * @param string $type
     * @return string
     */
    private function getSchemaWrapper(string $type = 'Column'): string
    {
        return file_get_contents($this->resolveStubPath("/app/Models/{$type}Model.php.stub"));
    }

    /**
     * @param array $schema
     * @param string $method
     * @return string
     */
    private function constructSchema(array $schema, string $method = 'addColumn'): string
    {
        if (! $schema) {
            return '';
        }

        $fields = array_map(function ($field)  use ($method) {
            return $this->$method($field);
        }, $schema);

        return implode("\n" . str_repeat(' ', 8), $this->removeEmpty($fields));
    }

    /**
     * Construct the syntax to add a column.
     *
     * @param  array $field
     * @return string
     */
    private function addColumn(array $field): string
    {
        if($this->hasForeignConstraint($field))
            return '';

        return sprintf("'%s',", $field['name']);
    }

    /**
     * @param array $field
     * @return string
     */
    private function addForeign(array $field): string
    {
        if (! $this->hasForeignConstraint($field))
            return '';

        $objectForeign = Str::singular(str_replace("'", '', $field['options']['on']));

        return str_replace(
            ['{{objectForeigntName}}', '{{ModelForeigntName}}'],
            [$objectForeign, ucwords($objectForeign)],
            $this->getSchemaWrapper('Foreign')
        );
    }
}
