<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:45
 */

namespace backend\utils\agenteSearcher\grupoSearcher;

use backend\utils\agenteSearcher\IAgenteSearcher;
use backend\utils\agenteSearcher\IAgenteSearchable;
use backend\models\empleado\Empleado;
use backend\models\security\securityUser\SecurityUserGroup;
class GrupoSearcher implements IAgenteSearcher
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
        $empleados = array();
        $grupo = $this->parametroAgente;
        $secUserGroup = SecurityUserGroup::find()->where(["group_id"=>$grupo])->all();
        foreach ($secUserGroup as $user)
        {
          $empleado = Empleado::find()->where(["username"=>$user->getAttribute("login")])->one();
          $empleados[] = $empleado;
        }
        return $empleados;



    }

}