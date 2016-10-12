<?php

class EntityFieldGenerator
{
    public static function generateFromModel(ORM $model)
    {
        $fields = '';
        foreach ($model->getTableColumns() as $name => $data) {
            $fields .= "\t/**\r\n\t * @var " . self::getFieldType($data['type']) . "\r\n\t */";
            $fields .= "\r\n\tprotected \$_" . $name . ";\r\n\r\n";
        }

        file_put_contents('fields.txt', $fields);
    }

    protected static function getFieldType($type)
    {
        if (in_array($type, ['int', 'decimal'])) {
            return 'int';
        }

        if ($type == 'datetime') {
            return 'string';
        }

        return $type;
    }
}