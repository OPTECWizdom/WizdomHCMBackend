<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 11/09/2017
 * Time: 11:24
 */

namespace backend\utils\agenteSearcher\puestoSearcher;

use backend\utils\agenteSearcher\IAgenteSearcher;
use backend\utils\agenteSearcher\IAgenteSearchable;
use backend\models\empleado\Empleado;
class PuestoSearcher implements IAgenteSearcher
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
        if(count($arrayParmsAgente)==3)
        {
            $compania = $arrayParmsAgente[0];
            $organigrama = $arrayParmsAgente[1];
            $puesto = $arrayParmsAgente[2];
            $searchConditions = ["compania"=>$compania,"codigo_nodo_organigrama"=>$organigrama,"codigo_puesto"=>$puesto];
            $empleados = Empleado::find()->where($searchConditions)->all();
            return $empleados;


        }
        return [] ;




    }

}