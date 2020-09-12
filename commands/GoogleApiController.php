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

    public function actionGetData()
    {
        $gapi = new GoogleApi();
        foreach (Yii::$app->params['spreadSheets'] ?? [] as $dictKey => $spreadSheet) {
            $data = [];
            foreach ($spreadSheet['spreadSheetRanges'] ?? [] as $langKey => $spreadSheetRange) {
                if (!$spreadSheet['spreadSheetId']) {
                    echo 'SpreadSheet ID missed.';
                    continue;
                }
                $rawData = $gapi->getSpreadsheet($spreadSheet['spreadSheetId'], $spreadSheetRange);
                $data[$langKey] = $rawData;
            }
            file_put_contents(Yii::getAlias('@data/terms-' . $dictKey . '.tmp.json'), json_encode($data, JSON_UNESCAPED_UNICODE));
            echo "Terms of {$dictKey} dictionary downloaded and saved to file!\n";
        }
    }

    public function actionPrepareData()
    {
        foreach (Yii::$app->params['spreadSheets'] ?? [] as $dictKey => $spreadSheet) {
            $fileName = Yii::getAlias('@data/terms-' . $dictKey . '.tmp.json');
            if (!file_exists($fileName)) {
                echo 'Data for dictionary "' . $dictKey . '" missed. Use "php yii google-api/get-data" for getting data from Google Drive.';
                continue;
            }
            $str = file_get_contents($fileName);
            $dictionariesData = json_decode($str, true);
            $data = [];
            foreach ($spreadSheet['spreadSheetRanges'] ?? [] as $langKey => $spreadSheetRange) {
                $rawData = $dictionariesData[$langKey] ?? [];
                $columns = $spreadSheet['spreadSheetColumns'];
                foreach($rawData ?? [] as $termKey => $term) {
                    $id = $termKey + 1;
                    if (!isset($data[$langKey])) {
                        $data[$langKey] = [];
                    }
                    $record = [
                        'id' => $id,
                    ];
                    if ($dictKey === 'kalaha') {
                        foreach ($columns ?? [] as $key => $value) {
                            $record[$value] = $term[$key] ?? '';
                        }
                        $data[$langKey][] = $record;
                    } else if ($dictKey === 'cv500') {
                        foreach ($columns ?? [] as $key => $value) {
                            if (in_array($value, ['term', 'transcription', 'translation'])) {
                                $record[$value] = trim($term[$key] ?? '');
                            } else if (in_array($value, ['examples'])) {
                                $examples = [];
                                foreach (explode(';', trim($term[$key] ?? '')) ?? [] as $example) {
                                    $parts = explode(' — ', $example);
                                    if (count($parts) === 2) {
                                        $examples[] = [
                                            'cv'     => trim($parts[0]),
                                            $langKey => trim($parts[1]),
                                        ];
                                    } else {
                                        echo "Language {$langKey}. Row {$termKey}. Check this: {$example}.\n";
                                    }
                                }
                                $record[$value] = $examples;
                            }
                        }
                        $data[$langKey][] = $record;
                    }
                }
            }
            file_put_contents(Yii::getAlias('@data/terms-' . $dictKey . '.json'), json_encode($data, JSON_UNESCAPED_UNICODE));
            echo "Terms file {$dictKey} prepared!\n";
        }
    }
}
