<?php

namespace Xethron\MigrationsGenerator\GraphQLSyntax;

class MutationTable extends Table
{
    /**
     * @inheritDoc
     */
    protected function getItem(array $field): string
    {
        if (isset($field['table']) && (strtolower($field['field']) === "id" || strtolower($field['field']) === strtolower($field['table']."_id"))) {
            $property = $field['field'];

            // If the field is an array,
            // make it an array in the Migration
            if (is_array($property)) {
                $property = "['" . implode("', '", $property) . "']";
            } else {
                $property = $property ? "'$property'" : null;
            }

            $type = $field['type'];
            $table = ucfirst(strtolower($field['table']));

            $property = str_replace("'", "", $property);

            $output = sprintf(
                "create%s(input: Create%sInput! @spread): %s @create
    update%s(input: Update%sInput! @spread): %s @update(scopes: [\"AuthProduct\"])
    delete%s(id: ID! @rename(attribute: \"%s_id\")): %s @delete(scopes: [\"AuthProduct\"])",
                $table,
                $table,
                $table,
                $table,
                $table,
                $table,
                $table,
                strtolower($table),
                $table
            );
            return $output;
        }
        else {
            return "";
        }
    }
}
