<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 04/10/2017
 * Time: 14:50
 */

namespace backend\controllers;

use yii\rest\ActiveController;
class EmpleadosController extends  ActiveController
{
    public $modelClass = 'backend\models\empleado\Empleado';



}