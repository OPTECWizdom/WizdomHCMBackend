<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 05/12/2017
 * Time: 15:08
 */

namespace backend\models\calendario\diaFeriado;


interface IDiaFeriado
{
    public function getDiaFeriado();

    public function getMesFeriado();

}