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
            'initialStatusId'=>'PE', //Pendiente
            'status'=>[
                'PE'=>[
                    'label'=> 'Pendiente',
                    'transition'=>['AP','RE','FI']
                ],
                'AP'=>[
                    'label'=>'Aprobado',
                ],
                'RE'=>[
                    'label' => 'Rechazado',
                ],
                'FI'=>[
                    'label'=>'Finalizado y Aprobado'
                ]
            ]
        ];

    }

}