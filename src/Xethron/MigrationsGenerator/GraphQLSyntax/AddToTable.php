<?php namespace Xethron\MigrationsGenerator\GraphQLSyntax;

/**
 * Class AddToTable
 * @package Xethron\MigrationsGenerator\Syntax
 */
class AddToTable extends Table
{
    /**
     * Return string for adding a column
     *
     * @param  array  $field
     * @return string
     */
    protected function getItem(array $field): string
    {
        if(isset($field['table'])) {
            $property = $field['field'];

            // If the field is an array,
            // make it an array in the Migration
            if (is_array($property)) {
                $property = "['" . implode("', '", $property) . "']";
            } else {
                $property = $property ? "'$property'" : null;
            }

            $type = $field['type'];

            $property = str_replace("'", "", $property);

            $rename = false;

            if (str_contains(strtolower($property), strtolower($field['table'])) && (strtolower($property) != strtolower($field['table']))) {
                $property = str_replace(strtolower($field['table'] . "_"), "", $property);
                $rename = true;
            }

            if (str_contains(strtolower($type), "integer")) {
                $type = 'integer';
            }

            switch ($type) {
                case 'decimal':
                case 'float':
                case 'double':
                case 'integer':
                    $type = 'Int';
                    break;
                case 'dateTime':
                case 'string':
                case 'timestamp':
                case 'char':
                    $type = 'String';
                    break;
                case 'boolean':
                    $type = 'Boolean';
                    break;
                case 'json':
                    $type = 'JSON';
                    break;
                case 'geometry':
                case 'set':
                case 'enum':
                default:
                    $type = 'UNKNOWN';
                    break;
            }

            if (strtolower($property) === 'id') {
                $type = "ID!";
            }

            if ($rename === true) {
                $output = sprintf(
                    "%s: %s @rename(attribute: \"%s_%s\")",
                    $property,
                    $type,
                    strtolower($field['table']),
                    $property
                );
            } else {
                $output = sprintf(
                    "%s: %s",
                    $property,
                    $type
                );
            }

            return $output;
        }
        else {
            return "";
        }
    }
}
