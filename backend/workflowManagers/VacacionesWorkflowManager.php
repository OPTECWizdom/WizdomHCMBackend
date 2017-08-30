<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 17:24
 */

namespace backend\workflowManagers;

use app\models\FlujoProceso;
use app\models\MovimientoVacaciones;
use app\models\Proceso;
use app\models\VacacionesFlujoProcesoHelper;
use Yii;


class VacacionesWorkflowManager extends AbstractWorkflowManager
{
    private $movimientoVacaciones;

    private $flujoProceso;

    private $proceso;

    private $scenario;

    private $params;

    public function __construct(array $config = [])
    {
        if (array_key_exists('scenario',$config))
        {
           $this->scenario = $config['scenario'];

        }
        if (array_key_exists('params',$config)) {
            $this->params = $config['params'];
            if (array_key_exists('modelMovimientoVacaciones',$this->params)) {
                $this->movimientoVacaciones = $this->params['modelMovimientoVacaciones'];
            }
        }

    }


    public function insert()
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try
        {
            $this->insertMovimientoVacaciones();
            $this->insertProceso();
            $this->insertFlujoProceso();
            $transaction->commit();
            return true;

        }
        catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
        return false;



    }
    public function update(){
        $transaction = Yii::$app->getDb()->beginTransaction();
        try
        {
            $this->updateEstadoMovimientoVacacionesFromEstadoFlujoProceso();
            $this->movimientoVacaciones->save();
            $this->getFlujoProcesoFromParams();
            $this->updateFlujoProcesoStatus();
            $this->flujoProceso->save();
            $transaction->commit();
            return true;

        }
        catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
        return false;



    }

    public function delete(){

    }

    public function run(){
        $actions = $this->getActions();
        $action = $actions[$this->scenario];
        return call_user_func([$this,$action]);

    }

    private function getActions(){
        return [
            parent::REGISTER =>"insert",
            parent::UPDATE=>"update",
            parent::DELETE=>"delete"
        ];
    }

    private function insertMovimientoVacaciones(){
        try {

            $movimientoVacaciones= new MovimientoVacaciones(['scenario' => $this->scenario]);
            $movimientoVacaciones->load($this->params, '');
            $movimientoVacaciones->enterWorkflow();
            if ($movimientoVacaciones->save()) {
                $this->movimientoVacaciones = $movimientoVacaciones;

            } else {
                throw new \Exception("");

            }
        }
        catch (\Exception $e){
            throw $e;
        }

    }

    private function insertProceso(){
        try
        {
            $movimientoVacaciones = $this->movimientoVacaciones;
            $proceso = new Proceso(['scenario' => $this->scenario]);
            $proceso->compania = $this->movimientoVacaciones->compania;
            $proceso->tipo_flujo_proceso = "movimientos_vacaciones";
            $proceso->sistema_procedencia="RHU";
            $proceso->codigo_empleado = $movimientoVacaciones->codigo_empleado;
            if($proceso->save()){
                $this->proceso = $proceso;

            }
            else
            {
                throw new \Exception();
            }

        }
        catch (\Exception $e){
            throw e;
        }
    }


    private function insertFlujoProceso(){
        try{
            $flujoProcesoHelper = new VacacionesFlujoProcesoHelper($this->movimientoVacaciones,$this->proceso);
            $flujoProceso = $flujoProcesoHelper->getFlujoProceso();
            $flujoProceso->enterWorkflow();
            if($flujoProceso->save()){
                $this->flujoProceso = $flujoProceso;

            }
            else
            {
                throw new \Exception();
            }

        }
        catch (\Exception $e){
            throw $e;
        }



    }

    private function getFlujoProcesoFromParams(){
        $flujoProceso = FlujoProceso::find()->where($this->params["flujo_proceso"])->one();
        $this->flujoProceso = $flujoProceso;

    }

    private function updateEstadoMovimientoVacacionesFromEstadoFlujoProceso(){
        $movimientoVacacionesTranslations =     ["MovimientoVacacionesWorkflow/AP"=>"P",
                                                 "MovimientoVacacionesWorkflow/RE"=>"R"];
        if(array_key_exists($this->movimientoVacaciones->estado_flujo_proceso,$movimientoVacacionesTranslations)){
            $this->movimientoVacaciones->estado = $movimientoVacacionesTranslations[$this->movimientoVacaciones->estado_flujo_proceso];
        }
    }

    private function updateFlujoProcesoStatus(){
        $estadoFlujoProceso = $this->movimientoVacaciones->getAttribute("estado_flujo_proceso");
        $flujoProcesoTranslations =     ["MovimientoVacacionesWorkflow/AP"=>"AP",
                                        "MovimientoVacacionesWorkflow/RE"=>"RE"];
        $this->flujoProceso->sendToStatus($flujoProcesoTranslations[$estadoFlujoProceso]);
    }

    public function getMovimientoVacaciones(){
        return $this->movimientoVacaciones;
    }








}