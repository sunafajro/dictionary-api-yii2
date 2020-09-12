<?php

namespace app\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DictionaryController extends Controller
{
    /**
     * @return array
     */
    public function actionList() : array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            ['name' => 'kalaha', 'title' => 'Чувашско-русский словарь учебного пособия Кала-ха'],
            ['name' => 'cv500', 'title' => 'Словарь 500 основных чувашских корней'],
        ];
    }

    /**
     * @param string $dictionary
     * @param string $dictionary_language
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionListTerms(
        string $dictionary,
        string $dictionary_language = null,
        int $limit = null,
        int $offset = null
    )
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!in_array($dictionary, ['kalaha', 'cv500'])) {
            throw new NotFoundHttpException('Can\'t found dictionary: ' . $dictionary);
        }

        if (!$dictionary_language) {
            $dictionary_language = Yii::$app->params['dictionaryLanguage'][$dictionary] ?? null;
        }

        if (!$limit) {
            $limit = Yii::$app->params['limit'] ?? null;
        }

        if (!$offset) {
            $offset = Yii::$app->params['offset'] ?? null;
        }

        $str = file_get_contents(Yii::getAlias('@data/terms-' . $dictionary . '.json'));
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

    public function actionSearchTerms(
        string $dictionary,
        string $term,
        string $dictionary_language = null,
        string $search_language = null,
        int $limit = null,
        int $offset = null
    )
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!in_array($dictionary, ['kalaha', 'cv500'])) {
            throw new NotFoundHttpException('Can\'t found dictionary: ' . $dictionary);
        }

        if (!$dictionary_language) {
            $dictionary_language = Yii::$app->params['dictionaryLanguage'] ?? null;
        }

        if (!$search_language) {
            $search_language = Yii::$app->params['searchLanguage'] ?? null;
        }

        if (!$limit) {
            $limit = Yii::$app->params['limit'] ?? null;
        }

        if (!$offset) {
            $offset = Yii::$app->params['offset'] ?? null;
        }

        $str = file_get_contents(Yii::getAlias('@data/terms-' . $dictionary . '.json'));
        $terms = json_decode($str, true);

        if (!isset($terms[$dictionary_language])) {
            $terms[$dictionary_language] = [];
        }

        $searchKey = Yii::$app->params['searchColumn'][$dictionary] ?? NULL;
        if (!$searchKey) {
            throw new BadRequestHttpException('Missed required parameter: searchKey');
        }

        $filteredTerms = $term && $searchKey ? array_filter($terms[$dictionary], function($item) use ($term, $searchKey) {
            $str = $item[$searchKey];
            return mb_strpos($str, $term) !== false;
        }) : $terms;

        return [
            'terms' => array_slice($filteredTerms, $offset, $limit),
            'total' => count($filteredTerms),
        ];
    }
}
