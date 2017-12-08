<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 11/09/2017
 * Time: 11:24
 */

namespace backend\utils\agenteSearcher\empleadoSearcher;

use backend\utils\agenteSearcher\IAgenteSearcher;
use backend\utils\agenteSearcher\IAgenteSearchable;
use backend\models\empleado\Empleado;

class EmpleadoSearcher implements IAgenteSearcher
{
    /**
     * @var IAgenteSearchable  $agenteSearchableObject
     */
    private $agenteSearchableObject;
    private $parametroAgente;

    public function __construct(IAgenteSearchable $agenteSearchableObject,string $parametroAgente = null)
    {
        $this->agenteSearchableObject = $agenteSearchableObject;
        $this->parametroAgente = $parametroAgente;
    }

    public function search($config = [])
    {
        $arrayParmsAgente = explode("-",$this->parametroAgente);
        if(count($arrayParmsAgente)==2)
        {
            $compania = $arrayParmsAgente[0];
            $codigo_empleado = $arrayParmsAgente[1];
            $searchConditions = ["compania"=>$compania,"codigo_empleado"=>$codigo_empleado];
            $empleado = Empleado::find()->where($searchConditions)->one();
            return [$empleado];


        }
        return [] ;




    }

}