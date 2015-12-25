<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
class Category extends ActiveRecord
{
    public static function tableName()
    {
        return 'categories';
    }
        
    public static function primaryKey()
    {
        return array('id');
    }
    public function getChilds()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    } 
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }  
    public function getGoods()
    {
        return $this->hasMany(Good::className(), ['category_id' => 'id']);
    }
    public function hasChilds()
    {
        return count($this->childs) > 0;
    }
    public static function getTreeRoute($categories, $target_id)
    {
        $mapper = ArrayHelper::map($categories, 'id', 'parent_id');
        $mapper_name = ArrayHelper::map($categories, 'id', 'name');
        $output = [];
        if(isset($mapper[$target_id])){
            $output[] = $target_id;
            $parent_id = $mapper[$target_id];
            $i = 0;
            while($parent_id > 0){
                $target_id = $parent_id;
                $output[] = $target_id;
                $parent_id = $mapper[$target_id];
            } 
            $output = array_reverse($output);   
        }
        $result = [['id' => 0, 'name' => 'Корневой каталог']];
        foreach($output as $out){
            $result[] = ['id' => $out, 'name' => $mapper_name[$out]];
        }
        return $result;   
    }
    public static function selfTree(&$output, $categories, $parent_id, $level)
    {
        $mapper = ArrayHelper::map($categories, 'id', 'name', 'parent_id');
        if(isset($mapper[$parent_id])){
            foreach($mapper[$parent_id] as $id => $map){
                $output[$id] = [
                  'name' => str_repeat(' - ', $level).' '.$map,
                  'id' => $id
                ];
                if(isset($mapper[$id])){
                    $level += 1;
                    self::selfTree($output, $categories, $id, $level);
                    $level -= 1;
                }
            }
        }
    }  
}