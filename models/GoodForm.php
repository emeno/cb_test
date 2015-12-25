<?php

namespace app\models;

use Yii;
use yii\base\Model;

class GoodForm extends Model
{
    public $name;
    public $category_id;
    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Необходимо ввести название'],
            ['category_id', 'required', 'message' => 'Необходимо выбрать вложенный каталог']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => 'Название товара',
            'parent_id' => 'ID категории (каталога)'
        ];
    }
}
