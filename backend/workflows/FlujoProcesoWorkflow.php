<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 17:09
 */

namespace backend\workflows;


class FlujoProcesoWorkflow implements \raoul2000\workflow\source\file\IWorkflowDefinitionProvider
{

    public function getDefinition()
    {

        return [
            'initialStatusId'=>'P', //Pendiente
            'status'=>[
                'P'=>[
                    'label'=> 'Pendiente',
                    'transition'=>['A','R','F']
                ],
                'A'=>[
                    'label'=>'Aprobado',
                ],
                'R'=>[
                    'label' => 'Rechazado',
                ],
                'F'=>[
                    'label'=>'Finalizado'
                ]
            ]
        ];

    }

}