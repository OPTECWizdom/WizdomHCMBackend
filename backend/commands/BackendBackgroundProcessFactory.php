<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/11/2017
 * Time: 15:38
 */

namespace backend\commands;


class BackendBackgroundProcessFactory
{

    private $backgroundProcessClasses =
        [
            "MovimientoVacacionesWebService"=>'backend\commands\MovimientoVacacionesExecuterCommand'
        ];

    /**
     * @param string $processName
     * @return IBackendBackgroundProcess
     */

    public function getBackgroundProcess(string $processName)
    {
        if(array_key_exists($processName,$this->backgroundProcessClasses))
        {
            return new $this->backgroundProcessClasses[$processName]();
        }
        return null;

    }


}