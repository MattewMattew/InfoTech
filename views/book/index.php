<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'title',
                    [
                            'attribute' => 'authorNames',
                            'label' => 'Авторы',
                            'value' => function($model) {
                                return $model->getAuthorNames();
                            },
                    ],
                    'release_year',
                    'isbn',
                    [
                            'attribute' => 'cover_image',
                            'format' => 'html',
                            'value' => function($model) {
                                return $model->cover_image ?
                                        Html::img('/uploads/' . $model->cover_image, ['width' => '50', 'class' => 'img-thumbnail']) :
                                        'Нет фото';
                            },
                    ],
                    [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'visibleButtons' => [
                                    'update' => !Yii::$app->user->isGuest,
                                    'delete' => !Yii::$app->user->isGuest,
                            ]
                    ],
            ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>