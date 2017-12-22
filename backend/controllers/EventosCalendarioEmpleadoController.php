<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 10:19
 */

namespace backend\controllers;


use backend\rest\controllers\WizdomActiveController;

class EventosCalendarioEmpleadoController extends WizdomActiveController
{
    public $modelClass = 'backend\models\eventoCalendario\EventoCalendarioEmpleado';

}