<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 12:53
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Organigrama extends ActiveRecord
{

    public static function tableName()
    {
        return 'ORGANIGRAMA';
    }

    public static function primaryKey()
    {
        return ['compania','codigo_nodo_organigrama'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getCatalogoDiasFeriados()
    {
        return $this->hasMany(CatalogoDiasFeriados::className(),["compania"=>"compania","codigo_catalogo"=>"catalogo_dias_feriados"]);
    }


    public function rules()
    {
        return [
            [
                [
                    'compania','codigo_nodo_organigrama'
                ],'required'

            ]

        ];
    }

}