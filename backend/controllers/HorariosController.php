<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 11:33
 */

namespace backend\controllers;


use backend\rest\controllers\WizdomActiveController;

class HorariosController extends WizdomActiveController
{
    public $modelClass = '\backend\models\horario\Horario';

}