<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 05/09/2017
 * Time: 8:28
 */

namespace backend\workflowManagers;

use backend\utils\email\GenericEmailSender;
use backend\utils\email\IEmailable;
use Yii;

class EmailSenderManager extends AbstractWorkflowManager
{
    private $emailables = [
        'backend\Models\notificacion\Notificacion',
        'backend\Models\proceso\flujoProceso\flujoProcesoAgente\FlujoProcesoAgente'
    ];

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

            foreach ($this->emailables as $emailable)
            {
                /**
                 * @var IEmailable $emailable
                 */
                $pendingEmails = $emailable::findPendingEmails();
                array_map([$this,'sendEmail'],$pendingEmails);

            }

            return true;
        }
        catch(\Exception $e)
        {
            throw $e;
        }

    }


    public function sendEmail(IEmailable $emailable)
    {
        $emailSender = new GenericEmailSender();
        $result =  $emailSender->sendEmail($emailable);
        if($result===true)
        {

            $transaction = Yii::$app->getDb()->beginTransaction();
            $emailable->setSentStatus();
            $emailable->save();
            $transaction->commit();
        }
        return $result;

    }



}