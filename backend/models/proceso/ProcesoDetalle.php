<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 30/11/2017
 * Time: 10:11
 */

namespace backend\models\proceso;


use yii\db\ActiveRecord;
use yii\base\Event;
use backend\models\proceso\flujoProceso\FlujoProcesoDetalle;
use backend\models\proceso\flujoTipoProceso\FlujoTipoProcesoDetalle;
use backend\models\proceso\flujoProceso\FlujoProceso;
use backend\models\proceso\flujoProceso\flujoProcesoAgente\FlujoProcesoAgenteDetalle;

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
                                                                "id_proceso"=>"id_proceso"])->orderBy('codigo_tarea asc');
    }



    /**
     * @return \yii\db\ActiveQuery
     */

    public function getTipoFlujoProceso()
    {
        return $this->hasMany(FlujoTipoProcesoDetalle::className(),
                ["compania"=>"compania",
                "tipo_flujo_proceso"=>"tipo_flujo_proceso"])
                ->alias('FTP')
                ->where(['NOT EXISTS',FlujoProceso::find()
                                        ->alias('FP')
                                        ->where("ftp.compania = fp.compania and 
                                                 ftp.tipo_flujo_proceso = fp.tipo_flujo_proceso and 
                                                 ftp.codigo_tarea = fp.codigo_tarea")
                                        ->andWhere(["id_proceso"=>$this->id_proceso])])
                ->orderBy('orden');
    }


    public function getTipoFlujoProcesoDetalle()
    {
        /**
         * @var FlujoTipoProcesoDetalle[] $flujos
         */
        $flujos = $this->getTipoFlujoProceso()->all();
        foreach ($flujos as &$flujo)
        {
            $flujo->setProceso($this);
        }
        return $flujos;
    }


    public function fields()
    {
        $parentFields =  parent::fields();
        $customFields = ["flujoProcesoDetalle","tipoFlujoProcesoDetalle"];
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