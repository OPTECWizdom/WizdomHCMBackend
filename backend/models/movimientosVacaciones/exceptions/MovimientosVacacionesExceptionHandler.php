<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 21/12/2017
 * Time: 10:52
 */

namespace backend\models\movimientosVacaciones\exceptions;


use backend\models\abstractWizdomModel\AbstractWizdomModel;
use backend\models\abstractWizdomModel\AbstractWizdomModelExceptionHandler;
use yii\db\Exception;

class MovimientosVacacionesExceptionHandler extends AbstractWizdomModelExceptionHandler
{





   public function getClassCode()
   {
       return 1;
   }


   public function handleInsertScenario()
   {
       $message = $this->getErrorsStringHTML();
       $exception = new Exception($message,[],$this->getClassCode().$this::SCENARIO_INSERT_CODE);
       throw $exception;

   }

   public function handleUpdateScenario()
   {
   }


   public function handleDeleteScenario()
   {
   }

}