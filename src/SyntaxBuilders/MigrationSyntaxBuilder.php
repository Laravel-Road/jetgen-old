<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

use LaravelRoad\JetGen\Contracts\Replaceable;

class MigrationSyntaxBuilder extends AbstractSintaxBuilder implements Replaceable
{
    /**
     * @param array $schema
     * @return array
     */
    public function create(array $schema): array
    {
        $up = $this->createSchemaForUpMethod($schema);

        return compact('up');
    }

    /**
     * @param array $schema
     * @return string
     */
    private function createSchemaForUpMethod(array $schema): string
    {
        $fields = $this->constructSchema($schema);

        return $this->insert($fields)->into($this->getCreateSchemaWrapper());
    }

    /**
     * @param string $template
     * @return MigrationSyntaxBuilder
     */
    private function insert(string $template): MigrationSyntaxBuilder
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
        return str_replace('{{schema_up}}', $this->template, $wrapper);
    }

    /**
     * @return string
     */
    private function getCreateSchemaWrapper(): string
    {
        return file_get_contents($this->resolveStubPath('/database/migrations/schema-create.stub'));
    }

    /**
     * @param array $schema
     * @param string $direction
     * @return string
     */
    private function constructSchema(array $schema): string
    {
        $fields = array_map(fn ($field) => $this->addColumn($field), $schema);

        return implode("\n".str_repeat(' ', 12), $fields);
    }

    /**
     * @param array $field
     * @return string
     */
    private function addColumn(array $field): string
    {
        $syntax = sprintf("\$table->%s('%s')", $field['type'], $field['name']);

        // If there are arguments for the schema type, like decimal('amount', 5, 2)
        // then we have to remember to work those in.
        if ($field['arguments']) {
            $syntax = substr($syntax, 0, -1).', ';

            $syntax .= implode(', ', $field['arguments']).')';
        }

        foreach ($field['options'] as $method => $value) {
            $syntax .= sprintf('->%s(%s)', $method, $value === true ? '' : $value);
        }

        return $syntax . ';';
    }
}
