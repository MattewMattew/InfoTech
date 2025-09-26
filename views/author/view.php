<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этого автора?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>

        <?php if (Yii::$app->user->isGuest): ?>
            <?= Html::a('Подписаться на новые книги', ['subscription/create', 'author_id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => ['method' => 'post']
            ]) ?>
        <?php endif; ?>

        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            [
                'attribute' => 'booksCount',
                'label' => 'Количество книг',
                'value' => $model->getBooksCount(),
            ],
            'created_at:datetime',
        ],
    ]) ?>

    <h2>Книги автора</h2>
    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getBooks(),
            'pagination' => ['pageSize' => 10],
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'title',
                'format' => 'html',
                'value' => function($model) {
                    return Html::a($model->title, ['book/view', 'id' => $model->id]);
                },
            ],
            'release_year',
            'isbn',
        ],
    ]); ?>
</div>