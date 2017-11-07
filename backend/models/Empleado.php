<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 12:27
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Empleado extends  ActiveRecord
{

    public static function tableName()
    {
        return "empleado";
    }

    public static function primaryKey()
    {
        return [
            "compania","codigo_empleado"
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    "compania","codigo_empleado"
                ],'required'
            ],
            [
                [
                    "nombre","primer_apellido","segundo_apellido",
                    "codigo_nodo_organigrama","codigo_puesto",
                    "codigo_jefe"
                ],"string"
            ]


        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */


    public function getHorariosEmpleado()
    {
        return $this->hasMany(HorarioEmpleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);
    }

    /**
     * @return array|null|HorarioEmpleado
     */
    public function getHorarioActual()
    {
        return $this->getHorariosEmpleado()->where([">=","fecha_final",date('Y-m-d')])->orderBy('fecha_inicial desc')->one();

    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getControlAjusteVacacionAcumulado()
    {
        return $this->hasOne(ControlAjusteVacacionAcumulado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getVacacionesEmpleado()
    {
        return $this->hasMany(VacacionesEmpleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);
    }


    public function getMovimientosVacaciones()
    {
        return $this->hasMany(MovimientoVacaciones::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);
    }


}