<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/11/2017
 * Time: 15:08
 */

namespace backend\commands;

use backend\models\MovimientoVacaciones;
use backend\workflowManagers\MovimientoVacacionesEjecutorManager;
use trntv\bus\interfaces\BackgroundCommand;
use trntv\bus\interfaces\SelfHandlingCommand;
use trntv\bus\middlewares\BackgroundCommandTrait;
use yii\base\Object;
use yii;

class MovimientoVacacionesExecuterCommand extends Object implements  BackgroundCommand,SelfHandlingCommand,IBackendBackgroundProcess
{
    use BackgroundCommandTrait;

    /**
     * @var \backend\models\MovimientoVacaciones
     */
    public $movimientoVacacion;

    public  function runJob($config = [])
    {

        $movimientoVacacionsPks = $this->getMovimientoVacacionesPK($config);
         Yii::$app->commandBus->handle(new MovimientoVacacionesExecuterCommand(['async'=>true,'movimientoVacacion' =>$movimientoVacacionsPks]));
    }


    public function handle($command) {


        $webServiceExecuter = new MovimientoVacacionesEjecutorManager($this->movimientoVacacion);
        $webServiceExecuter->run();
        //echo "fin";

    }


    private function  getMovimientoVacacionesPK($config)
    {
        try
        {
           $movimientoVacaciones = $config[0];
           return $movimientoVacaciones->getAttributes(MovimientoVacaciones::primaryKey());
        }
        catch(\Exception $e)
        {
            return [];
        }

    }





}