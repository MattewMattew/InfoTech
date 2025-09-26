<?php

namespace app\models;

use yii\base\Model;
use yii\db\Exception;

class SubscriptionForm extends Model
{
    public $author_id;
    public $phone;

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'match', 'pattern' => '/^\+7\d{10}$/', 'message' => 'Телефон должен быть в формате +7XXXXXXXXXX'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'author_id' => 'Автор',
            'phone' => 'Номер телефона',
        ];
    }

    /**
     * @throws Exception
     */
    public function subscribe(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        // Проверяем, нет ли уже подписки
        $existing = Subscription::find()
            ->where(['author_id' => $this->author_id, 'phone' => $this->phone])
            ->exists();

        if ($existing) {
            $this->addError('phone', 'Вы уже подписаны на этого автора.');
            return false;
        }

        $subscription = new Subscription();
        $subscription->author_id = $this->author_id;
        $subscription->phone = $this->phone;

        if ($subscription->save()) {
            return true;
        }

        return false;
    }

}