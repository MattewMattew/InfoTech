<?php

namespace app\models;

use app\models\Book;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BookSearch extends Book
{
    public $authorName; // для поиска по автору
    public $year_from;  // для диапазона годов
    public $year_to;

    public function rules(): array
    {
        return [
            [['id', 'release_year'], 'integer'],
            [['title', 'description', 'isbn', 'authorName'], 'safe'],
            [['year_from', 'year_to'], 'integer'],
        ];
    }

    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params): ActiveDataProvider
    {
        $query = Book::find();
        $query->joinWith(['authors']); // JOIN с авторами

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['release_year' => SORT_DESC],
                'attributes' => [
                    'id',
                    'title',
                    'release_year',
                    'isbn',
                    'created_at',
                    'authorName' => [
                        'asc' => ['authors.full_name' => SORT_ASC],
                        'desc' => ['authors.full_name' => SORT_DESC],
                        'label' => 'Автор'
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'release_year' => $this->release_year,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'isbn', $this->isbn]);

        // Фильтр по автору
        if ($this->authorName) {
            $query->andFilterWhere(['like', 'authors.full_name', $this->authorName]);
        }

        // Фильтр по диапазону годов
        if ($this->year_from) {
            $query->andFilterWhere(['>=', 'release_year', $this->year_from]);
        }
        if ($this->year_to) {
            $query->andFilterWhere(['<=', 'release_year', $this->year_to]);
        }

        // Убираем дубликаты из-за JOIN с авторами
        $query->distinct();

        return $dataProvider;
    }
}