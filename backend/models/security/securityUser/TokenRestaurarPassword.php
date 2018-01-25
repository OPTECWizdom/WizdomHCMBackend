<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/01/2018
 * Time: 16:10
 */

namespace backend\models\security\securityUser;


use yii\db\ActiveRecord;

class TokenRestaurarPassword extends ActiveRecord
{
    public function init()
    {
        parent::init();
    }


    public static function tableName()
    {
        return "TOKEN_RESTAURAR_PASSWORD";
    }

    public static function primaryKey()
    {
        return ["username","token"];
    }

    public function rules()
    {
        return [
            [
                ["username","token","tstamp","expirado"],
                "string"
            ],
            [
                ["fecha_registro","fecha_expira"],
                "datetime"
            ],
            ['expirado', 'default', 'value' => 'N'],
            ['token','default','value'=>$this->getNewToken()],
            ['fecha_expira','default','value' =>\Yii::$app->formatter->asDatetime(new \DateTime("+1 day"))]

        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_registro', 'tstamp'],
                ]
            ]

        ];

    }

    /**
     * @param $token
     * @return null|static
     */

    public static function findByToken($token)
    {
        return static ::findOne(["token"=>$token]);

    }

    public function getNewToken()
    {
        $token = \Yii::$app->security->generateRandomString(32);
        while (static::findByToken($token))
        {
            $token =  \Yii::$app->security->generateRandomString(32);
        }
        return $token;
    }




}