<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 12:27
 */

namespace backend\models;


use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

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
     * @return \yii\db\ActiveQuery
     *
     */
    public function getHorarioActual()
    {
        $fechaActual = date('Y-m-d');
        return $this->getHorariosEmpleado()
            ->alias('he')
            ->where([">=","fecha_final",$fechaActual])
            ->andWhere(['NOT EXISTS',HorarioEmpleado::find()
                                    ->alias('he_temp')
                                    ->where("he_temp.compania = he.compania and 
                                                        he_temp.codigo_empleado = he.codigo_empleado and 
                                                        he_temp.consecutivo != he.consecutivo and
                                                        he_temp.fecha_final >= '$fechaActual' and
                                                        he_temp.fecha_inicial > he.fecha_inicial")]);

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

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getOrganigrama()
    {
        return $this->hasOne(Organigrama::className(),['compania'=>'compania','codigo_nodo_organigrama'=>'codigo_nodo_organigrama']);
    }

    /**
     * @return ActiveRecord
     */

    public function getDiasFeriados()
    {
        /**
         * @var Organigrama $organigrama
         */
        $organigrama = $this->getOrganigrama()->one();
        if(!empty($organigrama))
        {
            $catalogoDiasFeriados = $organigrama->getAttribute('catalogo_dias_feriados');
            if(!empty($catalogoDiasFeriados))
            {
                return DiaFeriadoCatalogo::find()->where(['compania'=>$this->compania,'catalogo_dias_feriados'=>$catalogoDiasFeriados])->all();
            }
        }
        return DiaFeriado::find()->where(["compania"=>$this->getAttribute('compania')])->all();
    }


    public function extraFields()
    {
        return [
            'vacacionesEmpleado',
            'horarioActual',
            'organigrama',
            'diasFeriados'

        ];
    }






    public function fields()
    {
        $fields =  parent::fields();
        return $fields;

    }

}