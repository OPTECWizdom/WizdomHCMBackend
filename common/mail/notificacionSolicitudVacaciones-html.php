<?php
use yii\helpers\Html;
use app\models\ProcesoMovimientoVacacion;
use app\models\Proceso;



/**
 * @var Proceso $proceso
 */
$proceso;

$procesoPK = $proceso->getAttributes(Proceso::primaryKey());
/**
 * @var ProcesoMovimientoVacacion $procesoMovimientoVacaciones
 */

$procesoMovimientoVacaciones = ProcesoMovimientoVacacion::find()->where($procesoPK)->one();

/**
 * @var \app\models\MovimientoVacaciones $movimientoVacaciones
 */
$movimientoVacaciones = $procesoMovimientoVacaciones->getMovimientoVacaciones()->one();


/**
 * @var \app\models\Empleado $empleado
 */

$empleado = $movimientoVacaciones->getEmpleado()->one();

$nombreEmpleado = mb_convert_case(mb_strtolower($empleado->nombre.' '.$empleado->primer_apellido.' '.$empleado->segundo_apellido),MB_CASE_TITLE);
$fechaInicial = $movimientoVacaciones->getAttribute('fecha_inicial');
$dateTimeFechaInicial = new \DateTime($fechaInicial);
$fechaInicial =  $dateTimeFechaInicial->format(\Yii::$app->params['displayDateFormat']);

$fechaFinal = $movimientoVacaciones->getAttribute('fecha_final');
$dateTimeFechaFinal = new \DateTime($fechaFinal);
$fechaFinal =  $dateTimeFechaFinal->format(\Yii::$app->params['displayDateFormat']);

?>

<div class="site-index">

    <div class="jumbotron">
        <p>Tiene una nueva solicitud de vacaciones que requiere de su aprobación.</p>
        <h3>Datos de la solicitud</h3>
        <table>
            <tr>
                <th>
                    Nombre del Empleado
                </th>
                <th>
                    Fecha Inicial
                </th>
                <th>
                    Fecha Final
                </th>
            </tr>
            <tr>
                <td>

                    <?= Html::encode(($nombreEmpleado));?>
                </td>
                <td>
                    <?= Html::encode($fechaInicial);?>
                </td>
                <td>
                    <?= Html::encode($fechaFinal);?>
                </td>
            </tr>
        </table>

    </div>


</div>
