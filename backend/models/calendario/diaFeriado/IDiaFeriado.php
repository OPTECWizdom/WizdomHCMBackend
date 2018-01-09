<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 05/12/2017
 * Time: 15:08
 */

namespace backend\models\calendario\diaFeriado;


use yii\db\ActiveRecordInterface;

interface IDiaFeriado extends ActiveRecordInterface
{
    /**
     * @return int
     */
    public function getDiaFeriado();

    /**
     * @return int
     */
    public function getMesFeriado();

    /**
     * @return string
     */
    public function getNombreDiaFeriado();

}