<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

use LaravelRoad\JetGen\Traits\StubResolver;

class AbstractSintaxBuilder
{
    use StubResolver;

    /**
     * @var string
     */
    protected string $template;

    /**
     * @var array
     */
    protected array $bluePrintTypes;

    /**
     * @var array
     */
    protected array $stringTypes;

    /**
     * @var array
     */
    protected array $integerTypes;

    /**
     * @var array
     */
    protected array $floatTypes;

    /**
     * @var array
     */
    protected array $dateTypes;

    /**
     * @var array
     */
    protected array $foreignTypes;

    public function __construct()
    {
        $this->bluePrintTypes = config('jetgen.blueprint_types');
        $this->stringTypes = config('jetgen.string_types');
        $this->integerTypes = config('jetgen.integer_types');
        $this->floatTypes = config('jetgen.float_types');
        $this->dateTypes = config('jetgen.date_types');
        $this->foreignTypes = config('jetgen.foreign_types');
    }

    /**
     * @param array $field
     * @return bool
     */
    protected function hasForeignConstraint(array $field): bool
    {
        return in_array($field['type'], $this->foreignTypes);
    }

    /**
     * Remove empty fields.
     *
     * @param array $fields
     *
     * @return array
     */
    protected function removeEmpty(array $fields): array
    {
        foreach ($fields as $key => $field) {
            if ($field == '') {
                unset($fields[$key]);
            }
        }

        return $fields;
    }
}
