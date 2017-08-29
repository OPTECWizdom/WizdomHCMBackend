<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 24/08/2017
 * Time: 16:44
 */

namespace app\models;


use yii\db\ActiveRecord;

class FlujoTipoProceso extends ActiveRecord
{

    public static function tableName()
    {
        return "flujo_tipo_proceso";
    }

    public static function primaryKey()
    {
        return ["compania","tipo_flujo_proceso","codigo_tarea"];

    }

    public function rules()
    {
        return
            [
                [
                    [
                        "compania","tipo_flujo_proceso","codigo_tarea"
                     ],
                    "required",
                ]


            ];
    }

    public  function getPrimeraTarea(){
        try
        {
            $flujoTipoProceso = FlujoTipoProceso::find()->
                                                    where(
                                                        [
                                                            'compania'=>$this->compania,
                                                            'tipo_flujo_proceso' =>$this->tipo_flujo_proceso

                                                        ])
                                                        ->orderBy('orden')
                                                        ->one();
            return $flujoTipoProceso;

        }
        catch (\Exception $e){
            throw $e;
        }


    }


    public static function getNextFlujoTipoProceso(FlujoProceso $flujoProceso){


        $flujoTipoProcesoActual = FlujoTipoProceso::find()
                                    ->where
                                    ([
                                        "compania"=>$flujoProceso->getAttribute('compania'),
                                        "tipo_flujo_proceso"=>$flujoProceso->getAttribute('tipo_flujo_proceso'),
                                        "codigo_tarea"=>$flujoProceso->getAttribute('codigo_tarea')
                                    ])
                                    ->one();
        $flujoTipoProcesoSiguiente = FlujoTipoProceso::find()
                                    ->where
                                    ([
                                        "compania"=>$flujoProceso->getAttribute('compania'),
                                        "tipo_flujo_proceso"=>$flujoProceso->getAttribute('tipo_flujo_proceso')
                                    ])
                                    ->andWhere(['>', 'orden' ,$flujoTipoProcesoActual->getAttribute('orden')])
                                    ->orderBy('orden')
                                    ->one();
        return $flujoTipoProcesoSiguiente;


    }

}