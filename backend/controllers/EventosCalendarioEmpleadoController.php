<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 10:19
 */

namespace backend\controllers;


use yii\rest\ActiveController;

class EventosCalendarioEmpleadoController extends ActiveController
{
    public $modelClass = 'backend\models\eventoCalendario\EventoCalendarioEmpleado';

}