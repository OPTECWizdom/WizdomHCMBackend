<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 21/08/2017
 * Time: 11:58
 */

namespace backend\workflows;


class MovimientoVacacionesWorkflow implements \raoul2000\workflow\source\file\IWorkflowDefinitionProvider
{


    /**
     * Returns the workflow definition in the form of an array.
     * @return array
     */
    public function getDefinition()
    {
        return [
            'initialStatusId'=>'RV',
            'status'=>[
                'RV'=>[
                    'label'=> 'EN REVISION',
                    'transition'=>['AR','RE']
                ],
                'AR'=>[
                    'label'=>'APROBACION REVISION',
                    'transition'=>['AP','RE']
                ],
                'AP'=>[
                    'label' => 'APROBACION FINAL '
                ],
                'RE'=>[
                    'label'=>'RECHAZADO'
                ]
            ]

        ];
    }
}