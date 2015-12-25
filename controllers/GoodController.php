<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Category;
use app\models\Good;
use yii\web\HttpException;

class GoodController extends Controller
{
    public function actionSave($id = 0)
    {
        $form = new \app\models\GoodForm();
        $post = Yii::$app->request->post();
        if($form->load($post) && $form->validate()){
            if($id > 0){
                $good_current = Good::findOne($id);
                if(!$good_current){
                    throw new HttpException(404, 'Указанный Вами товар не найден');
                }
            }
            else{
                $good_current = new Good();
            }
            $category_id = intval($post['GoodForm']['category_id']);
            $good_current->name = trim(htmlspecialchars($post['GoodForm']['name']));
            $good_current->category_id = $category_id;
            $good_current->save(); 
            return $this->redirect(Url::To(['category/cat', 'id' => $category_id]));
        }
        return $this->redirect(Url::To(['good/'.($id > 0 ? 'update' : 'add'), 'id' => $id]));
    }
    public function actionAdd($catalog_id  = 0)
    {
        $catalog_id = intval($catalog_id);
        $categories = Category::find()->asArray()->all();
        $categories_output = [];
        Category::selfTree($categories_output, $categories, 0, 0);
        return $this->render(
          'form', 
          [
            'controller_title' => 'Добавление нового товара',
            'form' => new \app\models\GoodForm(),
            'categories' => $categories_output,
            'catalog_id' => $catalog_id,
            'good_current' => null,
            'id' => 0
          ]
        );
    }
    public function actionUpdate($id)
    {
        $id = intval($id);
        if($id <= 0){
            throw new HttpException(404, 'Указанный Вами товар не найден');
        }
        $good_current = Good::findOne($id);
        if(!$good_current){
            throw new HttpException(404, 'Указанный Вами товар не найден');
        }
        $categories = Category::find()->asArray()->all();
        $categories_output = [];
        Category::selfTree($categories_output, $categories, 0, 0);
        return $this->render(
          'form', 
          [
            'controller_title' => 'Изменение товара '.$good_current->name,
            'form' => new \app\models\GoodForm(),
            'categories' => $categories_output,
            'catalog_id' => $good_current->category_id,
            'good_current' => $good_current,
            'id' => $id
          ]
        );
    }
    public function actionDelete($id)
    {
        $id = intval($id);
        if($id <= 0){
            throw new HttpException(404, 'Указанный Вами товар не найден');
        }
        $good_current = Good::findOne($id);
        if(!$good_current){
            throw new HttpException(404, 'Указанный Вами товар не найден');
        }
        $transaction = Good::getDb()->beginTransaction();
        try
        {
            $good_current->delete();
            $transaction->commit();
            return $this->redirect(Url::To(['category/cat', 'id' => $good_current->category_id]));
        }
        catch(\Exception $ex)
        {
            $transaction->rollBack();
            throw $ex;
        }
    }
}
