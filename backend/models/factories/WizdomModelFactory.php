<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/03/2018
 * Time: 16:31
 */

namespace backend\models\factories;


use backend\models\abstractWizdomModel\AbstractWizdomModel;
use yii\db\ActiveRecord;

class WizdomModelFactory
{
    private static $tables = [
        'movimientos_vacaciones'=>'backend\models\movimientosVacaciones\MovimientoVacaciones',
    ];

    /**
     * Metodo que dado el nombre de una tabla retorna una nueva instancia del modelo correspondiente a esa tabla,
     * definido por el atributp $tables de esta clase
     * @param string $nombreTabla
     * @return AbstractWizdomModel
     * @throws \Exception En caso de que el nombre de la tabla no este definido en tables
     */
    public static function getWizdomModel($nombreTabla)
    {
        try
        {
            $clase = self::$tables[$nombreTabla];
            return new $clase();
        }
        catch (\Exception $e)
        {
            throw new \Exception('Clase no definida en el atributo tables');
        }

    }

}