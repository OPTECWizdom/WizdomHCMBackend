<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 10/11/2017
 * Time: 11:37
 */

namespace backend\controllers;


use yii\rest\ActiveController;

class RelacionesEmpleadosController extends  ActiveController
{
    public $modelClass = 'backend\models\RelacionEmpleado';

}