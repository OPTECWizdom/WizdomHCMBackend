<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 05/12/2017
 * Time: 10:49
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomActiveController;

class VacacionesEmpleadoMovimientoController extends AbstractWizdomActiveController
{
    public $modelClass = 'backend\models\movimientosVacaciones\vacacionesEmpleadoMovimiento\VacacionEmpleadoMovimiento';

}