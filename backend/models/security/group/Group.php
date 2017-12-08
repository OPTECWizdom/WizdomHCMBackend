<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:47
 */

namespace backend\models\security\group;


use yii\db\ActiveRecord;

class Group extends ActiveRecord
{

    public static function tableName()
    {
        return "SEC_GROUPS";
    }

    public static function primaryKey()
    {
        return [
            "group_id"
        ];
    }

}