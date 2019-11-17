<?php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class BookController extends Controller
{
    public function actionApiIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Book::getBooksSchema();
    }

    public function actionApiFile(int $id, string $name)
    {
        $file = Yii::getAlias("@content/kala-ha_0{$id}/{$name}");
        return Yii::$app->response->sendFile($file);
    }
}