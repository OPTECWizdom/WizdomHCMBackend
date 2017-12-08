<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 12:07
 */

namespace backend\utils\agenteSearcher\empleadoSearcher;

use backend\models\empleado\Empleado;
use backend\utils\agenteSearcher\IAgenteSearcher;
use backend\utils\agenteSearcher\IAgenteSearchable;
class EmpleadoSolicitanteSearcher implements IAgenteSearcher
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
        return [$empleado];

    }



}