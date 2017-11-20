<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ESchemaOci
 *
 * @author Cristi.Axe
 */
namespace common\components;

use  yii\db\oci\Schema;

class ESchemaOci extends Schema {

    protected function findColumns($table)
    {
        //$schemaName = $table->schemaName;
        //$tableName = $table->name;
        $schemaName = strtoupper($table->schemaName);
        $tableName = strtoupper($table->name);
        $sql = <<<EOD
SELECT lower(a.column_name) as column_name, a.data_type, a.data_precision, a.data_scale, a.data_length,
    a.nullable, a.data_default,
    (   SELECT D.constraint_type
        FROM ALL_CONS_COLUMNS C
        inner join ALL_constraints D on D.OWNER = C.OWNER and D.constraint_name = C.constraint_name
        WHERE C.OWNER = B.OWNER
           and C.table_name = B.object_name
           and C.column_name = A.column_name
           and D.constraint_type = 'P') as Key,
    '' as column_comment
FROM ALL_TAB_COLUMNS A
inner join ALL_OBJECTS B ON b.owner = a.owner and ltrim(B.OBJECT_NAME) = ltrim(A.TABLE_NAME)
WHERE
    a.owner = '{$schemaName}'
    and (b.object_type = 'TABLE' or b.object_type = 'VIEW')
    and b.object_name = '{$tableName}'
ORDER by a.column_id
EOD;

        try {
            $columns = $this->db->createCommand($sql)->queryAll();
        } catch (\Exception $e) {
            return false;
        }

        foreach ($columns as $column) {
            $c = $this->createColumn($column);
            $table->columns[$c->name] = $c;
            if ($c->isPrimaryKey) {
                $table->primaryKey[] = $c->name;
                $table->sequenceName = $this->getTableSequenceName($table->name);
                $c->autoIncrement = true;
            }
        }
        return true;
    }

    protected function createColumn($column)
    {
        $c = $this->createColumnSchema();
        $c->name = $column['column_name'];
        $c->allowNull = $column['nullable'] === 'Y';
        $c->isPrimaryKey = strpos($column['key'], 'P') !== false;
        $c->comment = $column['column_comment'] === null ? '' : $column['column_comment'];

        $this->extractColumnType($c, $column['data_type'], $column['data_precision'], $column['data_scale'], $column['data_length']);
        $this->extractColumnSize($c, $column['data_type'], $column['data_precision'], $column['data_scale'], $column['data_length']);

        if (!$c->isPrimaryKey) {
            if (stripos($column['data_default'], 'timestamp') !== false) {
                $c->defaultValue = null;
            } else {
                $c->defaultValue = $c->phpTypecast($column['data_default']);
            }
        }

        return $c;
    }

    protected function findConstraints($table)
    {
        $sql = <<<EOD
        SELECT D.constraint_type as CONSTRAINT_TYPE, C.COLUMN_NAME, C.position, D.r_constraint_name,
                E.table_name as table_ref, f.column_name as column_ref,
                C.table_name
        FROM ALL_CONS_COLUMNS C
        inner join ALL_constraints D on D.OWNER = C.OWNER and D.constraint_name = C.constraint_name
        left join ALL_constraints E on E.OWNER = D.r_OWNER and E.constraint_name = D.r_constraint_name
        left join ALL_cons_columns F on F.OWNER = E.OWNER and F.constraint_name = E.constraint_name and F.position = c.position
        WHERE C.OWNER = '{$table->schemaName}'
           and C.table_name = '{$table->name}'
           and D.constraint_type <> 'P'
        order by d.constraint_name, c.position
EOD;
        $command = $this->db->createCommand($sql);
        foreach ($command->queryAll() as $row) {
            if ($row['constraint_type'] === 'R') {
                $name = $row["column_name"];
                $table->foreignKeys[$name] = [$row["table_ref"], $row["column_ref"]];
            }
        }
    }

    protected function findTableNames($schema = '')
    {
        if ($schema === '') {
            $sql = <<<EOD
SELECT table_name, '{$schema}' as table_schema FROM user_tables
EOD;
            $command = $this->db->createCommand($sql);
        } else {
            $sql = <<<EOD
SELECT object_name as table_name, owner as table_schema FROM all_objects
WHERE object_type = 'TABLE' AND owner=:schema
EOD;
            $command = $this->db->createCommand($sql);
            $command->bindParam(':schema', $schema);
        }

        $rows = $command->queryAll();
        $names = [];
        foreach ($rows as $row) {
            $names[] = $row['table_name'];
        }

        return $names;
    }

    public function quoteSimpleColumnName($name)
    {
        return $name;
    }
}