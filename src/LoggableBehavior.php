<?php
/**
 * CodeUP yihai using Yii Framework
 * @link http://codeup.orangeit.id/yihai
 * @copyright Copyright (c) 2018 OrangeIT.ID
 * @author Upik Saleh <upxsal@gmail.com>
 */

namespace codeup\activitylog;

use codeup\base\ActiveRecord;
use yii\base\Behavior;

/**
 * Class LoggableBehavior
 * @package codeup\activitylog
 * @property \codeup\base\ActiveRecord $owner
 */
class LoggableBehavior extends Behavior
{

    private $_oldAttributes = [];

    public $ignore = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterFind($event){
        $this->_oldAttributes = $this->owner->getAttributes();
    }
    public function afterInsert($event){
        ActivityLog::newLog('insert', $this->owner, $this->owner->getAttributes());
    }
    public function afterUpdate($event){
        $updated_from = [];
        $updated_to = [];
        foreach($this->_oldAttributes as $key => $value){
            if(in_array($key, ['updated_at','updated_by','created_at','created_by'])){
                continue;
            }
            if($value != $this->owner->getAttribute($key)){
                $updated_from[$key] = $value;
                $updated_to[$key] = $this->owner->getAttribute($key);
            }
        }
        if(!empty($updated_from) && !empty($updated_to)) {
            $msg = [
                'from' => $updated_from,
                'to' => $updated_to
            ];
            $log = ActivityLog::newLog('update', $this->owner, $msg);
        }
    }

    public function afterDelete($event){
        ActivityLog::newLog('delete', $this->owner, $this->owner->getAttributes());
    }
}