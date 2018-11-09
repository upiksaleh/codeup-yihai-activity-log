<?php
/**
 * CodeUP yihai using Yii Framework
 * @link http://codeup.orangeit.id/yihai
 * @copyright Copyright (c) 2018 OrangeIT.ID
 * @author Upik Saleh <upxsal@gmail.com>
 */

namespace codeup\activitylog;

use Cii;
use yii\helpers\Json;

/**
 * Class ActivityLog
 * @package codeup\activitylog
 * @property string $user
 * @property string $type
 * @property array  $msg
 * @property string $action
 * @property string $model
 * @property int $time
 *
 */
class ActivityLog extends \codeup\base\ActiveRecord
{
    public static function tableName()
    {
        return '{{%sys_activity_logs}}';
    }

    public function rules()
    {
        return [
            [['action', 'user', 'time', 'type'], 'required'],
            [['action', 'model'], 'string', 'max' => 255],
            ['type', 'string', 'max' => 20],
            ['user', 'string', 'max' => 64],
            ['msg', 'safe'],
            ['time', 'integer'],
        ];
    }

    public function setMsg($msg){
        $this->msg = Json::encode($msg, JSON_PRETTY_PRINT);
    }
    public function getMsg(){
        return Json::decode($this->msg);
    }
    /**
     * @param $type
     * @param null|\yii\base\Component|string $owner
     * @param array|string $msg
     * @return ActivityLog|boolean
     */
    public static function newLog($type, $owner = null, $msg = null, $save = true){
        $log = new static();
        $log->type = $type;
        $log->user = Cii::getGroupAndUserId();
        $log->action = Cii::$app->controller->action->getUniqueId();
        $log->time = time();

        if($owner){
            if($owner instanceof \yii\base\Component)
                $log->model = $owner->className();
            else
                $log->model = $owner;
        }
        if($msg != null){
            $log->setMsg($msg);
        }
        if($save){
            return $log->save();
        }else {
            return $log;
        }
    }

    public function filterRules()
    {
        return [
            ['id', 'integer'],
            ['action', 'safe'],
            ['model', 'safe'],
            ['type', 'safe'],
            ['user', 'safe'],
            ['time', 'safe'],
            ['msg', 'safe'],
        ];
    }
    public function onSearch(&$query, $filterModel)
    {
        if($filterModel->id){
            $query->andWhere(['like', 'id', $filterModel->id]);
        }
        if($filterModel->action){
            $query->andWhere(['like', 'action', $filterModel->action]);
        }
        if($filterModel->model){
            $query->andWhere(['like', 'model', $filterModel->model]);
        }
        if($filterModel->type){
            $query->andWhere(['type'=> $filterModel->type]);
        }
        if($filterModel->user){
            $query->andWhere(['like','user', $filterModel->user]);
        }
        if($filterModel->time){
            $query->andWhere(['like','time', $filterModel->time]);
        }
        if($filterModel->msg){
            $query->andWhere(['like','msg', $filterModel->msg]);
        }
    }
}