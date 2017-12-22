<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 27/11/2017
 * Time: 11:20
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomActiveController;

class SecurityUsersController extends AbstractWizdomActiveController
{
    public $modelClass =  'backend\models\security\securityUser\SecurityUser';

}