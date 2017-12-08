<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 11/09/2017
 * Time: 11:24
 */

namespace backend\utils\agenteSearcher\departamentoSearcher;


use backend\utils\agenteSearcher\IAgenteSearchable;
use backend\utils\agenteSearcher\IAgenteSearcher;
use backend\models\empleado\Empleado;

class DepartamentoSearcher implements IAgenteSearcher
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
            $organigrama = $arrayParmsAgente[1];
            $searchConditions = ["compania"=>$compania,"codigo_nodo_organigrama"=>$organigrama];
            $empleados = Empleado::find()->where($searchConditions)->all();
            return $empleados;


        }
        return [] ;




    }

}