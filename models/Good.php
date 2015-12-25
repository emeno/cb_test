<?php

namespace app\models;
use yii\db\ActiveRecord;

class Good extends ActiveRecord
{
    public static function tableName()
    {
        return 'goods';
    }
        
    public static function primaryKey()
    {
        return array('id');
    }
}