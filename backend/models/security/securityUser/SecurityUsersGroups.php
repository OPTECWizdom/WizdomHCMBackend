<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 05/02/2018
 * Time: 9:25
 */

namespace backend\models\security\securityUser;


use yii\db\ActiveRecord;

class SecurityUsersGroups extends ActiveRecord
{
    public static function tableName()
    {
        return "SEC_USERS_GROUPS";
    }


    public static function primaryKey()
    {
        return ["login","group_id"];
    }

    public function rules()
    {
        return [
            [
                ["login"],"string"
            ],
            [
                ["group_id"],"integer"
            ]
        ];
    }


}