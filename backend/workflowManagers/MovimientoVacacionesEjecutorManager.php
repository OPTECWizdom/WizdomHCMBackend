<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/10/2017
 * Time: 16:49
 */

namespace backend\workflowManagers;


use backend\models\movimientosVacaciones\controlAjusteVacacionesMovimiento\ControlAjusteVacacionesMovimiento;
use backend\models\movimientosVacaciones\MovimientoVacaciones;
use backend\wizdomWebServices\MovimientosVacacionesWebService\MovimientosVacacionesWebService;
use yii\db\ActiveRecord;
use yii\db\Transaction;

class MovimientoVacacionesEjecutorManager extends AbstractWorkflowManager
{

    /**
     * @var MovimientoVacaciones $movimientoVacacion
     */
    public $movimientoVacacion;


    public function __construct($config=[]){
        if(!empty($config)) {
            try
            {
                $this->movimientoVacacion = $config[0];

            }catch (\Exception $e)
            {

            }
        }


    }
    public function insert()
    {
        // TODO: Implement insert() method.
    }
    public function update()
    {
        // TODO: Implement update() method.
    }
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function run()
    {


        try {

            $this->ejecutarMovimiento($this->movimientoVacacion);

            return true;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * @param MovimientoVacaciones $movimientoVacacion
     * @throws \Exception
     * @return bool
     */

    private function ejecutarMovimiento(MovimientoVacaciones $movimientoVacacion)
    {
        try{
            /**
             * @var ControlAjusteVacacionesMovimiento $controlAjuste
             */
            $controlAjuste = $movimientoVacacion->getControlAjusteVacacionesMov()->one();
            $save = true;
            if(!empty($controlAjuste)) {
                $save = $this->guardarControlAjuste($controlAjuste);
            }
            if($save)
            {
                $movimientosVacacionesWebService = new MovimientosVacacionesWebService($movimientoVacacion);
                $result = $movimientosVacacionesWebService->of_procesarmovimiento();
                if($result->of_procesarmovimientoResult=='1'){
                    return true;
                }

            }

            throw new \Exception('Ha habido un error');
        }
        catch (\Exception $e)
        {
            throw  $e;
        }
    }

    /**
     * @param ControlAjusteVacacionesMovimiento $controlAjuste
     * @return bool
     * @throws \Exception
     */

    private function guardarControlAjuste($controlAjuste)
    {
        try{
            $controlAjusteEmpleado = $controlAjuste->getControlAjusteAcumulado()->one();
            if(!empty($controlAjusteEmpleado))
            {
                $controlAjusteEmpleado->setAttribute('dias_ajuste',$controlAjuste->getAttribute('dias_ajuste'));

                return $controlAjusteEmpleado->save();
            }

        }
        catch (\Exception $e)
        {
            throw $e;

        }
        return true;

    }

}