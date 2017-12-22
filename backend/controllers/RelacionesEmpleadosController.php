<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 10/11/2017
 * Time: 11:37
 */

namespace backend\controllers;


use backend\rest\controllers\WizdomActiveController;

class RelacionesEmpleadosController extends  WizdomActiveController
{
    public $modelClass = 'backend\models\empleado\relacionEmpleado\RelacionEmpleado';

}