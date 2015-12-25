<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(Url::To(['category/cat']));
    }
    public function actionError()
    {
        return $this->render('error', ['message' => Yii::$app->errorHandler->exception->getMessage()]);
    }
}
