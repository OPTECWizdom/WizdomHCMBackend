<?php
use yii\helpers\Html;
use backend\models\proceso\Proceso;
use backend\models\procesoModelConnector\procesoPreparacionAcademicaEmpleado\ProcesoPreparacionAcademicaEmpleado;
use backend\models\preparacionAcademicaEmpleado\PreparacionAcademicaEmpleado;
use backend\models\empleado\Empleado;

/**
 * @var Proceso $proceso
 */
$proceso;

$procesoPK = $proceso->getAttributes(Proceso::primaryKey());
/**
 * @var ProcesoPreparacionAcademicaEmpleado $procesoPreparacionAcademicaEmpleado
 */

$procesoPreparacionAcademicaEmpleado = ProcesoPreparacionAcademicaEmpleado::find()->where($procesoPK)->one();

/**
 * @var PreparacionAcademicaEmpleado $preparacionAcademicaEmpleado
 */
$preparacionAcademicaEmpleado = $procesoPreparacionAcademicaEmpleado->getPreparacionAcademicaEmpleado()->one();


/**
 * @var Empleado $empleado
 */

$empleado = $preparacionAcademicaEmpleado->getEmpleado()->one();

$nombreEmpleado = mb_convert_case(mb_strtolower($empleado->nombre.' '.$empleado->primer_apellido.' '.$empleado->segundo_apellido),MB_CASE_TITLE);
$nombreProfesion = $preparacionAcademicaEmpleado->getProfesion()->one()->getAttribute('descripcion_profesion');
$gradoAcademico = $preparacionAcademicaEmpleado->getGradoAcademico()->one()->getAttribute('descripcion_grado');

?>

<div class="site-index">

    <div class="jumbotron">
        <p>Tiene una nueva solicitud de convalidación de Preparación Académica.</p>
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
                    <?= Html::encode("$gradoAcademico $nombreProfesion");?>
                </td>
                <td>
                </td>
            </tr>
        </table>

    </div>


</div>
