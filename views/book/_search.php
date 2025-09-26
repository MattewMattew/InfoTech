<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="book-search">
    <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                    'data-pjax' => 1
            ],
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'title') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'authorName')->label('Автор') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'release_year') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'isbn') ?>
        </div>
        <div class="col-md-2" style="padding-top: 25px;">
            <div class="form-group">
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>