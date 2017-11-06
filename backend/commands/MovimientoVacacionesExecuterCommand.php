<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/11/2017
 * Time: 15:08
 */

namespace backend\commands;

use backend\workflowManagers\MovimientoVacacionesEjecutorManager;
use trntv\bus\interfaces\BackgroundCommand;
use trntv\bus\interfaces\SelfHandlingCommand;
use trntv\bus\middlewares\BackgroundCommandTrait;
use yii\base\Object;
use yii;

class MovimientoVacacionesExecuterCommand extends Object implements  BackgroundCommand,SelfHandlingCommand,IBackendBackgroundProcess
{
    use BackgroundCommandTrait;

    public  function runJob()
    {
        Yii::$app->commandBus->handle(new MovimientoVacacionesExecuterCommand(['async'=>true]));
    }


    public function handle($command) {
        $webServiceExecuter = new MovimientoVacacionesEjecutorManager();
        $webServiceExecuter->run();
    }





}