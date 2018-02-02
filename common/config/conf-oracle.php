<?php


return [
    'class' => 'yii\db\Connection',
    'dsn' => 'oci:dbname=//ip:puerto/nombre_servicio;charset=UTF8',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    // Duration of schema cache.
    'schemaCacheDuration' => 20000,
    'schemaMap'=>['oci'=>'common\components\ESchemaOci'],// Si es base de datos Oracle habilitar este atributo
    'attributes' => [
        PDO::ATTR_CASE => PDO::CASE_LOWER,
    ],
    // Si es base de datos Oracle activar este comando, cambia la fecha de la sesion
    'on afterOpen'=>function ($event)
    {
        $event->sender->createCommand("ALTER SESSION SET NLS_DATE_FORMAT ='yyyy-mm-dd hh24:mi:ss'")->execute();
        $event->sender->createCommand("alter session set nls_numeric_characters = '.,'")->execute();
        $event->sender->createCommand("alter session set NLS_TIMESTAMP_FORMAT = 'yyyy-mm-dd hh24:mi:ss'")->execute();
        
    }
    /**
     * Si es base de datos Oracle, descomentar las lineas que corresponden con SchemaMap,Attributes y on afterOpen
     */

];