<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 18/10/2017
 * Time: 11:02
 */

namespace backend\controllers;


use backend\rest\controllers\WizdomActiveController;

class PuestosController extends WizdomActiveController
{
    public $modelClass = 'backend\models\puesto\Puesto';


}