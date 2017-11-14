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
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {

            $flujoProcesos = \backend\models\FlujoProceso::find()->where(['estado'=>'FlujoProcesoWorkflow/PE'])->all();
            foreach ($flujoProcesos as $flujoProceso)
            {
                $correoHelper = new \backend\models\FlujoTipoProcesoCorreoExternoHelper($flujoProceso);
                $correoHelper->sendEmail();

            }

            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
        return false;

    }

}