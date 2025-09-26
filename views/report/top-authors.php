<?php
use yii\helpers\Html;

$this->title = 'ТОП 10 авторов за ' . $year . ' год';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<form method="get" class="form-inline mb-3">
    <div class="form-group mr-2">
        <label for="year" class="mr-2">Год: </label>
        <label>
            <input type="number" name="year" value="<?= $year ?>" class="form-control" min="1900" max="<?= date('Y') ?>">
        </label>
    </div>
    <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
</form>

<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Автор</th>
        <th>Количество книг</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($authors)): ?>
        <tr>
            <td colspan="3" class="text-center">Нет данных за выбранный год</td>
        </tr>
    <?php else: ?>
        <?php foreach ($authors as $index => $author): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= Html::a(Html::encode($author->full_name), ['author/view', 'id' => $author->id]) ?></td>
                <td><?= $author->book_count ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>