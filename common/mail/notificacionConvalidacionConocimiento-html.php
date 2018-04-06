<?php
use yii\helpers\Html;
use backend\models\proceso\Proceso;
use backend\models\procesoModelConnector\procesoConvalidacionConocimiento\ProcesoConvalidacionConocimiento;
use backend\models\convalidacionConocimiento\ConvalidacionConocimiento;
use backend\models\empleado\Empleado;

/**
 * @var Proceso $proceso
 */
$proceso;

$procesoPK = $proceso->getAttributes(Proceso::primaryKey());
/**
 * @var ProcesoConvalidacionConocimiento $procesoConvalidacionConocimiento
 */

$procesoConvalidacionConocimiento = ProcesoConvalidacionConocimiento::find()->where($procesoPK)->one();

/**
 * @var ConvalidacionConocimiento $convalidacionConocimiento
 */
$convalidacionConocimiento = $procesoConvalidacionConocimiento->getConvalidacionConocimiento()->one();


/**
 * @var Empleado $empleado
 */

$empleado = $convalidacionConocimiento->getEmpleado()->one();

$nombreEmpleado = mb_convert_case(mb_strtolower($empleado->nombre.' '.$empleado->primer_apellido.' '.$empleado->segundo_apellido),MB_CASE_TITLE);
$descripcionConocimiento = $convalidacionConocimiento->getAttribute('conocimiento_desc');

?>

<div class="site-index">

    <div class="jumbotron">
        <p>Tiene una nueva solicitud de convalidación de conocimiento.</p>
        <h3>Datos de la convalidación</h3>
        <table>
            <tr>
                <th align = "left" width = "50%">
                    Nombre del Empleado
                </th >
                <th align = "left" width = "50%">
                    Descripción
                </th>

            </tr>
            <tr>
                <td>

                    <?= Html::encode(($nombreEmpleado));?>
                </td>
                <td>
                    <?= Html::encode($descripcionConocimiento);?>
                </td>
                <td>
                </td>
            </tr>
        </table>

    </div>


</div>
