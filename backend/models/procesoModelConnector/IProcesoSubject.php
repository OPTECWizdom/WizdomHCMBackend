<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/12/2017
 * Time: 15:56
 */

namespace backend\models\procesoModelConnector;
use yii\db\ActiveRecordInterface;

/**
 * Interface IProcesoSubject
 * @package backend\models
 */
interface IProcesoSubject  extends ActiveRecordInterface
{
    /**
     * @return string
     */
    public function getSubjectProcesoDescription();

    /**
     * @return string
     */

    public function getNotificationSubject();





}