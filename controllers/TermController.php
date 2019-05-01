<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class TermController extends Controller
{
    public function actionApiIndex(string $limit = '', string $offset = '', string $search = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
       
        $limit = $limit !== '' ? (int)$limit : (isset(Yii::$app->params['limit']) && Yii::$app->params['limit'] !== '' ? (int)Yii::$app->params['limit'] : NULL);
        $offset = $offset !== '' ? (int)$offset : (isset(Yii::$app->params['offset']) && Yii::$app->params['offset'] !== '' ? (int)Yii::$app->params['offset'] : 0);

        $string = file_get_contents(Yii::getAlias('@data/terms.json'));
        $terms = json_decode($string, true);
        
        $searchKey = Yii::$app->params['searchKey'] ?? NULL;
        $filteredTerms = $search && $searchKey ? array_filter($terms, function($item) use ($search, $searchKey) {
            $str = $item[$searchKey];
            return mb_strpos($str, $search) !== false;
        }) : $terms;

        return [
            'terms' => array_slice($filteredTerms, $offset, $limit),
            'total' => count($filteredTerms),
        ];
    }
}