<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 08/12/2017
 * Time: 17:11
 */

namespace backend\models\procesoModelConnector;


use backend\models\proceso\Proceso;
use yii\db\ActiveRecord;

abstract class ProcesoSubjectAbstract extends ActiveRecord implements IProcesoSubject
{

    /**
     * @var Proceso $proceso
     */

    private $proceso;


    public function setProceso($proceso)
    {
        $this->proceso = $proceso;
    }

    public function getProceso()
    {
        return $this->proceso;
    }


    /**
     * @return string
     */
    public  abstract function  getSubjectProcesoDescription();

    /**
     * @return string
     */

    public abstract function getNotificationSubject();




}