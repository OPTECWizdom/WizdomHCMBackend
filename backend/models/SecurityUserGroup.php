<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:58
 */

namespace app\models;


use yii\db\ActiveRecord;

class SecurityUserGroup extends ActiveRecord
{
    public static function tableName()
    {
        return "sec_users_group";
    }

    public static function primaryKey()
    {
        return [
            "login","group_id"
        ];
    }

}