<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 27/11/2017
 * Time: 11:20
 */

namespace backend\controllers;


use yii\rest\ActiveController;

class SecurityUsersController extends ActiveController
{
    public $modelClass =  'backend\models\security\securityUser\SecurityUser';

}