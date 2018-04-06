<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/12/2017
 * Time: 15:59
 */

namespace backend\models\procesoModelConnector;


use backend\models\proceso\Proceso;
use yii\db\ActiveRecord;

class FactoryProcesoSubjectConnector
{
    /**
     * @var array $connectors
     * Atributo que contiene el nombre de los flujos de proceso y la tabla usada para relacionarlos con los sujetos del
     * flujo de proceso.
     */
    private $connectors = [
        'movimientos_vacaciones'=>'backend\models\procesoModelConnector\procesoMovimientoVacacion\ProcesoMovimientoVacacion',
        'convalidacion_conocimiento' => 'backend\models\procesoModelConnector\procesoConvalidacionConocimiento\ProcesoConvalidacionConocimiento',
        'idioma_empleado'=>'backend\models\procesoModelConnector\procesoIdiomaEmpleado\ProcesoIdiomaEmpleado'
    ];

    /**
     * @param Proceso $proceso
     * Proceso del cual se va a encontrar el sujeto. Debe de tener las llaves primarias llenas.
     * @return IProcesoSubjectConnector|null
     */

    public function getSubjectProceso(Proceso $proceso)
    {
        try
        {
            $nombreProceso = $proceso->getAttribute('tipo_flujo_proceso');
            if(array_key_exists($nombreProceso,$this->connectors))
            {
                /**
                 * @var IProcesoSubjectConnector $procesoSubjectConnector
                 */
                $procesoSubjectConnector = new $this->connectors[$nombreProceso];
                $procesoSubjectConnector = $procesoSubjectConnector::find()->where(["compania"=>$proceso->getAttribute('compania'),
                                                        "id_proceso"=>$proceso->getAttribute('id_proceso'),
                                                        "tipo_flujo_proceso"=>$proceso->getAttribute('tipo_flujo_proceso')])->one();
                return $procesoSubjectConnector;
            }
        }
        catch (\Exception $e)
        {
        }
        return null;



    }

    public function getConnectors()
    {
        return $this->connectors;
    }

    /**
     * @param array $connectors
     */
    public function setConnectors(array $connectors)
    {
        $this->connectors = $connectors;
    }

    /**
     * Retorna una nueva instancia del conector asociado con el proceso
     * @param  $nombreProceso
     * @return ActiveRecord
     */
    public function getConnector($nombreProceso)
    {
        $className = $this->connectors[$nombreProceso];
        return  new $className;
    }




}