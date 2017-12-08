<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 30/11/2017
 * Time: 10:15
 */

namespace backend\models\proceso\flujoProceso;


use yii\db\ActiveRecord;
use yii\base\Event;
use backend\models\proceso\flujoProceso\flujoProcesoAgente\FlujoProcesoAgenteDetalle;

class FlujoProcesoDetalle extends ActiveRecord
{
    public static function tableName()
    {
        return "FLUJO_PROCESO";
    }


    public static function primaryKey()
    {
        return ["compania","tipo_flujo_proceso","id_proceso","codigo_tarea"];
    }



    public function getFlujoProcesoAgenteDetalle()
    {

        return $this->hasMany(FlujoProcesoAgenteDetalle::className(),["compania"=>"compania","tipo_flujo_proceso"=>"tipo_flujo_proceso",
                                                                "id_proceso"=>"id_proceso","codigo_tarea"=>"codigo_tarea"]);
    }

    public function init()
    {
        parent::init();
        Event::on(FlujoProcesoAgenteDetalle::className(),FlujoProcesoAgenteDetalle::EVENT_AFTER_FIND,
            function($event){FlujoProcesoDetalle::attachFlujoProceso($event);},['flujoProceso'=>$this]);
    }

    public static function attachFlujoProceso($event)
    {
        /**
         * @var FlujoProcesoDetalle $flujoProceso
         */
        $flujoProceso = $event->data['flujoProceso'];
        $flujoProcesoPks =  $flujoProceso->getAttributes(FlujoProcesoDetalle::primaryKey());
        /**
         * @var FlujoProcesoAgenteDetalle $flujoProcesoAgenteDetalle
         */
        $flujoProcesoAgenteDetalle = $event->sender;
        $flujoProcesoAgenteDetallePks = $flujoProcesoAgenteDetalle->getAttributes(FlujoProcesoDetalle::primaryKey());
        if($flujoProcesoAgenteDetallePks==$flujoProcesoPks)
        {
            $flujoProcesoAgenteDetalle->setFlujoProcesoDetalle($flujoProceso);
        }
    }



    public function fields()
    {
        $parentFields =  parent::fields();
        $customFields = ['flujoProcesoAgenteDetalle'];
        return array_merge($parentFields,$customFields);
    }


}