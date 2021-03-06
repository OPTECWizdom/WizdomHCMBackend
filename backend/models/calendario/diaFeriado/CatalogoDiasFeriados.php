<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 14:10
 */

namespace backend\models\calendario\diaFeriado;


use yii\db\ActiveRecord;

class CatalogoDiasFeriados extends ActiveRecord
{
    public static function tableName()
    {
        return "CATALOGO_DIAS_FERIADOS";
    }

    public static function primaryKey()
    {
        return ["compania","codigo_catalogo"];
    }

    public function getDiasFeriadosPorCatalogo()
    {
        return $this->hasMany(DiaFeriadoCatalogo::className(),["compania"=>"compania","catalogo_dias_feriados"=>"codigo_catalogo"]);
    }


    public function fields()
    {
        $fields = parent::fields();
        $fields['diasFeriadosPorCatalogo'] = 'diasFeriadosPorCatalogo';
        return $fields;
    }

}