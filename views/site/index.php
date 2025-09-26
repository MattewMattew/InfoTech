<?php
use yii\helpers\Html;

$this->title = 'Каталог книг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <div class="jumbotron">
        <p>
            <?= Html::a('Книги', ['/book/index'], ['class' => 'btn btn-lg btn-success']) ?>
            <?= Html::a('Авторы', ['/author/index'], ['class' => 'btn btn-lg btn-primary']) ?>
            <?= Html::a('Отчеты', ['/report/top-authors'], ['class' => 'btn btn-lg btn-info']) ?>
        </p>
    </div>
</div>