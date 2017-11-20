<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 26/10/2017
 * Time: 17:14
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ProcesoMovimientoVacacion extends ActiveRecord
{

    public static function tableName()
    {
        return "PROCESO_MOVIMIENTOS_VACACIONES";
    }

    public static function primaryKey()
    {
        return ["compania","tipo_mov","consecutivo_movimiento"];
    }


    public function rules()
    {
        return [
            [
                ["compania","tipo_mov","consecutivo_movimiento"],"required"
            ],
            [
                ["compania","tipo_mov","tipo_flujo_proceso"],"string"
            ],
            [
                ["consecutivo_movimiento","id_proceso"],"integer"
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProceso()
    {
        return $this->hasOne(Proceso::className(),["compania"=>"compania","tipo_flujo_proceso"=>"tipo_flujo_proceso",
                                                    "id_proceso"=>"id_proceso"]);
    }

    /**
     *
     */
    public function getMovimientoVacaciones()
    {
        return $this->hasOne(MovimientoVacaciones::className(),["compania"=>"compania","tipo_mov"=>"tipo_mov",
                                                                "consecutivo_movimiento"=>'consecutivo_movimiento']);

    }


}