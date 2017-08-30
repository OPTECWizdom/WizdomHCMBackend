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
            'initialStatusId'=>'PE', //Pendiente
            'status'=>[
                'PE'=>[
                    'label'=> 'Pendiente',
                    'transition'=>['AP','RE'] //Primera aprobacion, Rechazo
                ],
                'AP'=>[
                    'label' => 'Aprobacion Final ',
                    'transition' => ['AP'] //Aprobacion Final
                ],
                'RE'=>[
                    'label'=>'Rechazado'
                ]
            ]
        ];
    }
}