<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 02/09/2017
 * Time: 20:55
 */

namespace backend\models;


use yii\db\ActiveRecord;
use Yii;

class EnlaceExterno extends ActiveRecord
{

    public static function tableName()
    {
        return "enlaces_externos";
    }

    public static function primaryKey()
    {
        return ["id_url"];
    }


    public static function getRandomId()
    {
        $id = Yii::$app->getSecurity()->generateRandomString(32);
        $find = true;
        while($find)
        {
            $enlaceExterno = EnlaceExterno::find()->where(["id_url"=>$id])->all();
            if(empty($enlaceExterno))
            {
                $find = false;
            }
            else{
                $id = Yii::$app->getSecurity()->generateRandomString(32);
            }

        }
        return $id;

    }


    public function rules()
    {
        return [
          [
              ["id_url"],"required"
          ] ,
          [
              ["nombre_aplicacion","parametros","tstamp"],"string"
          ]
        ];
    }


    public function behaviors()
    {
        return [


            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [ 'tstamp'],
                ]
            ]

        ];

    }

}