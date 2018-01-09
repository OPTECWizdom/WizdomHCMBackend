<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 15:05
 */

namespace backend\models\calendario\diaFeriado\diasFeriadosSelector;


use backend\models\calendario\diaFeriado\AbstractDiaFeriado;
use backend\models\calendario\diaFeriado\IDiaFeriado;

abstract class AbstractDiaFeriadoSelectorManager
{
    /**
     * @var array $feriadosClass
     */

    protected $feriadosClass = [];

    public function __construct()
    {
        $this->setFeriadosClasses();
    }

    /**
     * @param $empleado
     * @return AbstractDiaFeriado[]
     */

    public function getDiasFeriado($empleado)
    {
        foreach($this->feriadosClass as $feriadosSelectorClass)
        {
            /**
             * @var IDiaFeriadoSelector $feriadoSelector
             */
            $feriadoSelector = new $feriadosSelectorClass();
            $feriados = $feriadoSelector->getDiasFeriados($empleado);
            if(!empty($feriados))
            {
                return $feriados;
            }
        }
        return [];

    }


    public abstract function setFeriadosClasses();
}