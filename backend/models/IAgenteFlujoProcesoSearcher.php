<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 12:08
 */

namespace backend\models;


interface IAgenteFlujoProcesoSearcher
{

    /**
     * @return Empleado[]|null
     */
    public function search();

}