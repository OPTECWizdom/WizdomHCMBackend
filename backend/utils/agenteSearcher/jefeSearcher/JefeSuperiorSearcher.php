<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 11/09/2017
 * Time: 11:24
 */

namespace backend\utils\agenteSearcher\jefeSearcher;
use backend\utils\agenteSearcher\IAgenteSearcher;
use backend\utils\agenteSearcher\IAgenteSearchable;
use backend\models\empleado\Empleado;

class JefeSuperiorSearcher implements IAgenteSearcher
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
        $jefeInmediatoSearcher = new JefeInmediatoSearcher($this->agenteSearchableObject,$this->parametroAgente);
        $jefeInmediato = $jefeInmediatoSearcher->search();
        if(!empty($jefeInmediato))
        {
            /**
             * @var Empleado $empleado
             */
            $empleado = $jefeInmediato[0];
            $compania = $empleado->getAttribute("compania");
            $codigoEmpleadoJefe = $empleado->getAttribute("codigo_jefe");
            $empleadoJefeSuperiorPks = ["compania"=>$compania,
                "codigo_empleado"=>$codigoEmpleadoJefe

            ];
            $empleadoJefeSuperior = Empleado::find()->where($empleadoJefeSuperiorPks)->one();
            return [$empleadoJefeSuperior];

        }
        return [];

    }

}