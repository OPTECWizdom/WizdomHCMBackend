<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 24/08/2017
 * Time: 15:54
 */

namespace app\models;


use yii\db\Exception;

class VacacionesFlujoProcesoHelper implements IFlujoProcesoHelper
{



    private $movimientoVacaciones;
    private $flujoProceso;
    private $flujoTipoProceso;
    private $proceso;
    private $operation;


    public function __construct(MovimientoVacaciones $movimientoVacaciones,Proceso $proceso,int $operation = 0,
                                FlujoProceso $flujoProceso = null)
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
        $this->proceso = $proceso;
        $this ->operation = $operation;
        $this->flujoProceso;


    }
    public function getFlujoProceso():FlujoProceso{
        if(isset($this->flujoProceso)){
            return $this->flujoProceso;
        }
        $this->buildFlujoProceso();
        return $this->flujoProceso;
    }

    private function buildFlujoProceso(){
        try{
            if($this->operation==IFlujoProcesoHelper::FISRT)
            {
                $this->getFirstFlujoProceso();

            }
            $this->setAttributesFlujoProceso();




        }catch (Exception $e){
            throw $e;
        }

    }


    private function getFirstFlujoProceso(){
        $flujoTipoProceso = new FlujoTipoProceso();
        $flujoTipoProceso->setAttribute("compania",$this->proceso->getAttribute("compania"));
        $flujoTipoProceso->setAttribute("tipo_flujo_proceso",$this->proceso->getAttribute("tipo_flujo_proceso"));
        $this->flujoTipoProceso = $flujoTipoProceso->getPrimeraTarea();

    }


    private function setAttributesFlujoProceso(){
        $flujoProceso = new FlujoProceso();
        $flujoProceso->setAttribute("compania",$this->proceso->getAttribute("compania"));
        $flujoProceso->setAttribute("id_proceso",$this->proceso->getAttribute("id_proceso"));
        $flujoProceso->setAttribute("tipo_flujo_proceso",$this->proceso->getAttribute("tipo_flujo_proceso"));
        $flujoProceso->setAttribute("codigo_tarea",$this->flujoTipoProceso->getAttribute("codigo_tarea"));
        $this->flujoProceso = $flujoProceso;
    }








}