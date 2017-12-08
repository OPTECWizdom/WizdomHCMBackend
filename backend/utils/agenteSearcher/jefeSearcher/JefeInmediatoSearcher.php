<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 23:40
 */

namespace backend\utils\agenteSearcher\jefeSearcher;


use backend\utils\agenteSearcher\IAgenteSearcher;
use backend\utils\agenteSearcher\IAgenteSearchable;
use backend\models\empleado\Empleado;
class JefeInmediatoSearcher implements IAgenteSearcher
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
        $empleadoPks = $this->agenteSearchableObject->getEmpleadoPrincipal()->getAttributes(Empleado::primaryKey());
        $empleado = Empleado::find()->where($empleadoPks)->one();
        if(!empty($empleado)){
            $compania = $empleado->getAttribute("compania");
            $codigoEmpleadoJefe = $empleado->getAttribute("codigo_jefe");
            $empleadoJefePks = ["compania"=>$compania,
                                "codigo_empleado"=>$codigoEmpleadoJefe

            ];

            $empleadoJefe = Empleado::find()->where($empleadoJefePks)->one();
            return [$empleadoJefe];
        }
        return [];

    }

}