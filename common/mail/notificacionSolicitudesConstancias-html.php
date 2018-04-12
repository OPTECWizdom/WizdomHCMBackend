<?php
use yii\helpers\Html;
use backend\models\proceso\Proceso;
use backend\models\procesoModelConnector\procesoSolicitudesConstancias\ProcesoSolicitudesConstancias;
use backend\models\solicitudesConstancias\SolicitudConstancia;
use backend\models\empleado\Empleado;

/**
 * @var Proceso $proceso
 */
$proceso;

$procesoPK = $proceso->getAttributes(Proceso::primaryKey());
/**
 * @var ProcesoSolicitudesConstancias $procesoSolicitudConstancia
 */

$procesoSolicitudConstancia = ProcesoSolicitudesConstancias::find()->where($procesoPK)->one();

/**
 * @var SolicitudConstancia $solicitudConstancia
 */
$solicitudConstancia = $procesoSolicitudConstancia->getSolicitudConstancia()->one();


/**
 * @var Empleado $empleado
 */

$empleado = $solicitudConstancia->getEmpleado()->one();

$nombreEmpleado = mb_convert_case(mb_strtolower($empleado->nombre.' '.$empleado->primer_apellido.' '.$empleado->segundo_apellido),MB_CASE_TITLE);
$descripcionConstancia = $solicitudConstancia->getDocumento()->one()->getAttribute('descripcion');

?>

<div class="site-index">

    <div class="jumbotron">
        <p>Tiene una nueva solicitud de constancia.</p>
        <h3>Datos de la solicitud</h3>
        <table>
            <tr>
                <th align = "left" width = "50%">
                    Nombre del Empleado
                </th >
                <th align = "left" width = "50%">
                    Constancia
                </th>

            </tr>
            <tr>
                <td>

                    <?= Html::encode(($nombreEmpleado));?>
                </td>
                <td>
                    <?= Html::encode($descripcionConstancia);?>
                </td>
                <td>
                </td>
            </tr>
        </table>

    </div>


</div>
