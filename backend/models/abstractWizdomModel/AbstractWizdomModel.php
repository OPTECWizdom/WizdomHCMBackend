<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 21/12/2017
 * Time: 10:37
 */

namespace backend\models\abstractWizdomModel;


use yii\db\ActiveRecord;

/**
 * Class AbstractWizdomModel
 * @package backend\models
 */

abstract class AbstractWizdomModel extends ActiveRecord
{

    const SCENARIO_INSERT = 'INSERT';
    const SCENARIO_UPDATE = 'UPDATE';
    const SCENARIO_DELETE = 'DELETE';
    /**
     * @var AbstractWizdomModelExceptionHandler $wizdomModelExceptionHandler
     */
    protected $wizdomModelExceptionHandler;

    /**
     * @return AbstractWizdomModelExceptionHandler
     */
    protected abstract function getExceptionHandler();

    public function init()
    {
        parent::init();
        $this->on(self::EVENT_AFTER_VALIDATE,[$this,'handleException']);
    }


    public function getWizdomScenarios()
    {
        return [$this::SCENARIO_INSERT,$this::SCENARIO_UPDATE,$this::SCENARIO_DELETE];
    }

    public function handleException()
    {
        if($this->hasErrors() && in_array($this->getScenario(),$this->getWizdomScenarios()))
        {
            $exceptionHandler = $this->getExceptionHandler();
            if(!empty($exceptionHandler))
            {
                $exceptionHandler->handleException();

            }
        }

    }

}