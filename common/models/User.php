<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\IdentityInterface;

/**
 * User model

 */
class User extends ActiveRecord implements \OAuth2\Storage\UserCredentialsInterface,IdentityInterface

{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return '{{%sec_users}}';
    }


    /**
     * Implemented for Oauth2 Interface
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static ::findOne(["login"=>"MObregon"]);
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
        return static ::findOne(["login"=>"MObregon"]);

    }

    public function validatePassword($password)
    {
       return $this->pswd =$password;
    }
    public function getId()
    {
        return 0;
    }


    public function getAuthKey()
    {

    }


    public function validateAuthKey($authKey)
    {
    }
}
