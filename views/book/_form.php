<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Author;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'release_year')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'coverImageFile')->fileInput() ?>
            <?php if (!$model->isNewRecord && $model->cover_image): ?>
                <div class="form-group">
                    <?= Html::img('/uploads/' . $model->cover_image, ['width' => '100', 'class' => 'img-thumbnail']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'authorIds')->widget(Select2::class, [
        'data' => ArrayHelper::map(Author::find()->all(), 'id', 'full_name'),
        'options' => [
                'placeholder' => 'Выберите авторов...',
                'multiple' => true
        ],
        'pluginOptions' => [
                'allowClear' => true
        ],
])->label('Авторы') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

<?php ActiveForm::end(); ?>