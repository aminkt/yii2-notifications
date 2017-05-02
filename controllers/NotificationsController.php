<?php

namespace machour\yii2\notifications\controllers;

use common\models\Notification;
use machour\yii2\notifications\NotificationsModule;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class NotificationsController extends Controller
{
    /**
     * @var integer The current user id
     */
    private $userId;

    /**
     * @var string The current user type
     */
    private $userType;

    /**
     * @var string The notification class
     */
    private $notificationClass;

    /**
     * @inheritdoc
     */
    public function init()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $this->userId = $this->module->userId;
        $this->userType = $this->module->userType;
        $this->notificationClass = $this->module->notificationClass;
        parent::init();
    }

    /**
     * Poll action
     *
     * @param int $seen Whether to show already seen notifications
     * @return array
     */
    public function actionPoll($seen = 0)
    {
        /** @var Notification $class */
        $class = $this->notificationClass;
        $models = $class::find()->where([
            'userId' => $this->userId,
            'userType'=> $this->userType,
            'seen' => $seen
        ])->orWhere([
            'userId' => 0,
            'seen' => $seen
        ])->orWhere([
            'userId' => -1,
            'seen' => $seen
        ])->all();

        $results = [];

        foreach ($models as $model) {
            $formatter = Yii::$app->getFormatter();
            /** @var Notification $model */
            $results[] = [
                'id' => $model->id,
                'type' => $model->type,
                'title' => $model->getTitle(),
                'description' => $model->getDescription(),
                'url' => Url::to(['notifications/rnr', 'id' => $model->id]),
                'key' => $model->key,
                'date' => $formatter->asRelativeTime($model->createTime)
            ];
        }
        return $results;
    }

    /**
     * Marks a notification as read and redirects the user to the final route
     *
     * @param int $id The notification id
     * @return Response
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    public function actionRnr($id)
    {
        $notification = $this->actionRead($id);
        return $this->redirect(Url::to($notification->getRoute()));
    }

    /**
     * Marks a notification as read
     *
     * @param int $id The notification id
     * @return Notification The updated notification record
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    public function actionRead($id)
    {
        $notification = $this->getNotification($id);

        $notification->seen = 1;
        $notification->save();

        return $notification;
    }

    /**
     * Deletes a notification
     *
     * @param int $id The notification id
     * @return int|false Returns 1 if the notification was deleted, FALSE otherwise
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    public function actionDelete($id)
    {
        $notification = $this->getNotification($id);
        return $notification->delete();
    }


    public function actionTest(){
        $n = NotificationsModule::getInstance();
        echo $n->userType;
//        Notification::error(Notification::KEY_NEW_BOT_REG, 2, Notification::USER_TYPE_ADMIN, 2);
    }

    /**
     * Gets a notification by id
     *
     * @param int $id The notification id
     * @return Notification
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    private function getNotification($id)
    {
        /** @var Notification $notification */
        $class = $this->notificationClass;
        $notification = $class::findOne($id);
        if (!$notification) {
            throw new HttpException(404, "Unknown notification");
        }

        if($notification->userId != -1)
            if (($notification->userId != $this->userId and  $notification->userId!=0) or $notification->userType != $this->userType) {
                throw new HttpException(500, "Not your notification");
            }

        return $notification;
    }
}