<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Book extends Model {
    public static function getSchema()
    {
        $file = Yii::getAlias('@data/books.json');
        return json_decode(file_get_contents($file), true);
    }

    public static function getBooksSchema() : array
    {
        return self::getSchema();
    }

    public static function getBookSchema(int $id) : array
    {
        $books = getSchema();
        return $books[$id];
    }
}