<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

class ModelSyntaxBuilder extends AbstractSintaxBuilder
{
    /**
     * @param array $schema
     * @return string
     */
    public function create(array $schema): string
    {
        return $this->createSchema($schema);
    }

    /**
     * @param array $schema
     * @return string
     */
    private function createSchema(array $schema): string
    {
        $fields = $this->constructSchema($schema);

        return $this->insert($fields)->into($this->getSchemaWrapper());
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
     * @return string
     */
    private function getSchemaWrapper(): string
    {
        return file_get_contents($this->resolveStubPath('/app/Models/Model.php.stub'));
    }

    /**
     * @param array $schema
     * @return string
     */
    private function constructSchema(array $schema): string
    {
        if (! $schema) {
            return '';
        }

        $fields = array_map(function ($field) {
            return $this->addColumn($field);
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
        return sprintf("'%s',", $field['name']);
    }
}
