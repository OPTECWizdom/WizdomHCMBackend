<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 08/12/2017
 * Time: 17:18
 */
namespace backend\models\procesoModelConnector\procesoObserver;
use backend\models\procesoModelConnector\ProcesoSubjectAbstract;
interface IProcesoObserver
{

    /**
     * @param ProcesoSubjectAbstract $procesoSubject
     * @return bool
     */

    public function insertProceso($procesoSubject);
}