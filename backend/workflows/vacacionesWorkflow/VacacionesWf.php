<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 21/08/2017
 * Time: 11:58
 */

namespace backend\workflows\vacacionesWorkflow;


class VacacionesWf implements \raoul2000\workflow\source\file\IWorkflowDefinitionProvider
{


    /**
     * Returns the workflow definition in the form of an array.
     * @return array
     */
    public function getDefinition()
    {
        return [
            'initialStatusId'=>'pendienteAprobarJefe',
            'status'=>[
                'pendienteAprobarJefe'=>[
                    'transition'=>['aprobadaJefe','rechazadaJefe']
                ],
                'aprobadaJefe'=>[
                    'transition'=>['aprobada','rechazada']
                ]
            ]
        ];
    }
}