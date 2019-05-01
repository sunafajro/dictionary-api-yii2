<?php

namespace app\commands;

use Yii;
use app\models\GoogleApi;
use yii\console\Controller;

class GoogleApiController extends Controller
{
    public $message;
    
    public function options($actionID)
    {
        return ['message'];
    }
    
    public function actionIndex()
    {
        $gapi = new GoogleApi();
        $rawData = $gapi->getSpreadsheet(Yii::$app->params['spreadsheetId'], Yii::$app->params['spreadsheetRange']);
        $columns = Yii::$app->params['spreadsheetColumns'];
        $data = [];
        foreach($rawData ?? [] as $term) {
            $record = [];
            foreach($columns ?? [] as $key => $value) {
                $record[$value] = $term[$key] ?? '';
            }
            $data[] = $record;
        }
        file_put_contents(Yii::getAlias('@data/terms.json'), json_encode($data, JSON_UNESCAPED_UNICODE));
        echo "Terms file updated!\n";
    }
}