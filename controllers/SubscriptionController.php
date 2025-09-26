<?php

namespace app\controllers;

use app\models\Author;
use app\models\SubscriptionForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SubscriptionController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreate($author_id): Response|string
    {
        $author = Author::findOne($author_id);
        if (!$author) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        $model = new SubscriptionForm();
        $model->author_id = $author_id;

        if ($model->load(Yii::$app->request->post()) && $model->subscribe()) {
            Yii::$app->session->setFlash('success', 'Вы успешно подписались на уведомления о новых книгах автора.');
            return $this->redirect(['author/view', 'id' => $author_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'author' => $author,
        ]);
    }
}