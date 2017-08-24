<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 10:45
 */

namespace backend\workflowManagers;


use yii\db\Exception;

class WorkflowManagerFactory implements IWorkflowManagerFactory
{


    public  function createWorkflowManager(string $workflowManager, array $config = []) : AbstractWorkflowManager
    {
        try{
            $workflowManagerClass = "backend\workflowManagers\\$workflowManager";
            $workflowManager = new $workflowManagerClass($config);
            return $workflowManager;
        }catch(Exception $e){
            throw $e;
        }

    }


}