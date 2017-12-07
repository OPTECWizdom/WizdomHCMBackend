<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 07/12/2017
 * Time: 10:47
 */

namespace backend\models;


use yii\db\ActiveRecordInterface;

interface IEmailable extends ActiveRecordInterface
{
    /**
     * @return IEmailable[]
     */
    public static function findPendingEmails();

    /**
     * @return bool
     */
    public function setSentStatus();

    /**
     * @return string|null
     */
    public function getSubjectEmail();

    /**
     * @return string|null
     */
    public function getHTMLBody();

    /**
     * @return string|null
     */
    public function getEmailBody();

    /**
     * @return string|null
     */
    public  function getDestinations();

    /**
     * @return array|null
     */

    public function getHTMLBodyParms();

    /**
     * @return bool|null
     */
    public function isEmail();

}