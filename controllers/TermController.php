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

        $dictionary_language = Yii::$app->params['dictionaryLanguage']['kalaha'] ?? null;

        if (!$limit) {
            $limit = Yii::$app->params['limit'] ?? null;
        }

        if (!$offset) {
            $offset = Yii::$app->params['offset'] ?? null;
        }

        $str = file_get_contents(Yii::getAlias('@data/terms-kalaha.json'));
        $terms = json_decode($str, true);

        if (!is_array($terms)) {
            $terms = [
                $dictionary_language => [],
            ];
        }

        if (!isset($terms[$dictionary_language])) {
            $terms[$dictionary_language] = [];
        }

        return [
            'terms' => array_slice($terms[$dictionary_language], $offset, $limit),
            'total' => count($terms[$dictionary_language]),
        ];
    }

    public function actionApiSearch(string $term, string $limit = '', string $offset = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dictionary_language = Yii::$app->params['dictionaryLanguage']['kalaha'] ?? null;

        if (!$limit) {
            $limit = Yii::$app->params['limit'] ?? null;
        }

        if (!$offset) {
            $offset = Yii::$app->params['offset'] ?? null;
        }

        $str = file_get_contents(Yii::getAlias('@data/terms-kalaha.json'));
        $terms = json_decode($str, true);

        if (!isset($terms[$dictionary_language])) {
            $terms[$dictionary_language] = [];
        }

        $searchKey = Yii::$app->params['searchColumn']['kalaha'] ?? NULL;
        $filteredTerms = $term && $searchKey ? array_filter($terms[$dictionary_language], function($item) use ($term, $searchKey) {
            $str = $item[$searchKey];
            return mb_strpos($str, $term) !== false;
        }) : $terms;

        return [
            'terms' => array_slice($filteredTerms, $offset, $limit),
            'total' => count($filteredTerms),
        ];
    }
}
