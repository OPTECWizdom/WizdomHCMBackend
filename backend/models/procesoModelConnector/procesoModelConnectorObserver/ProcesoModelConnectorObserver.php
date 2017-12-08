<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 08/12/2017
 * Time: 17:30
 */
namespace backend\models\procesoModelConnector\procesoModelConnectorObserver;


use backend\models\procesoModelConnector\FactoryProcesoSubjectConnector;
use backend\models\procesoModelConnector\ProcesoSubjectAbstract;
use yii\db\ActiveRecord;

class ProcesoModelConnectorObserver implements IProcesoModelConnectorObserver
{
    /**
     * @var ProcesoSubjectAbstract $procesoSubject
     */
    private $procesoSubject;
    /**
     * @param ProcesoSubjectAbstract $procesoSubject
     * @return bool
     */
    public function insertProcesoModelConnector($procesoSubject)
    {
        $this->procesoSubject = $procesoSubject;
        $className = $this->getConnector();
        if(!empty($className))
        {
            $proceso = $procesoSubject->getProceso();
            /**
             * @var ActiveRecord $connector;
             */
            $connector = new $className();
            $connector->setAttributes($proceso->getAttributes($proceso::primaryKey()));
            $connector->setAttributes($procesoSubject->getAttributes($procesoSubject::primaryKey()));
            if($connector->save())
            {
                return true;
            }
            return false;


        }
        return true;
    }
    
    private function getConnector()
    {
        $factory = new FactoryProcesoSubjectConnector();
        $connectors = $factory->getConnectors();
        if(array_key_exists($this->procesoSubject::tableName(),$connectors))
        {
            return $connectors[$this->procesoSubject::tableName()];
        }
        return '';
    }

}