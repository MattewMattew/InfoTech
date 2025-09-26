<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class Book extends ActiveRecord
{
    public $coverImageFile = null;

    public $authorIds = [];

    public static function tableName(): string
    {
        return 'books';
    }

    public function rules(): array
    {
        return [
            [['authorIds'], 'safe'],
            [['title', 'release_year', 'isbn'], 'required'],
            [['release_year'], 'integer', 'min' => 1000, 'max' => date('Y')],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['isbn'], 'unique'],
            [['cover_image'], 'string', 'max' => 500],
            [['coverImageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_authors', ['book_id' => 'id']);
    }

    public function getAuthorIds(): array
    {
        if (empty($this->authorIds)) {
            $this->authorIds = ArrayHelper::getColumn($this->authors, 'id');
        }
        return $this->authorIds;
    }

    public function getAuthorNames(): string
    {
        return implode(', ', array_map(function($author) {
            return $author->full_name;
        }, $this->authors));
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }

    /**
     * @throws Exception
     */
    public function upload(): bool
    {
        if ($this->coverImageFile !== null) {
            $fileName = Yii::$app->security->generateRandomString() . '.' . $this->coverImageFile->extension;
            $filePath = Yii::getAlias('@webroot/uploads/') . $fileName;

            if ($this->coverImageFile->saveAs($filePath)) {
                $this->cover_image = $fileName;
                return true;
            }
        }
        return false;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function saveAuthors(): void
    {
        BookAuthor::deleteAll(['book_id' => $this->id]);

        if (!empty($this->authorIds)) {
            foreach ($this->authorIds as $authorId) {
                $bookAuthor = new BookAuthor();
                $bookAuthor->book_id = $this->id;
                $bookAuthor->author_id = $authorId;
                $bookAuthor->save();
            }
        }
    }

    /**
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!parent::save($runValidation, $attributeNames)) {
                $transaction->rollBack();
                return false;
            }
            $this->saveAuthors();
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            // Отправка уведомлений о новой книге
            foreach ($this->authors as $author) {
                $this->sendSubscriptionNotifications($author);
            }
        }
    }

    private function sendSubscriptionNotifications($author): void
    {
        $subscriptions = Subscription::find()->where(['author_id' => $author->id])->all();

        foreach ($subscriptions as $subscription) {
            $this->sendSMS($subscription->phone, "Новая книга автора {$author->full_name}: {$this->title}");
        }
    }

    private function sendSMS($phone, $message): void
    {
        $apiKey = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ'; // TODO: Поменять на данные из .env, надеюсь не забуду :)
        $url = "https://smspilot.ru/api.php";

        $params = [
            'send' => $message,
            'to' => $phone,
            'apikey' => $apiKey,
            'format' => 'json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        Yii::info("SMS sent to {$phone}: {$message}. Response: {$response}");
    }

}
