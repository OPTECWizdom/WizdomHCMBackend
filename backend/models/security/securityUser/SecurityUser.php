<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 27/11/2017
 * Time: 11:16
 */

namespace backend\models\security\securityUser;


use yii\db\ActiveRecord;
use backend\models\empleado\Empleado;

class SecurityUser extends ActiveRecord
{

    public static function tableName()
    {
        return "SEC_USERS";
    }

    public static function primaryKey()
    {
        return ["login"];
    }
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->on(static::EVENT_AFTER_INSERT,[$this,"insertUserInGroup"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleado::className(),["username"=>"login"]);
    }

    public function extraFields()
    {
        $extraFields =  parent::extraFields(); // TODO: Change the autogenerated stub
        $extraFields[] = 'empleado';
        return $extraFields;
    }

    public function fields()
    {
        return ["login","email"];
    }

    public function rules()
    {
        return [
            [
               [ "login","pswd","name","email","active"],
                "string"
            ],
            ['active', 'default', 'value' => 'Y'],
            ['pswd','default','value' => md5(\Yii::$app->getSecurity()->generateRandomString(8))]


        ];
    }

    /**
     * @param Empleado $empleado
     */
    public function setAttributesFromEmpleado($empleado)
    {
        $empleadoData = $empleado->getAttributes(["username","nombre","primer_apellido","segundo_apellido","correo_electronico_principal"]);
        $this->setAttributes(["login"=>$empleadoData["username"],
                              "name"=>$empleadoData["nombre"].' '.$empleadoData["primer_apellido"].' '.
                                        $empleadoData["segundo_apellido"],
                              "email"=>$empleadoData["correo_electronico_principal"],
                              ]);
    }

    /**
     * @return bool
     */
    public function insertUserInGroup()
    {
        $groupUser = new SecurityUserGroup();
        $groupUser->setAttributes(["login"=>$this->getAttribute("login"),"group_id"=>2]);
        try
        {
            $groupUser->insert();
            return true;
        }
        catch (\Exception $e){
            return false;
        }

    }


}