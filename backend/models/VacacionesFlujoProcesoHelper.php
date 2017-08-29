<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 24/08/2017
 * Time: 15:54
 */

namespace app\models;



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
        if(isset($this->flujoProceso))
        {
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




        }
        catch (\Exception $e)
        {
            throw $e;
        }

    }


    private function getFirstFlujoProceso()
    {
        $flujoTipoProceso = new FlujoTipoProceso();
        $flujoTipoProceso->setAttribute("compania",$this->proceso->getAttribute("compania"));
        $flujoTipoProceso->setAttribute("tipo_flujo_proceso",$this->proceso->getAttribute("tipo_flujo_proceso"));
        $this->flujoTipoProceso = $flujoTipoProceso->getPrimeraTarea();

    }


    private function setAttributesFlujoProceso()
    {
        $flujoProceso = new FlujoProceso();
        $flujoProceso->setAttribute("compania",$this->proceso->getAttribute("compania"));
        $flujoProceso->setAttribute("id_proceso",$this->proceso->getAttribute("id_proceso"));
        $flujoProceso->setAttribute("tipo_flujo_proceso",$this->proceso->getAttribute("tipo_flujo_proceso"));
        $flujoProceso->setAttribute("codigo_tarea",$this->flujoTipoProceso->getAttribute("codigo_tarea"));
        $this->flujoProceso = $flujoProceso;
        $this->setParametrosAplicacion();
    }

    private function setParametrosAplicacion()
    {
        $parametrosAplicacion = [];
        $parametrosAplicacion[] = "gs_compania_movimientos_vacaciones='".$this->movimientoVacaciones->getAttribute("compania")."'";
        $parametrosAplicacion[] = "gs_tipo_mov_movimientos_vacaciones='".$this->movimientoVacaciones->getAttribute("tipo_mov")."'";
        $parametrosAplicacion[] = "gs_consecutivo_movimiento_movimientos_vacaciones='".$this->movimientoVacaciones->getAttribute("consecutivo_movimiento")."'";
        $parametrosAplicacion[] = "gs_compania_flujo_proceso='".$this->flujoProceso->getAttribute("compania")."'";
        $parametrosAplicacion[] = "gs_tipo_flujo_proceso_flujo_proceso='".$this->flujoProceso->getAttribute("tipo_flujo_proceso")."'";
        $parametrosAplicacion[] = "gi_id_proceso_flujo_proceso='".$this->flujoProceso->getAttribute("id_proceso")."'";
        $parametrosAplicacion[] = "gs_codigo_tarea_flujo_proceso='".$this->flujoProceso->getAttribute("codigo_tarea")."'";
        $stringParametrosAplicacion = implode(";",$parametrosAplicacion);
        $stringParametrosAplicacion.=";";
        $this->flujoProceso->setAttribute("parametros_aplicacion",$stringParametrosAplicacion);

    }








}