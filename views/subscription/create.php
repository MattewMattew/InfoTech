<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Подписаться на новые книги автора: ' . $author->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['author/index']];
$this->params['breadcrumbs'][] = ['label' => $author->full_name, 'url' => ['author/view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = 'Подписаться';
?>
<div class="subscription-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите ваш номер телефона, чтобы подписаться на уведомления о новых книгах этого автора.</p>
    <p class="text-muted">Формат: +7XXXXXXXXXX</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone')->textInput(['placeholder' => '+7XXXXXXXXXX']) ?>

    <div class="form-group">
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['author/view', 'id' => $author->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>