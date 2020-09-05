<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;

class DictionaryController extends Controller
{
    public function actionApiIndex(string $dict, string $lang = '', string $limit = '', string $offset = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;


        if (!$lang) {
            $lang = Yii::$app->params['dictLang'] ?? null;
        }
        if (!$lang) {
            throw new BadRequestHttpException('Missed required parameter: lang');
        }

        $limit = $limit !== '' ? (int)$limit : (isset(Yii::$app->params['limit']) && Yii::$app->params['limit'] !== '' ? (int)Yii::$app->params['limit'] : NULL);
        $offset = $offset !== '' ? (int)$offset : (isset(Yii::$app->params['offset']) && Yii::$app->params['offset'] !== '' ? (int)Yii::$app->params['offset'] : 0);

        $str = file_get_contents(Yii::getAlias('@data/terms-' . $dict . '.json'));
        $terms = json_decode($str, true);

        return [
            'terms' => array_slice($terms[$lang], $offset, $limit),
            'total' => count($terms[$lang]),
        ];
    }

    public function actionApiSearch(string $dict, string $lang = '', string $term, string $limit = '', string $offset = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$lang) {
            $lang = Yii::$app->params['dictLang'] ?? null;
        }
        if (!$lang) {
            throw new BadRequestHttpException('Missed required parameter: lang');
        }


        $limit = $limit !== '' ? (int)$limit : (isset(Yii::$app->params['limit']) && Yii::$app->params['limit'] !== '' ? (int)Yii::$app->params['limit'] : NULL);
        $offset = $offset !== '' ? (int)$offset : (isset(Yii::$app->params['offset']) && Yii::$app->params['offset'] !== '' ? (int)Yii::$app->params['offset'] : 0);

        $str = file_get_contents(Yii::getAlias('@data/terms-' . $dict . '.json'));
        $terms = json_decode($str, true);

        $searchKey = Yii::$app->params['searchKey'][$dict] ?? NULL;
        if (!$searchKey) {
            throw new BadRequestHttpException('Missed required parameter: searchKey');
        }

        $filteredTerms = $term && $searchKey ? array_filter($terms[$lang], function($item) use ($term, $searchKey) {
            $str = $item[$searchKey];
            return mb_strpos($str, $term) !== false;
        }) : $terms;

        return [
            'terms' => array_slice($filteredTerms, $offset, $limit),
            'total' => count($filteredTerms),
        ];
    }
}
