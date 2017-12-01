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
     * @param array $config
     * Parámetro con configuraciones extra para buscar.
     *  En la llave $config['relations'] se puede indicar las relaciones extras que se deseen buscar.
     *
     * @return Empleado[]|null
     */
    public function search($config = []);

}