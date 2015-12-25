<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Category;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;

class CategoryController extends Controller
{
    public function actionCat($id = 0)
    {
        $id = intval($id);
        $category_name = 'Корень';
        if($id > 0){
            $cat_current = Category::findOne($id);
            if(!$cat_current){
                throw new HttpException(404, 'Указанный Вами каталог не найден');
            }
            if(!$cat_current->hasChilds()){
                $cat_goods = $cat_current->goods;
            }
            else{
                $cat_childs = $cat_current->childs;
            }
            $category_name = $cat_current->name;
        }
        else{
            $cat_childs = Category::find()->where(['parent_id' => 0])->all();
        }
        $chain = Category::getTreeRoute(Category::find()->asArray()->all(), $id);
        return $this->render(
          'cat',
          [
            'chain' => $chain, 
            'category_name' => $category_name, 
            'categories' => isset($cat_childs) ? $cat_childs : array(), 
            'goods' => isset($cat_goods) ? $cat_goods : array(),
            'id' => $id
          ]
        );
    }
    public function actionSave($id = 0)
    {
        $form = new \app\models\CategoryForm();
        $post = Yii::$app->request->post();
        if($form->load($post) && $form->validate()){
            if($id > 0){
                $cat_current = Category::findOne($id);
                if(!$cat_current){
                    throw new HttpException(404, 'Указанный Вами каталог не найден');
                }
            }
            else{
                $cat_current = new Category();
            }
            $parent_id = intval($post['CategoryForm']['parent_id']);
            if($parent_id <= 0){
                $depth = 1;
            }
            else{
                $parent_cat = Category::findOne($parent_id);
                $depth = intval($parent_cat->depth) + 1;
            }
            $cat_current->name = trim(htmlspecialchars($post['CategoryForm']['name']));
            $cat_current->parent_id = $parent_id;
            $cat_current->depth = $depth;
            $cat_current->save(); 
            return $this->redirect(Url::To(['category/cat', 'id' => $parent_id]));
        }
        return $this->redirect(Url::To(['category/'.($id > 0 ? 'update' : 'add'), 'id' => $id]));
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
            'controller_title' => 'Добавление нового вложенного каталога',
            'form' => new \app\models\CategoryForm(),
            'categories' => $categories_output,
            'catalog_id' => $catalog_id,
            'cat_current' => null,
            'id' => 0
          ]
        );
    }
    public function actionUpdate($id)
    {
        $id = intval($id);
        if($id <= 0){
            throw new HttpException(404, 'Указанный Вами каталог не найден');
        }
        $cat_current = Category::findOne($id);
        if(!$cat_current){
            throw new HttpException(404, 'Указанный Вами каталог не найден');
        }
        $categories = Category::find()->asArray()->all();
        $categories_output = [];
        Category::selfTree($categories_output, $categories, 0, 0);
        return $this->render(
          'form', 
          [
            'controller_title' => 'Изменение каталога '.$cat_current->name,
            'form' => new \app\models\CategoryForm(),
            'categories' => $categories_output,
            'catalog_id' => $cat_current->parent_id,
            'cat_current' => $cat_current,
            'id' => $id
          ]
        );
    }
    public function actionDelete($id)
    {
        $id = intval($id);
        if($id <= 0){
            throw new HttpException(404, 'Указанный Вами каталог не найден');
        }
        $cat_current = Category::findOne($id);
        if(!$cat_current){
            throw new HttpException(404, 'Указанный Вами каталог не найден');
        }
        $transaction = Category::getDb()->beginTransaction();
        try
        {
            $cat_current->delete();
            Category::updateAll(
              [
                'parent_id' => $cat_current->parent_id,
                'depth' => $cat_current->depth
              ],
              [
                'parent_id' => $cat_current->id
              ]
            );
            $transaction->commit();
            return $this->redirect(Url::To(['category/cat', 'id' => $cat_current->parent_id]));
        }
        catch(\Exception $ex)
        {
            $transaction->rollBack();
            throw $ex;
        }
    }
}
