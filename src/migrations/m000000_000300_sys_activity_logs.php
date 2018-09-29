<?php
/**
 * CodeUP yihai using Yii Framework
 * @link http://codeup.orangeit.id/yihai
 * @copyright Copyright (c) 2018 OrangeIT.ID
 * @author Upik Saleh <upxsal@gmail.com>
 */

/**
 * Class m000000_000300_sys_activity_logs
 */
class m000000_000300_sys_activity_logs extends \codeup\base\Migration
{
    public $tableName = '{{%sys_activity_logs}}';
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'    => $this->primaryKey(),
            'action' => $this->string()->notNull(),
            'model' => $this->string(),
            'type' => $this->string(20)->notNull(),
            'user' => $this->string(64)->notNull(),
            'time' => $this->integer()->notNull(),
            'msg' => $this->binary()
        ], $this->getTableOptions());
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}