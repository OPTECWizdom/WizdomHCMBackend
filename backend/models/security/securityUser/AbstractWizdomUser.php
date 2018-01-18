<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 18/01/2018
 * Time: 14:47
 */

namespace backend\models\security\securityUser;


use backend\models\empleado\Empleado;
use filsh\yii2\oauth2server\models\OauthAccessTokens;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

abstract class AbstractWizdomUser extends ActiveRecord implements IdentityInterface, \OAuth2\Storage\UserCredentialsInterface
{


    /**
     * @param
     * @return Empleado
     */

    public static abstract function getEmpleadoByUsername($username);

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $accessToken = OauthAccessTokens::findOne(["access_token"=>$token]);
        if($accessToken)
        {
            $empleado = static::getEmpleadoByUsername($accessToken->getAttribute('user_id'));
            return static::findByUsername($empleado->getAttribute('username'));
        }
        return null;
    }

    /**
     * Implemented for Oauth2 Interface
     */
    public function checkUserCredentials($username, $password)
    {
        $user = static::findByUsername($username);
        if (empty($user)) {
            return false;
        }
        return $user->validatePassword($password);
    }

    /**
     * Implemented for Oauth2 Interface
     */
    public function getUserDetails($username)
    {
        $user = static::findByUsername($username);
        return ['user_id' => $user->getId()];
    }

    public static function findIdentity($userId){
        return self::findByUsername($userId);
    }

    /**
     * @param $username
     * @return null|static
     */

    public static function findByUsername($username)
    {
        return static ::findOne(["login"=>$username]);

    }

    public function validatePassword($password)
    {
        return $this->pswd =$password;
    }

    public function getId()
    {
        $empleado = static::getEmpleadoByUsername($this->login);
        if($empleado)
        {
            return $empleado->getAttribute('codigo_empleado');
        }
        return null;
    }

    public function getAuthKey()
    {

    }
    public function validateAuthKey($authKey)
    {

    }




}