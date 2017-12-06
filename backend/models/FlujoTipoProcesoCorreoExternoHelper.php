<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 23:26
 */

namespace backend\models;

use Yii;



class FlujoTipoProcesoCorreoExternoHelper
{

    private $flujoProceso;
    private $proceso;
    private $flujoTipoProceso;



    public function __construct(FlujoProceso $flujoProceso)
    {
        $this->flujoProceso  = $flujoProceso;
        $ids = Proceso::primaryKey();
        $primaryKeysProceso = $this->flujoProceso->getAttributes($ids);
        $this->proceso = Proceso::find()->where($primaryKeysProceso)->one();
        $idFlujoTipoProceso = FlujoTipoProceso::primaryKey();
        $primaryKeysFlujoTipoProceso = $this->flujoProceso->getAttributes($idFlujoTipoProceso);
        $this->flujoTipoProceso= FlujoTipoProceso::find()->where($primaryKeysFlujoTipoProceso)->one();
    }


    public function sendEmail()
    {
        $flujoProcesoAgentes = $this->getAgentesWithoutEmailSent();
        $correosFlujoProceso = $this->getCorreosOfFlujoTipoProceso();
        if (!empty($flujoProcesoAgentes) && !empty($correosFlujoProceso)) {
            foreach ($flujoProcesoAgentes as $flujoProcesoAgente) {
                foreach ($correosFlujoProceso as $correo) {
                    $mensaje = $correo->getAttribute('mensaje');
                    $asunto = $correo->getAttribute('asunto');
                    $this->sendEmailAgente($flujoProcesoAgente, $mensaje, $asunto);
                    $flujoProcesoAgente->setAttribute('correo_enviado','S');
                    $flujoProcesoAgente->save();
                }
            }
        }
    }


    public function sendEmailAgente(FlujoProcesoAgente $flujoProcesoAgente,string $mensaje, string $asunto)
    {
        $empleados = $this->getEmpleadosFromAgente($flujoProcesoAgente);

        if(!empty($empleados))
        {
            foreach($empleados as $empleado)
            {
                $asunto = $asunto." - ".ucwords(strtolower($empleado->nombre." ".$empleado->primer_apellido." ".$empleado->segundo_apellido));
                $correo = $empleado->getAttribute('correo_electronico_principal');
                if(!empty($correo))
                {
                    $this->sendEmailToEmpleado($correo,$mensaje,$asunto);
                }
            }
        }
    }

    public function createEnlaceExterno()
    {
        $enlaceExterno = new EnlaceExterno();
        $enlaceExterno->setAttribute('id_url', urlencode(EnlaceExterno::getRandomId()));
        $enlaceExterno->setAttribute('nombre_aplicacion', $this->flujoTipoProceso->getAttribute('aplicacion'));
        $enlaceExterno->setAttribute('parametros', $this->flujoProceso->getAttribute('parametros_aplicacion'));
        $enlaceExterno->save();
        return $enlaceExterno;



    }



    public function getAgentesWithoutEmailSent()
    {
        $ids = FlujoProceso::primaryKey();
        $primaryKeys = $this->flujoProceso->getAttributes($ids);
        $primaryKeys["correo_enviado"]="N";
        $flujoProcesoAgentes = FlujoProcesoAgente::find()->where($primaryKeys)->where($primaryKeys)->all();
        return $flujoProcesoAgentes;

    }

    public function getCorreosOfFlujoTipoProceso()
    {
        $ids = FlujoTipoProceso::primaryKey();
        $primaryKeys = $this->flujoProceso->getAttributes($ids);
        $correos = FlujoTipoProcesoCorreoExterno::find()->where($primaryKeys)->all();
        return $correos;

    }

    public function getEmpleadosFromAgente(FlujoProcesoAgente $flujoProcesoAgente)
    {
        $parametroAgente = $flujoProcesoAgente->getAttribute("parametro_agente");
        $agente = $flujoProcesoAgente->getAttribute("agente");
        $agenteSearcherFactory = new AgenteSearcherFactory($this->flujoProceso,$this->proceso,$parametroAgente);
        $agenteSearcher = $agenteSearcherFactory->createAgenteSearcher($agente);
        $search = $agenteSearcher->search();
        return $search;



    }



    public function sendEmailToEmpleado(string $email,string $message,string $subject)
    {
        $result =
         Yii::$app->mailer->compose($message,['proceso'=>$this->proceso])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($email)
            ->setSubject($subject)
            ->setTextBody($message)
            ->send();
        return $result;

    }






}