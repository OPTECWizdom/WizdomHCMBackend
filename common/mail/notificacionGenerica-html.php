<?php
use yii\helpers\Html;
use backend\models\notificacion\Notificacion;
/**
 * @var Notificacion $notificacion
 */
$notificacion;
$notificacionMsjArray = explode(':',$notificacion->getAttribute('mensaje'));
$notificacionAsunto = $notificacionMsjArray[0];
$notificacionMsj = $notificacionMsjArray[1];
?>
<div class="site-index">
    <p>
        Se le informa:
    </p>
    <?= Html::encode(($notificacionAsunto));?>
    <br>
    <?= Html::encode(($notificacionMsj));?>
</div>
