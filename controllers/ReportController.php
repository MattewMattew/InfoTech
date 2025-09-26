<?php

namespace app\controllers;

use app\models\Author;
use yii\web\Controller;

class ReportController extends Controller
{
    public function actionTopAuthors($year = null): string
    {
        $year = $year ?: date('Y');

        $authors = Author::find()
            ->select([
                'authors.*',
                'COUNT(book_authors.book_id) as book_count'
            ])
            ->innerJoin('book_authors', 'book_authors.author_id = authors.id')
            ->innerJoin('books', 'books.id = book_authors.book_id')
            ->where(['books.release_year' => $year])
            ->groupBy('authors.id')
            ->orderBy(['book_count' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('top-authors', [
            'authors' => $authors,
            'year' => $year,
        ]);
    }
}