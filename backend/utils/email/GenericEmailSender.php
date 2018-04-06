<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 07/12/2017
 * Time: 10:47
 */

namespace backend\utils\email;
use Yii;


class GenericEmailSender implements IEmailSender
{
    public function sendEmail(IEmailable $emailObject)
    {
        if($emailObject->isEmail()) {
            try {
                $result =
                    Yii::$app->mailer->compose($emailObject->getHTMLBody(), $emailObject->getHTMLBodyParms())
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($emailObject->getDestinations())
                        ->setSubject($emailObject->getSubjectEmail())
                        ->setTextBody($emailObject->getEmailBody())
                        ->send();
                return $result;
            }
            catch (\Exception $e)
            {
                \Yii::error($e->getTraceAsString(),1);
                return false;
            }
        }
        return true;

    }


}