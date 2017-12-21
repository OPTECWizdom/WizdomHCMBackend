<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 21/12/2017
 * Time: 10:43
 */

namespace backend\models\abstractWizdomModel;


abstract class AbstractWizdomModelExceptionHandler
{
    const SCENARIO_INSERT_CODE = '100';
    const SCENARIO_UPDATE_CODE = '200';
    const SCENARIO_DELETE_CODE = '300';


    /**
     * @var AbstractWizdomModel
     */
    private $wizdomModel;

    /**
     * @var array
     */

    private $scenariosFunctions;



    protected function getScenariosFunctions()
    {
        if(empty($this->scenariosFunctions))
        {
            $this->scenariosFunctions = [
                AbstractWizdomModel::SCENARIO_INSERT=>'handleInsertScenario',
                AbstractWizdomModel::SCENARIO_UPDATE=>'handleUpdateScenario',
                AbstractWizdomModel::SCENARIO_DELETE=>'handleDeleteScenario'

            ];
        }
        return $this->scenariosFunctions;

    }



    protected abstract function getClassCode();
    public function __construct($wizdomModel)
    {
        $this->wizdomModel = $wizdomModel;
    }

    public  function handleException()
    {
        $function = $this->getScenariosFunctions()[$this->wizdomModel->getScenario()];
        $this->$function();

    }


    public abstract function handleInsertScenario();
    public abstract function handleUpdateScenario();
    public abstract function handleDeleteScenario();

    /**
     * @return string
     */

    public function getErrorsStringHTML()
    {
        $errorsHTML = ''.
        $errors = $this->wizdomModel->getErrors();
        foreach ($errors as $attribute=>$error){
            foreach ($error as $message)
            {
              $errorsHTML.='<br>'.$message;
            }
        }
        return $errorsHTML;

    }




}