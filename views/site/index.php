<?php
use yii\web\View;
/** 
 * @var View $this
 */
$this->title = Yii::$app->params['appTitle'];
?>
<div>
    <h3>API для ресурса кала-ха.рф</h3>
    <p>
        <b>Учебные пособия:</b>
    </p>
    <ul>
        <li><b>GET /api/books</b> - схема всех учебных пособий.</li>
        <li><b>GET /api/books/:id</b> - схема указанного учебного пособия. id - номер пособия.</li>
        <li><b>GET /api/books/:id/:chapter</b> - схема главы учебного пособия. id - номер пособия, chapter - номер главы.</li>
        <li><b>GET /api/books/:id/file/:name/:type</b> - файл указанного учебного пособия. id - номер пособия, name - имя файла, type - тип файла</li>
    </ul>
    <p>
        <b>Словарь:</b>
    </p>
    <ul>
        <li><b>GET /api/terms/:limit/:offset</b> - список терминов словаря</li>
        <li><b>GET /api/term/:term/:limit/:offset</b> - поиск по терминам словаря</li>
    </ul>
</div>
