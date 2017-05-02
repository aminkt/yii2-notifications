<?php

namespace machour\yii2\notifications\models;

use machour\yii2\notifications\NotificationsModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property string $keyId
 * @property string $key
 * @property string $type
 * @property boolean $seen
 * @property string $createTime
 * @property string $userType
 * @property integer $userId
 */
abstract class Notification extends ActiveRecord
{

    /**
     * Default notification
     */
    const TYPE_DEFAULT = 'default';
    /**
     * Information notification
     */
    const TYPE_INFO   = 'info';
    /**
     * Error notification
     */
    const TYPE_ERROR   = 'error';
    /**
     * Warning notification
     */
    const TYPE_WARNING = 'warning';
    /**
     * Success notification type
     */
    const TYPE_SUCCESS = 'success';

    /**
     * Define admin level user
     */
    const USER_TYPE_ADMIN = 'admin';

    /**
     * Define guest level user
     */
    const USER_TYPE_GUEST = 'guest';

    /**
     * Define user level user
     */
    const USER_TYPE_USER = 'user';

    /**
     * @var array List of all enabled notification types
     */
    public static $types = [
        self::TYPE_WARNING,
        self::TYPE_INFO,
        self::TYPE_DEFAULT,
        self::TYPE_ERROR,
        self::TYPE_SUCCESS,
    ];

    /**
     * Gets the notification title
     *
     * @return string
     */
    abstract public function getTitle();

    /**
     * Gets the notification description
     *
     * @return string
     */
    abstract public function getDescription();

    /**
     * Gets the notification route
     *
     * @return string
     */
    abstract public function getRoute();

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'userId', 'key', 'createTime', 'userType'], 'required'],
            [['id', 'keyId', 'createTime'], 'safe'],
            [['userId'], 'integer'],
            [['keyId', 'userType'], 'string'],
        ];
    }

    /**
     * Creates a notification
     *
     * @param string $key
     * @param integer $user_id The user id that will get the notification
     * @param null|string $user_type Type of user
     * @param string $key_id The foreign instance id
     * @param string $type
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public static function notify($key, $user_id, $user_type = self::USER_TYPE_USER, $key_id = null, $type = self::TYPE_DEFAULT)
    {
        $class = self::className();
        return NotificationsModule::notify(new $class(), $key, $user_id, $user_type, $key_id, $type);
    }

    /**
     * Creates a warning notification
     *
     * @param string $key
     * @param null|string $user_type Type of user
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The notification key id
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public static function warning($key, $user_id, $user_type=self::USER_TYPE_USER, $key_id = null)
    {
        return static::notify($key, $user_id, $user_type, $key_id, self::TYPE_WARNING);
    }


    /**
     * Creates an error notification
     *
     * @param string $key
     * @param null|string $user_type Type of user
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The notification key id
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public static function error($key, $user_id, $user_type=self::USER_TYPE_USER, $key_id = null)
    {
        return static::notify($key, $user_id, $user_type, $key_id, self::TYPE_ERROR);
    }


    /**
     * Creates a success notification
     *
     * @param string $key
     * @param null|string $user_type Type of user
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The notification key id
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public static function success($key, $user_id, $user_type=self::USER_TYPE_USER, $key_id = null)
    {
        return static::notify($key, $user_id, $user_type, $key_id, self::TYPE_SUCCESS);
    }

    /**
     * Creates a info notification
     *
     * @param string $key
     * @param null|string $user_type Type of user
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The notification key id
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public static function info($key, $user_id, $user_type=self::USER_TYPE_USER, $key_id = null)
    {
        return static::notify($key, $user_id, $user_type, $key_id, self::TYPE_INFO);
    }

}
