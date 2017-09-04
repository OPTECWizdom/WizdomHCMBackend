<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 23:26
 */

namespace app\models;

use tests\unit\workflow\events\EnterWorkflowReducedEventTest;
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
        if (!empty($flujoProcesoAgentes) || !empty($correosFlujoProceso)) {
            foreach ($flujoProcesoAgentes as $flujoProcesoAgente) {
                foreach ($correosFlujoProceso as $correo) {
                    $mensaje = $this->formatEmail($correo);
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
        $enlaceExterno = $this->createEnlaceExterno();

        if(!empty($empleados))
        {
            foreach($empleados as $empleado)
            {
                $correo = $empleado->getAttribute('correo_electronico_principal');
                if(!empty($correo))
                {
                    $siteUrl = Yii::$app->params['siteUrl'];
                    $mensaje.="\n";
                    $enlaceExternoId = $enlaceExterno->getAttribute('id_url');
                    $htmlContent = "$mensaje<br><br><a href ='$siteUrl/?ext=$enlaceExternoId' >Ir a solicitud</a>";
                    $this->sendEmailToEmpleado($correo,$mensaje,$asunto,$htmlContent);
                }
            }
        }
    }

    public function createEnlaceExterno()
    {
        $enlaceExterno = new EnlaceExterno();
        $enlaceExterno->setAttribute('id_url', EnlaceExterno::getRandomId());
        $enlaceExterno->setAttribute('nombre_aplicacion', $this->flujoTipoProceso->getAttribute('aplicacion'));
        $enlaceExterno->setAttribute('parametros', $this->flujoProceso->getAttribute('parametros_aplicacion'));
        $enlaceExterno->save();
        return $enlaceExterno;



    }



    public function getAgentesWithoutEmailSent()
    {
        $ids = FlujoProceso::primaryKey();
        $primaryKeys = $this->flujoProceso->getAttributes($ids);
        $flujoProcesoAgentes = FlujoProcesoAgente::find()->where($primaryKeys)->where(["correo_enviado"=>'N'])->all();
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
        return $agenteSearcher->search();


    }


    public function formatEmail(FlujoTipoProcesoCorreoExterno $correo)
    {
        $message = $correo->getAttribute("texto");
        $formatter = new EmailVariableFormatter($this->flujoProceso,$this->proceso);
        $variables = [];
        $pattern = '/{\$\w+}/';
        $result = preg_match($pattern,$message,$variables);
        if($result)
        {
            foreach ($variables as $variable)
            {
                $formattedVariable = $formatter->formatEmail($variable);
                $message = str_replace($variable,$formattedVariable,$message);
            }
        }
        return $message;

    }

    public function sendEmailToEmpleado(string $email,string $message,string $subject, string $htmlContent)
    {
        $result =
         Yii::$app->mailer->compose()
            ->setFrom('optec.wizdom@gmail.com')
            ->setTo("ldrc2895@gmail.com")
            ->setSubject($subject)
            ->setTextBody($message)
            ->setHtmlBody($htmlContent)
            ->send();
        var_dump($result);
        return $result;

    }






}