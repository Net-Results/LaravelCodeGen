<?php

namespace Xethron\MigrationsGenerator\GraphQLSyntax;

class QueryTable extends Table
{
    /**
     * @inheritDoc
     */
    protected function getItem(array $field): string
    {
        if (isset($field['table']) && (strtolower($field['field']) === "id" || strtolower($field['field']) === strtolower($field['table']."_id"))) {
            $property = $field['field'];
            $backslash = "\\";

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
                "%s(id: ID @eq(key: \"%s_id\")): %s @find(model: \"App\\\\\\\\Models\\\\\\\\%s\", scopes: [\"AuthProduct\"])
  %ss(
    orderBy: [OrderByClause!] @orderBy(columnsEnum: \"%sOrderByColumns\")
    where: _ @whereConditions(columnsEnum: \"%sSearchByColumns\")
  ): [%s!]! @paginate(type: \"paginator\", model: \"App\\\\\\\\Models\\\\\\\\%s\", scopes: [\"AuthProduct\"])",
            strtolower($table),
            strtolower($table),
            $table,
            $table,
            strtolower($table),
            $table,
            $table,
            $table,
            $table,
            );
            return $output;
        }
        else {
            return "";
        }
    }
}
