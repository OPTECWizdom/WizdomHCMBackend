<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 11:33
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomActiveController;

class HorariosController extends AbstractWizdomActiveController
{
    public $modelClass = '\backend\models\horario\Horario';

}