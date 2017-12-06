<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/12/2017
 * Time: 15:56
 */

namespace backend\models;

/**
 * Interface IProcesoSubject
 * @package backend\models
 */
interface IProcesoSubject
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