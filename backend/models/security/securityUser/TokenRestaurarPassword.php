<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/01/2018
 * Time: 16:10
 */

namespace backend\models\security\securityUser;


use backend\utils\email\activeRecord\AbstractActiveRecordEmailSender;
use backend\utils\email\activeRecord\InsertEventEmailSender;
use backend\utils\email\IEmailable;
use yii\db\ActiveRecord;

class TokenRestaurarPassword extends AbstractActiveRecordEmailSender
{
    public function init()
    {
        parent::init();
        $this->on(parent::EVENT_BEFORE_INSERT,[$this,"expirarEnlacesValidos"]);
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

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getUser()
    {
        return $this->hasOne(SecurityUser::className(),["login"=>"username"]);
    }


    public function expirarEnlacesValidos()
    {
        $username = $this->getAttributes(["username"]);
        $enlacesValidos = static ::getEnlacesValidos($username);
        foreach($enlacesValidos as $enlace)
        {
            $enlace->setAttribute("expirado","S");
            $enlace->save();
        }

    }

    /**
     * @param $username
     * @return array|TokenRestaurarPassword[]|ActiveRecord[]
     */

    public static function getEnlacesValidos($username)
    {
        $key = ["username"=>$username];
        $fechaActual = new \DateTime();
        $fechaActual = \Yii::$app->formatter->asDatetime($fechaActual);
        $tokenQuery = static::find()->where($key)
            ->andWhere("'$fechaActual'<fecha_expira")
            ->andWhere(['expirado'=>'N']);
        return $tokenQuery->all();
    }

    /**
     * @return IEmailable[]
     */
    public static function findPendingEmails()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function setSentStatus()
    {
        return true;
    }

    /**
     * @return string|null
     */
    public function getSubjectEmail()
    {
        return "Wizdom HCM - ".\Yii::t('app','restablecer_contrasena');

    }

    /**
     * @return string|null
     */
    public function getHTMLBody()
    {
        return "passwordResetToken-html";
    }

    /**
     * @return string|null
     */
    public function getEmailBody()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public  function getDestinations()
    {
        $user = $this->getUser()->one();
        $correo = $user->getEmpleado()->one()->getAttribute("correo_electronico_principal");
        return $correo;
    }

    /**
     * @return array|null
     */

    public function getHTMLBodyParms()
    {
        return ["token"=>$this];
    }

    /**
     * @return bool|null
     */
    public function isEmail()
    {
        return true;
    }

    public function attachEmailSenders()
    {
        $insert = new InsertEventEmailSender();
        $insert->attachEvents($this);

    }


}