<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 02/09/2017
 * Time: 0:54
 */

namespace app\models;


class EmailVariableFormatter
{
    private $flujoProceso;
    private $proceso;
    private $operations;


    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso)
    {
        $this->flujoProceso = $flujoProceso;
        $this->proceso = $proceso;
        $this->operations = [
            "{\$nombre_empleado}"=>"getNombreEmpleadoSolicitante",
            "{\$fecha_inicial_vacaciones}"=>"getFechaInicialVacaciones",
            "{\$fecha_final_vacaciones}"=>"getFechaFinalVacaciones"


        ];


    }


    public function formatEmail($variable)
    {
        if(array_key_exists($variable,$this->operations))
        {
            $function = $this->operations[$variable];
            return $this->$function();
        }
        return $variable;
    }


    public function getNombreEmpleadoSolicitante()
    {
        $agenteSearcherFactory = new AgenteSearcherFactory($this->flujoProceso,$this->proceso,"");
        $agenteSearcher = $agenteSearcherFactory->createAgenteSearcher("EMPLEADO_SOLICITA");
        $empleado = $agenteSearcher->search();
        if(!empty($empleado))
        {
            $empleado = $empleado[0];
            $nombreEmpleadoArray = $empleado->getAttributes(["nombre","primer_apellido","segundo_apellido"]);
            $nombreEmpleado =ucwords (strtolower($nombreEmpleadoArray["nombre"]." ".$nombreEmpleadoArray["primer_apellido"]." ".$nombreEmpleadoArray["segundo_apellido"]));
            return $nombreEmpleado;

        }
        return "";

    }

    public function getFechaInicialVacaciones()
    {
        $procesoPks = $this->proceso->getAttributes(Proceso::primaryKey());
        $procesoMovimientoVacaciones = ProcesoMovimientoVacacion::find()->where($procesoPks)->one();
        if(!empty($procesoMovimientoVacaciones))
        {
            $movimientoVacaciones = $procesoMovimientoVacaciones->getMovimientoVacaciones()->one();
            $fechaInicial =  $this->$movimientoVacaciones('fecha_inicial');
            $dateTimeFechaInicial = new \DateTime($fechaInicial);
            return $dateTimeFechaInicial->format(\Yii::$app->params['displayDateFormat']);
        }
        return "";

    }
    public function getFechaFinalVacaciones()
    {
        $procesoPks = $this->proceso->getAttributes(Proceso::primaryKey());
        $procesoMovimientoVacaciones = ProcesoMovimientoVacacion::find()->where($procesoPks)->one();
        if(!empty($procesoMovimientoVacaciones))
        {
            $movimientoVacaciones = $procesoMovimientoVacaciones->getMovimientoVacaciones()->one();
            $fechaFinal =  $this->$movimientoVacaciones('fecha_final');
            $dateTimeFechaFinal = new \DateTime($fechaFinal);
            return $dateTimeFechaFinal->format(\Yii::$app->params['displayDateFormat']);
        }
        return "";

    }



}