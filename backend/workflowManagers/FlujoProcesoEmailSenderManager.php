<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 05/09/2017
 * Time: 8:28
 */

namespace backend\workflowManagers;
use Yii;

class FlujoProcesoEmailSenderManager extends AbstractWorkflowManager
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }


    public function insert()
    {

    }
    public function update()
    {

    }
    public function delete()
    {
    }
    public function run()
    {
        try {

            $transaction = Yii::$app->getDb()->beginTransaction();
            $flujoProcesos = \app\models\FlujoProceso::find()->where(['estado'=>'FlujoProcesoWorkflow/PE'])->all();
            foreach ($flujoProcesos as $flujoProceso)
            {
                $correoHelper = new \app\models\FlujoTipoProcesoCorreoExternoHelper($flujoProceso);
                $correoHelper->sendEmail();

            }

            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            throw $e;
            $transaction->rollBack();
        }
        return false;

    }

}