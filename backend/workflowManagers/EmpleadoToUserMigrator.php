<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 02/02/2018
 * Time: 11:12
 */

namespace backend\workflowManagers;



use backend\models\empleado\Empleado;
use backend\models\security\securityUser\SecurityUser;

 class EmpleadoToUserMigrator extends AbstractWorkflowManager
{


    public function __construct($config=[]){

    }


    protected  function insert()
    {

    }

    protected  function update()
    {

    }

    protected  function delete()
    {

    }

    public  function run()
    {
        $empleados = Empleado::findEmpleadosWithoutUser();
        $empleadosInsertados=[];
        /**
         * @var Empleado $empleado
         */
        foreach ($empleados as $empleado)
        {
            $user = new SecurityUser();
            $user->setAttributesFromEmpleado($empleado);
            if (!in_array($user->getAttribute('login'),$empleadosInsertados))
            {
                $user->save();
                $empleadosInsertados[]=$user->getAttribute('login');
            }
        }
        return true;

    }







}