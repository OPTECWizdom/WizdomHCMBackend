<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 05/12/2017
 * Time: 10:49
 */

namespace backend\controllers;


use yii\rest\ActiveController;

class VacacionesEmpleadoMovimientoController extends ActiveController
{
    public $modelClass = 'backend\models\movimientosVacaciones\vacacionesEmpleadoMovimiento\VacacionEmpleadoMovimiento';

}