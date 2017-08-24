<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 10:47
 */

namespace backend\workflowManagers;


interface IWorkflowManagerFactory
{

    public function createWorkflowManager(string $workflowManager,array $config = []):AbstractWorkflowManager;

}