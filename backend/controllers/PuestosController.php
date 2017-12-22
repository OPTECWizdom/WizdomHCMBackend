<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 18/10/2017
 * Time: 11:02
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomActiveController;

class PuestosController extends AbstractWizdomActiveController
{
    public $modelClass = 'backend\models\puesto\Puesto';


}