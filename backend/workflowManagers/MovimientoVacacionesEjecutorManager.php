<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/10/2017
 * Time: 16:49
 */

namespace backend\workflowManagers;


use app\models\ControlAjusteVacacionAcumulado;
use app\models\ControlAjusteVacacionesMovimiento;
use app\models\MovimientoVacaciones;
use backend\wizdomWebServices\MovimientosVacacionesWebService\MovimientosVacacionesWebService;
use yii\db\ActiveRecord;
use yii\db\Transaction;

class MovimientoVacacionesEjecutorManager extends AbstractWorkflowManager
{


    public function __construct($config=[]){


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
            $movimientosVacaciones = MovimientoVacaciones::find()->where(['estado' => 'P'])->all();
            if (!empty($movimientosVacaciones)) {
                foreach ($movimientosVacaciones as $movimientosVacacion) {
                    $this->ejecutarMovimiento($movimientosVacacion);

                }

            }
            return true;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * @param MovimientoVacaciones $movimientoVacacion
     * @throws \Exception
     */

    private function ejecutarMovimiento(MovimientoVacaciones $movimientoVacacion)
    {
        try{

            $movimientosVacacionesWebService = new MovimientosVacacionesWebService($movimientoVacacion);
            $result = $movimientosVacacionesWebService->of_procesarmovimiento();
            if($result->of_procesarmovimientoResult=='1'){
                $controlAjuste = $movimientoVacacion->getControlAjusteVacacionesMov()->one();
                if(!empty($controlAjuste))
                {
                    $this->guardarControlAjuste($controlAjuste);

                }
            }


        }
        catch (\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * @param ControlAjusteVacacionesMovimiento $controlAjuste
     * @throws \Exception
     */

    private function guardarControlAjuste($controlAjuste)
    {
        try{
            $controlAjusteEmpleado = $controlAjuste->getControlAjusteAcumulado()->one();
            if(!empty($controlAjusteEmpleado))
            {
                $controlAjusteEmpleado->setAttribute('dias_ajuste',$controlAjuste->getAttribute('dias_ajuste'));

                $controlAjusteEmpleado->save();
            }

        }
        catch (\Exception $e)
        {
            throw $e;

        }

    }

}