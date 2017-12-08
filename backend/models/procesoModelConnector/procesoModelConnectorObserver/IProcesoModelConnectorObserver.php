<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 08/12/2017
 * Time: 17:31
 */

namespace backend\models\procesoModelConnector\procesoModelConnectorObserver;


use backend\models\procesoModelConnector\ProcesoSubjectAbstract;

interface IProcesoModelConnectorObserver
{

    /**
     * @var ProcesoSubjectAbstract $procesoSubject
     * @return bool
     */
    public function insertProcesoModelConnector($procesoSubject);
}