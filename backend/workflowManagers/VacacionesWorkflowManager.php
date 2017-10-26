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
use app\models\ProcesoMovimientoVacacion;
use app\models\VacacionesFlujoProcesoHelper;
use Yii;


class VacacionesWorkflowManager extends AbstractWorkflowManager
{
    /**
     * @var MovimientoVacaciones $movimientoVacaciones
     */
    private $movimientoVacaciones;
    /**
     * @var FlujoProceso $flujoProceso
     */
    private $flujoProceso;
    /**
     * @var Proceso $proceso
     */
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
            $this->insertProcesoMovimientoVacaciones();
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
            $this->setEstadoVacacionesWorkflow();
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


    public function setEstadoVacacionesWorkflow()
    {
        $oldEstado = $this->movimientoVacaciones->getOldAttribute("estado_flujo_proceso");
        $estado = $this->movimientoVacaciones->getAttribute("estado_flujo_proceso");
        if($estado=="MovimientoVacacionesWorkflow/AP")
        {
            $transitions = [
                                "MovimientoVacacionesWorkflow/RV"=>"AR",
                                "MovimientoVacacionesWorkflow/AR"=>"AP"
                            ];
            if(array_key_exists($oldEstado,$transitions))
            {
                $this->movimientoVacaciones->sendToStatus($transitions[$oldEstado]);
            }
        }

    }

    public function delete(){
        $transaction = Yii::$app->getDb()->beginTransaction();
        try
        {
            $this->deleteProceso();
            $this->movimientoVacaciones->delete();
            return true;

        }
        catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
        return false;

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
        $compania = $this->params["flujo_proceso"]["compania"];
        $tipoFlujoProceso = $this->params["flujo_proceso"]["tipo_flujo_proceso"];
        $codigoTarea = $this->params["flujo_proceso"]["codigo_tarea"];
        $idProceso = $this->params["flujo_proceso"]["id_proceso"];
        $whereParams = ["compania"=>$compania,"id_proceso"=>$idProceso,"tipo_flujo_proceso"=>$tipoFlujoProceso,"codigo_tarea"=>$codigoTarea];
        $flujoProceso = FlujoProceso::find()->where($whereParams)->one();
        $flujoProceso->setAttributes($this->params["flujo_proceso"]);
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
        $flujoProcesoTranslations =     [
                                        "MovimientoVacacionesWorkflow/AR"=>"AP",
                                        "MovimientoVacacionesWorkflow/AP"=>"AP",
                                        "MovimientoVacacionesWorkflow/RE"=>"RE"];
        if (array_key_exists($estadoFlujoProceso,$flujoProcesoTranslations)){
            $this->flujoProceso->sendToStatus($flujoProcesoTranslations[$estadoFlujoProceso]);

        }
    }

    public function getMovimientoVacaciones(){
        return $this->movimientoVacaciones;
    }

    public function insertProcesoMovimientoVacaciones()
    {
        $procesoMovVacaciones = new ProcesoMovimientoVacacion();
        $procesoPk = $this->proceso->getAttributes(Proceso::primaryKey());
        $movVacacionesPk = $this->movimientoVacaciones->getAttributes(MovimientoVacaciones::primaryKey());
        $procesoMovVacaciones->setAttributes(array_merge($procesoPk,$movVacacionesPk));
        $procesoMovVacaciones->save();

    }


    public function deleteProceso()
    {
        $procesoMovVacaciones = $this->movimientoVacaciones->getProcesoMovimientoVacaciones()->one();
        if(!empty($procesoMovVacaciones))
        {
            $proceso = new Proceso();
            $proceso->setAttributes($procesoMovVacaciones->getAttributes(Proceso::primaryKey()));
            $proceso->delete();
        }

    }










}