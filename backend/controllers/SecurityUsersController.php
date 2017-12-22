<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 27/11/2017
 * Time: 11:20
 */

namespace backend\controllers;


use backend\rest\controllers\WizdomActiveController;

class SecurityUsersController extends WizdomActiveController
{
    public $modelClass =  'backend\models\security\securityUser\SecurityUser';

}