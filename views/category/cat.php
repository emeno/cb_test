<?php

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Содержимое каталога '.$category_name;
?>
<style type="text/css">
  a { font-size: 16px; }
  .sublink { font-size: 11px; }
</style>
<div class="container">
  <div class="page">
      <?php $cnt = 0; foreach($chain as $ch){ ?>
      <a href="<?php echo Url::to(['category/cat', 'id' => $ch['id']]); ?>"><?php echo $ch['name'] ?></a>
      <?php if($cnt < count($chain) - 1){ ?>
      &nbsp;&raquo;&nbsp;
      <?php } ?>
      <?php $cnt++; } ?>
      <h1><?php echo $category_name ?></h1>
      <?php if(count($categories) > 0){ ?>
        <?php foreach($categories as $category){ ?>
          <a href="<?php echo Url::to(['category/cat', 'id' => $category->id]); ?>">
            Вложенный каталог &laquo;<?php echo $category->name ?>&raquo;
          </a>
          &nbsp;&nbsp;
          <a class="sublink" href="<?php echo Url::to(['category/update', 'id' => $category->id]); ?>">редактировать</a>&nbsp;
          <a class="sublink" onclick="return confirm('Подтвердите удаление каталога <?php echo $category->name ?>')" href="<?php echo Url::to(['category/delete', 'id' => $category->id]); ?>">удалить</a>
          <br />
        <?php } ?>
      <?php } else if(count($goods) > 0){ ?>
        <?php foreach($goods as $good){ ?>
          <span>Товар &laquo;<?php echo $good->name ?>&raquo;</span>&nbsp;
          <a class="sublink" href="<?php echo Url::to(['good/update', 'id' => $good->id]); ?>">редактировать</a>&nbsp;
          <a class="sublink" href="<?php echo Url::to(['good/delete', 'id' => $good->id]); ?>">удалить</a>
          <br />
        <?php } ?>
      <?php }else{ ?>
      <span>Каталог пуст</span>
      <?php } ?>
      <br /><br />
      <a href="<?php echo Url::to(['category/add', 'catalog_id' => $id]); ?>">Добавить категорию</a><br />
      <a href="<?php echo Url::to(['good/add', 'catalog_id' => $id]); ?>">Добавить товар</a>
  </div>
</div>
