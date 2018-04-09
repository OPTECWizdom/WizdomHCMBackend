<?php
use yii\helpers\Html;
use backend\models\proceso\Proceso;
use \backend\models\procesoModelConnector\procesoIdiomaEmpleado\ProcesoIdiomaEmpleado;
use backend\models\idioma\idiomaEmpleado\IdiomaEmpleado;
use backend\models\empleado\Empleado;

/**
 * @var Proceso $proceso
 */
$proceso;

$procesoPK = $proceso->getAttributes(Proceso::primaryKey());
/**
 * @var ProcesoIdiomaEmpleado $procesoIdiomaEmpleado
 */

$procesoIdiomaEmpleado = ProcesoIdiomaEmpleado::find()->where($procesoPK)->one();

/**
 * @var IdiomaEmpleado $idiomaEmpleado
 */
$idiomaEmpleado = $procesoIdiomaEmpleado->getIdiomaEmpleado()->one();


/**
 * @var Empleado $empleado
 */

$empleado = $idiomaEmpleado->getEmpleado()->one();

$nombreEmpleado = mb_convert_case(mb_strtolower($empleado->nombre.' '.$empleado->primer_apellido.' '.$empleado->segundo_apellido),MB_CASE_TITLE);
$idioma = $idiomaEmpleado->getIdioma()->one()->getAttribute('nombre_idioma');

?>

<div class="site-index">

    <div class="jumbotron">
        <p>Tiene una nueva solicitud de aprobación de idioma.</p>
        <h3>Datos de la Solicitud</h3>
        <table>
            <tr>
                <th align = "left" width = "50%">
                    Nombre del Empleado
                </th >
                <th align = "left" width = "50%">
                    Idioma
                </th>

            </tr>
            <tr>
                <td>

                    <?= Html::encode(($nombreEmpleado));?>
                </td>
                <td>
                    <?= Html::encode($idioma);?>
                </td>
                <td>
                </td>
            </tr>
        </table>

    </div>


</div>
