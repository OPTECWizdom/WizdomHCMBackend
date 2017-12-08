<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 11:33
 */

namespace backend\controllers;


use yii\rest\ActiveController;

class HorariosController extends ActiveController
{
    public $modelClass = '\backend\models\horario\Horario';

}