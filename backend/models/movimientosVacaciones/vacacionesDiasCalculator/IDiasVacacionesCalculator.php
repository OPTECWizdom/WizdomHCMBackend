<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 15:29
 */

namespace backend\models\movimientosVacaciones\vacacionesDiasCalculator;
use backend\models\movimientosVacaciones\MovimientoVacaciones;

interface IDiasVacacionesCalculator
{

    /**
     * @return array
     *
     * Retorna un array en el cual la llave es el atributo al cual se le calcularon los dias,ej: dias habiles,
     * dias_feriados,dias_descansos, y el valor corresponde a los dias que se calcularon, ej: [dias_habiles=>1]
     *
     */
    public function calcularVacaciones();




    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */

    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones);

}