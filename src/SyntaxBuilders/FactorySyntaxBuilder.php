<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

use Illuminate\Support\Str;
use LaravelRoad\JetGen\Contracts\Replaceable;

class FactorySyntaxBuilder extends AbstractSintaxBuilder implements Replaceable
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
     * @return FactorySyntaxBuilder
     */
    private function insert(string $template): FactorySyntaxBuilder
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
        return file_get_contents($this->resolveStubPath('/database/factories/factory.php.stub'));
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

        return implode("\n".str_repeat(' ', 12), $this->removeEmpty($fields));
    }

    /**
     * @param array $field
     * @return string
     */
    private function addColumn(array $field): string
    {
        if ($this->hasForeignConstraint($field)) {
            $foreignName = Str::studly(str_replace('_id', '', $field['name']));

            return sprintf("'%s' => {{modelNamespace}}\\$foreignName::factory()->create(),", $field['name']);
        }

        if (strpos($field['name'], '_id')) {
            return '';
        }

        return sprintf("'%s' => \$this->faker->%s,", $field['name'], $this->getFakerType($field));
    }

    /**
     * @param array $field
     * @return string
     */
    private function getFakerType(array $field): string
    {
        $type = 'text(';

        if (in_array($field['type'], $this->stringTypes)) {
            $type = 'text(';
        }

        if (in_array($field['type'], $this->integerTypes)) {
            $type = 'randomNumber(';
        }

        if (in_array($field['type'], $this->floatTypes)) {
            $type = 'randomFloat(';

            if ($field['arguments']) {
                $type .= $field['arguments'][1].', 0, '.str_repeat('9', $field['arguments'][0] - $field['arguments'][1]).'.'.str_repeat('9', $field['arguments'][1]);
            }
        }

        if (in_array($field['type'], $this->dateTypes)) {
            $type = 'date(';
        }

        if ($field['type'] === 'boolean') {
            $type = 'boolean(';
        }

        if (!in_array($field['type'], $this->floatTypes) && $field['arguments']) {
            $type .= implode(', ', $field['arguments']);
        }

        return $type.')';
    }
}
