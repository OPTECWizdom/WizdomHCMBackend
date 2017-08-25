<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 24/08/2017
 * Time: 16:02
 */

namespace app\models;


interface IFlujoProcesoHelper
{

    const FISRT = 0;
    const NEXT = 1;
    public function getFlujoProceso();

}