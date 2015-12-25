<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\Helpers\ArrayHelper;
$this->title = $controller_title;
?>
<h1><?php echo $controller_title ?></h1>
<?php $output = ActiveForm::begin(['action' => Url::To(['category/save', 'id' => $id])]); ?>
    <?php echo $output->field($form, 'id')->hiddenInput(['value' => $id])->label('') ?>
    <?php echo $output->field($form, 'name')->textInput(['value' => !is_null($cat_current) ? htmlspecialchars_decode($cat_current->name) : '']) ?>
    <?php
      $mapper = ArrayHelper::map(
        $categories,
        'id',
        'name'
      );
      $options = [
        $catalog_id => [
          'Selected' => 'selected'
        ]
      ];
      if($id > 0){
          $options[$id] = [
            'Disabled' => 'disabled'
          ];
      }
      echo $output->field($form, 'parent_id')
      ->dropDownList(
        $mapper,
        [
          'prompt' => 'Выберите родительский каталог',
          'options' => $options
        ]
      )
    ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        <a href="<?php echo Url::To(['category/cat', 'id' => $catalog_id]); ?>" class="btn btn-primary">Отменить</a>
    </div>
<?php ActiveForm::end(); ?>
