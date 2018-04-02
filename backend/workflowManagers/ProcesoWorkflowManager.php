<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 17:24
 */

namespace backend\workflowManagers;
use backend\models\factories\WizdomModelFactory;
use backend\models\FlujoProcesoHelper;
use backend\models\proceso\Proceso;
use backend\models\proceso\flujoProceso\FlujoProceso;
use backend\models\procesoModelConnector\FactoryProcesoSubjectConnector;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;


class ProcesoWorkflowManager extends AbstractWorkflowManager
{
    /**
     * @var ActiveRecord $procesoObjeto
     */
    private $procesoObjeto;
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
            if (array_key_exists('model',$this->params)) {
                $this->procesoObjeto = $this->params['model'];
            }
        }

    }


    public function insert()
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try
        {
            $this->insertProcesoObjeto();
            $this->insertProceso();
            $this->insertProcesoRelacion();
            $this->insertFlujoProceso();
            $transaction->commit();
            return true;

        }
        catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }



    }
    public function update(){
        $transaction = Yii::$app->getDb()->beginTransaction();
        try
        {
            $this->getFlujoProcesoFromParams();
            $this->updateFlujoProcesoStatus();
            $this->updateProcesoObjeto();
            $this->flujoProceso->save();
            $transaction->commit();
            return true;

        }
        catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }



    }





    public function delete(){
        $transaction = Yii::$app->getDb()->beginTransaction();
        try
        {
            $this->deleteProceso();
            $this->deleteProcesoObjeto();
            $transaction->commit();
            return true;

        }
        catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }

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

    private function insertProcesoObjeto(){
        try {

            $procesoObjeto = WizdomModelFactory::getWizdomModel($this->params['proceso']['tipo_flujo_proceso']);
            $procesoObjeto->setScenario($procesoObjeto::SCENARIO_INSERT);
            $procesoObjeto->load($this->params['model'], '');
            if ($procesoObjeto->save()) {
                $this->procesoObjeto = $procesoObjeto;

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
            $procesoObjeto = $this->procesoObjeto;
            $proceso = new Proceso(['scenario' => $this->scenario]);
            $proceso->load($this->params['proceso'],'');
            $proceso->codigo_empleado = $procesoObjeto->getAttribute('codigo_empleado');
            if($proceso->save()){
                $this->proceso = $proceso;
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


    private function insertFlujoProceso(){
        try{
            $flujoProcesoHelper = new FlujoProcesoHelper($this->procesoObjeto,$this->proceso);
            $flujoProceso = $flujoProcesoHelper->getFlujoProceso();
            $flujoProceso->enterWorkflow();
            if($flujoProceso->save()){
                $this->flujoProceso = $flujoProceso;

            }
            else
            {
                throw new \Exception(json_encode($flujoProceso->getErrors()));
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


    private function updateFlujoProcesoStatus(){
        $this->flujoProceso->sendToStatus($this->params["flujo_proceso"]["estado"]);
    }

    private function updateProcesoObjeto()
    {
        $estadosProcesobjeto = $this->getFlujoTipoProcesoEstado();
        foreach ($estadosProcesobjeto as $nuevoEstado)
        {
           $this->procesoObjeto->setAttributes([$nuevoEstado->getAttribute('columna_proceso_objeto')=>
                                              $nuevoEstado->getAttribute('valor_columna_proceso_objeto')]) ;
        }

    }

    /**
     * @return array|ActiveRecord[]
     */
    private function getFlujoTipoProcesoEstado()
    {
        $estado = $this->flujoProceso->getAttribute('estado');
        $tipoFlujoProceso =  $this->flujoProceso->getTipoFlujoProceso()
            ->with(['flujoTipoProcesoEstados' => function (\yii\db\ActiveQuery $query) use ($estado) {
                    $query->andWhere([
                        'estado_flujo_proceso'=>$estado

                    ]);
                   },
            ])->one();
        return $tipoFlujoProceso->relatedRecords['flujoTipoProcesoEstados'];
    }





    public function insertProcesoRelacion()
    {
        $factoryProcesoSubjectConnector = new FactoryProcesoSubjectConnector();
        $procesoRelacion = $factoryProcesoSubjectConnector->getConnector($this->params['proceso']['tipo_flujo_proceso']);
        $procesoRelacion->setAttributes(array_merge($this->proceso->primaryKey,$this->procesoObjeto->primaryKey));
        $procesoRelacion->save();
    }


    public function deleteProceso()
    {
        $nombreTabla = strtolower($this->procesoObjeto::tableName());
        $factoryProcesoSubjectConnector = new FactoryProcesoSubjectConnector();
        $procesoRelacion = $factoryProcesoSubjectConnector->getConnector($nombreTabla);
        $procesoRelacion = $procesoRelacion::find()->where($this->procesoObjeto->primaryKey)->one();
        if(!empty($procesoRelacion))
        {
            $proceso = Proceso::find()->where($procesoRelacion->getAttributes(Proceso::primaryKey()))->one();
            if(!empty($proceso))
                $proceso->delete();
        }

    }

    private function deleteProcesoObjeto()
    {
        $this->procesoObjeto->delete();

    }

}