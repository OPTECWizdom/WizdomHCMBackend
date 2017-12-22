<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 10:19
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomActiveController;

class EventosCalendarioEmpleadoController extends AbstractWizdomActiveController
{
    public $modelClass = 'backend\models\eventoCalendario\EventoCalendarioEmpleado';

}