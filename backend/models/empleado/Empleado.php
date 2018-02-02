<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 12:27
 */

namespace backend\models\empleado;


use backend\models\calendario\diaFeriado\AbstractDiaFeriado;
use backend\models\calendario\diaFeriado\diasFeriadosSelector\DiaFeriadoSelectorManager;
use backend\models\security\securityUser\SecurityUser;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use backend\models\empleado\horarioEmpleado\HorarioEmpleado;
use backend\models\empleado\relacionEmpleado\RelacionEmpleado;
use backend\models\organigrama\Organigrama;
use backend\models\puesto\Puesto;
use backend\models\empleado\vacacionesEmpleado\VacacionesEmpleado;
use backend\models\movimientosVacaciones\MovimientoVacaciones;
use backend\models\empleado\vacacionesEmpleado\ControlAjusteVacacionAcumulado;
use backend\models\calendario\diaFeriado\IDiaFeriado;

class Empleado extends  ActiveRecord
{




    public static function tableName()
    {
        return "EMPLEADO";
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
                    "codigo_jefe","nomina_primaria"
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
            ->alias('HE')
            ->where([">=","fecha_final",$fechaActual])
            ->andWhere(['NOT EXISTS',HorarioEmpleado::find()
                                    ->alias('HE_TEMP')
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
     * @return ActiveQuery
     */

    public function getPuesto()
    {
        return $this->hasOne(Puesto::className(),["compania"=>"compania","codigo_puesto"=>"codigo_puesto"]);
    }

    /**
     * @return AbstractDiaFeriado[]
     */

    public function getDiasFeriados()
    {
        $diaFeriadoSelector = new DiaFeriadoSelectorManager();
        return $diaFeriadoSelector->getDiasFeriado($this);
    }


    public function getNominaPrimaria()
    {
        if(empty($this->nomina_primaria))
        {
            $this->loadAllAttributes();
        }
        return $this->nomina_primaria;
    }

    private function loadAllAttributes()
    {
        $this->setAttributes(Empleado::find()->where($this->getPrimaryKey(true))->one()->getAttributes());
    }

    public function extraFields()
    {
        return [
            'vacacionesEmpleado',
            'horarioActual',
            'organigrama',
            'diasFeriados',
            'puesto',
            'solicitudesPendientes'

        ];
    }

    /**
     * @return ActiveQuery
     */

    public function getRelacionesEmpleadosInvolucradas()
    {
        return $this->hasMany(RelacionEmpleado::className(),["compania"=>"compania","codigo_empleado_relacion"=>"codigo_empleado"]);
    }

    /**
     * @return ActiveQuery
     */

    public function getRelacionesEmpleados()
    {
        return $this->hasMany(RelacionEmpleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);
    }

    /**
     * @return ActiveQuery
     */
    public function getSolicitudesPendientes()
    {
        return $this->hasMany(MovimientoVacaciones::className(),['compania'=>'compania','codigo_empleado'=>'codigo_empleado'])
                ->where(['tipo_mov'=>'DVA','estado'=>['T','P']]);
    }






    public function fields()
    {
        $fields =  parent::fields();
        return $fields;

    }

    public function getNombreCompleto()
    {
        return $this->nombre." ".$this->primer_apellido." ".$this->segundo_apellido;
    }


    /**
     * @return Empleado
     */
    public static function findEmpleadosWithoutUser()
    {
       return static :: find()->leftJoin(SecurityUser::tableName(),"login = username")
                    ->where("login is null")->
                    andWhere("username is not null")->all();
    }




}