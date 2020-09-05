<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class TermController extends Controller
{
    public function actionApiIndex(string $limit = '', string $offset = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $limit = $limit !== '' ? (int)$limit : (isset(Yii::$app->params['limit']) && Yii::$app->params['limit'] !== '' ? (int)Yii::$app->params['limit'] : NULL);
        $offset = $offset !== '' ? (int)$offset : (isset(Yii::$app->params['offset']) && Yii::$app->params['offset'] !== '' ? (int)Yii::$app->params['offset'] : 0);

        $str = file_get_contents(Yii::getAlias('@data/terms-kalaha.json'));
        $terms = json_decode($str, true);

        return [
            'terms' => array_slice($terms, $offset, $limit),
            'total' => count($terms),
        ];
    }

    public function actionApiSearch(string $term, string $limit = '', string $offset = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $limit = $limit !== '' ? (int)$limit : (isset(Yii::$app->params['limit']) && Yii::$app->params['limit'] !== '' ? (int)Yii::$app->params['limit'] : NULL);
        $offset = $offset !== '' ? (int)$offset : (isset(Yii::$app->params['offset']) && Yii::$app->params['offset'] !== '' ? (int)Yii::$app->params['offset'] : 0);

        $str = file_get_contents(Yii::getAlias('@data/terms-kalaha.json'));
        $terms = json_decode($str, true);

        $searchKey = Yii::$app->params['searchKey'] ?? NULL;
        $filteredTerms = $term && $searchKey ? array_filter($terms, function($item) use ($term, $searchKey) {
            $str = $item[$searchKey];
            return mb_strpos($str, $term) !== false;
        }) : $terms;

        return [
            'terms' => array_slice($filteredTerms, $offset, $limit),
            'total' => count($filteredTerms),
        ];
    }
}
