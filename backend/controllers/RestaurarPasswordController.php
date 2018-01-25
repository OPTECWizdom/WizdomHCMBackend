<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/01/2018
 * Time: 16:38
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomActiveController;

class RestaurarPasswordController extends AbstractWizdomActiveController
{
    public $modelClass = 'backend\models\security\securityUser\TokenRestaurarPassword';

}