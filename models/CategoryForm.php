<?php

namespace app\models;

use Yii;
use yii\base\Model;

class CategoryForm extends Model
{
    public $name;
    public $parent_id;
    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Необходимо ввести название'],
            ['parent_id', 'validateParentId', 'message' => 'Неверно задан родительский каталог']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => 'Название категории',
            'parent_id' => 'ID родительского каталога'
        ];
    }
    public function validateParentId($attribute, $params)
    {
        $post = Yii::$app->request->post();
        if($this->$attribute == intval($post['CategoryForm']['id'])){
            $this->addError($attribute, 'Каталог не может быть потомком самого себя');
        }
    }
}
