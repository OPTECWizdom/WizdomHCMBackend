<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 30/11/2017
 * Time: 10:11
 */

namespace backend\models;


use function foo\func;
use yii\db\ActiveRecord;
use yii\base\Event;

class ProcesoDetalle extends ActiveRecord
{


    public static function tableName()
    {
        return "PROCESO";
    }

    public static function primaryKey()
    {
        return ["compania","tipo_flujo_proceso","id_proceso"];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getFlujoProcesoDetalle()
    {
       return $this->hasMany(FlujoProcesoDetalle::className(),["compania"=>"compania","tipo_flujo_proceso"=>"tipo_flujo_proceso",
                                                                "id_proceso"=>"id_proceso"]);
    }


    public function fields()
    {
        $parentFields =  parent::fields();
        $customFields = ["flujoProcesoDetalle"];
        return array_merge($parentFields,$customFields);
    }

    public function init()
    {
        parent::init();
        Event::on(FlujoProcesoAgenteDetalle::className(),FlujoProcesoAgenteDetalle::EVENT_AFTER_FIND,
                  function($event){ProcesoDetalle::attachProceso($event);},['proceso'=>$this]);
    }

    /**
     * @param Event $event
     */

    public static function attachProceso($event)
    {
        /**
         * @var ProcesoDetalle $proceso
         */
        $proceso = $event->data['proceso'];
        $procesoPks=  $proceso->getAttributes(ProcesoDetalle::primaryKey());
        /**
         * @var FlujoProcesoAgenteDetalle $flujoProcesoAgenteDetalle
         */
        $flujoProcesoAgenteDetalle = $event->sender;
        $flujoProcesoAgenteDetallePks = $flujoProcesoAgenteDetalle->getAttributes(ProcesoDetalle::primaryKey());
        if($flujoProcesoAgenteDetallePks==$procesoPks)
        {
           $flujoProcesoAgenteDetalle->setProcesoDetalle($proceso);
        }


    }

}