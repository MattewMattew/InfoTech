<?php
use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
                'brandLabel' => 'Каталог книг',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar-expand-lg navbar-dark bg-dark fixed-top'],
        ]);

        $menuItems = [
                ['label' => 'Главная', 'url' => ['/site/index']],
                ['label' => 'Книги', 'url' => ['/book/index']],
                ['label' => 'Авторы', 'url' => ['/author/index']],
                ['label' => 'Отчеты', 'url' => ['/report/top-authors']],
        ];

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
        } else {
            $menuItems[] = '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                    . Html::submitButton(
                            'Выйти (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn btn-link nav-link']
                    )
                    . Html::endForm()
                    . '</li>';
        }

        echo Nav::widget([
                'options' => ['class' => 'navbar-nav ml-auto'],
                'items' => $menuItems,
        ]);
        NavBar::end();
        ?>

        <div class="container" style="margin-top: 70px;">
            <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>

            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success"><?= Yii::$app->session->getFlash('success') ?></div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger"><?= Yii::$app->session->getFlash('error') ?></div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <footer class="footer mt-5 py-3 bg-light">
        <div class="container">
            <p class="text-center text-muted">&copy; Каталог книг <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>