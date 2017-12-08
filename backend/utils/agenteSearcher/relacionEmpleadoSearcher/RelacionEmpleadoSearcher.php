<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:45
 */

namespace backend\utils\agenteSearcher\relacionEmpleadoSearcher;

use backend\utils\agenteSearcher\IAgenteSearcher;
use backend\utils\agenteSearcher\IAgenteSearchable;
use backend\models\empleado\Empleado;
class RelacionEmpleadoSearcher implements IAgenteSearcher
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
        $tipoRelacion = $this->parametroAgente;
        $empleadoSolicitante = $this->agenteSearchableObject->getEmpleadoPrincipal();
        $codigoEmpleadoSolicitante = $empleadoSolicitante->getAttribute('codigo_empleado') ;
        $compania = $empleadoSolicitante->getAttribute('compania') ;
        $extraFields = array_key_exists('relations',$config)?$config['relations']:[];
        $empleados = Empleado::find()
                        ->with($extraFields)
                        ->joinWith('relacionesEmpleadosInvolucradas',false,'RIGHT JOIN')
                        ->where ([  '{{relaciones_empleado}}.compania'=>$compania,
                                    '{{relaciones_empleado}}.tipo_relacion'=>$tipoRelacion,
                                    '{{relaciones_empleado}}.codigo_empleado'=>$codigoEmpleadoSolicitante,
                                    '{{empleado}}.compania'=>$compania])->all();
        return $empleados;
    }


}