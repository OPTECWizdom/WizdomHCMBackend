<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $token backend\models\security\securityUser\TokenRestaurarPassword*/
/* @var $user backend\models\security\securityUser\SecurityUser */
/* @var $empleado backend\models\empleado\Empleado */


$resetLink = \Yii::$app->params['site_recover_password']."?".$token->getAttribute('token');
$fechaExpira = new \DateTime( $token->getAttribute('fecha_expira'));
$tiempoExpira = $fechaExpira->format('H:i:s');
$fechaExpira = $fechaExpira->format(\Yii::$app->params['displayDateFormat']);
$user = $token->getUser()->one();
$empleado = $user->getEmpleado()->one();
$nombre =  mb_convert_case(mb_strtolower($empleado->getNombreCompleto()),MB_CASE_TITLE);

?>
<div class="password-reset">

    <p>Hola, <?= Html::encode($nombre) ?>,</p>
    <p>Este correo fue enviado ya que un cambio de contraseña fue solicitado para su cuenta.<br>
        Por favor siga las siguientes instrucciones:
    </p>
    <ul>
        <li>Ingrese al siguiente enlace para reestablecer su contraseña<br>
            <?= Html::a(Html::encode($resetLink), $resetLink) ?>
        </li>
        <li>
           Este enlace expirará en tres horas.
        </li>
        <li>
            Si se solicita un nuevo cambio de contraseña, y el enlace anterior sigue vigente,
            el enlace anterior expirará automáticamente.
        </li>
    </ul>
</div>
