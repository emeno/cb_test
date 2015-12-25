<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Тестовое задание для CyberPlat';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Корневой каталог</h1>
        <br />
        <?php foreach($this->categories as $category){ ?>
        <a href=""><?php echo $category->name ?></a><br />
        <?php } ?>
    </div>
</div>
