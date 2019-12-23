<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class Book extends Model {
    const BOOK_SCHEMA_PATH  = '@content/kala-ha_{:id}/schema.json';
    const BOOKS_SCHEMA_PATH = '@content/books.json';

    /**
     * @param string $path
     *
     * @retrun array
     */
    public static function getSchema(string $path) : array
    {
        $file = Yii::getAlias($path);
        return json_decode(file_get_contents($file), true);
    }

    /**
     * @return array
     */
    public static function getBooksSchema() : array
    {
        return self::getSchema(self::BOOKS_SCHEMA_PATH);
    }

    /**
     * param int $id
     *
     * @return array
     */
    public static function getBookSchema(int $id) : array
    {
        $books = ArrayHelper::index(self::getSchema(self::BOOKS_SCHEMA_PATH), 'id');
        $book = self::getSchema(str_replace('{:id}', $id < 10 ? "0{$id}" : $id, self::BOOK_SCHEMA_PATH));

        return array_merge($books[$id] ?? [], $book);
    }

    /**
     * param int $id
     * param int $chapter
     *
     * @return array
     */
    public static function getBookChapterSchema(int $id, int $chapter) : array
    {
        $book = self::getSchema(str_replace('{:id}', $id < 10 ? "0{$id}" : $id, self::BOOK_SCHEMA_PATH));
        $chapters = ArrayHelper::index($book['chapters'] ?? [], 'id');

        return $chapters[$chapter] ?? [];
    }
}
