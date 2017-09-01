<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/08/2017
 * Time: 12:40
 */

namespace app\models;


use yii\db\Exception;

class FlujoProcesoAgenteHelper implements IFlujoProcesoAgenteHelper
{
    private $flujoProceso;


    public function __construct(FlujoProceso $flujoProceso)
    {
        $this->flujoProceso = $flujoProceso;
    }

    public function insertAgente()
    {
        $flujoProcesoAgente = $this->getFirstAgenteFlujoProceso();
        if(!empty($flujoProcesoAgente)){
            $flujoProcesoAgente->save();

        }



    }




    public function getFirstAgenteFlujoProceso(){
        $flujoTipoProcesoAgentes = FlujoTipoProcesoAgente::findAgentesOfFlujoProceso($this->flujoProceso);
        if (!empty($flujoTipoProcesoAgentes)){
            $flujoTipoProcesoAgente = $flujoTipoProcesoAgentes[0];
            return $this->getFlujoProcesoAgenteFromFlujoTipoProcesoAgente($flujoTipoProcesoAgente);

        }


    }

    public function getFlujoProcesoAgenteFromFlujoTipoProcesoAgente(FlujoTipoProcesoAgente $flujoTipoProcesoAgente){
        $flujoProcesoAgente = new FlujoProcesoAgente();
        $flujoProcesoAgente->setAttributes($flujoTipoProcesoAgente->getAttributes());
        $flujoProcesoAgente->setAttribute('id_proceso',$this->flujoProceso->getAttribute('id_proceso'));
        $flujoProcesoAgente->setAttribute('correo_enviado','N');
        return $flujoProcesoAgente;

    }
}