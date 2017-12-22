<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 04/10/2017
 * Time: 14:50
 */

namespace backend\controllers;

use backend\rest\controllers\WizdomActiveController;
class EmpleadosController extends  WizdomActiveController
{
    public $modelClass = 'backend\models\empleado\Empleado';



}