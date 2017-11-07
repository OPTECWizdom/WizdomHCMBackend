<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 19:22
 */

namespace backend\workflowManagers;


use backend\models\FlujoProceso;
use backend\models\FlujoProcesoAgenteHelper;

class FlujoProcesoAgenteManager extends AbstractWorkflowManager

{


    public function __construct($config=[]){


    }

    public  function insert()
    {
        throw new \Exception();


    }

    public  function update()
    {
        throw new \Exception();


    }

    public  function delete()
    {
      throw new \Exception();
    }

    public  function run()
    {
        try{
            $transaction =\Yii::$app->getDb()->beginTransaction();

            $flujoProcesos = FlujoProceso::find()->where(["estado"=>'FlujoProcesoWorkflow/PE'])->all();
            foreach ($flujoProcesos as $flujoProceso){
                $flujoProcesoAgenteHelper = new FlujoProcesoAgenteHelper($flujoProceso);
                $flujoProcesoAgenteHelper->updateAgentes();
            }
            $transaction->commit();

            return true;
        }
        catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }



    }



}