<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/12/2017
 * Time: 15:57
 */

namespace backend\models;



use yii\db\ActiveRecordInterface;

interface IProcesoSubjectConnector extends ActiveRecordInterface
{
    /**
     * @return IProcesoSubject
     */
    public function getProcesoSubject();

}