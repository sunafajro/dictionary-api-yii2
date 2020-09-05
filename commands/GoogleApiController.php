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
        foreach (Yii::$app->params['spreadSheets'] ?? [] as $dictKey => $spreadSheet) {
            $data = [];
            foreach ($spreadSheet['spreadSheetRanges'] ?? [] as $langKey => $spreadSheetRange) {
                if (!$spreadSheet['spreadSheetId']) {
                    echo 'SpreadSheet ID missed.';
                    continue;
                }
                $rawData = $gapi->getSpreadsheet($spreadSheet['spreadSheetId'], $spreadSheetRange);
                $columns = $spreadSheet['spreadSheetColumns'];
                foreach($rawData ?? [] as $termKey => $term) {
                    if ($dictKey === 'kalaha') {
                        $record = [];
                        foreach ($columns ?? [] as $key => $value) {
                            $record[$value] = $term[$key] ?? '';
                        }
                        $data[] = $record;
                    } else if ($dictKey === 'cv500') {
                        $id = $termKey + 1;
                        if (!isset($data[$langKey])) {
                            $data[$langKey] = [];
                        }
                        $record = [
                            'id' => $id,
                        ];
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
            echo "Terms file {$dictKey} updated!\n";
        }
    }
}
