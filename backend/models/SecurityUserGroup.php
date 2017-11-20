<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:58
 */

namespace backend\models;


use yii\db\ActiveRecord;

class SecurityUserGroup extends ActiveRecord
{
    public static function tableName()
    {
        return "SEC_USERS_GROUP";
    }

    public static function primaryKey()
    {
        return [
            "login","group_id"
        ];
    }

}