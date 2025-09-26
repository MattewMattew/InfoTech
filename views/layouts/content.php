<?php

use yii\bootstrap5\Breadcrumbs;

?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $this->title ?></h1>
        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? [],
        ]) ?>
    </section>

    <section class="content">
        <?= $content ?>
    </section>
</div>