# Простейший api словаря
Термины словаря хранятся в /data/terms.json.

## Установка
```
    php composer install
```

Добавить параметры выборки и ключ поиска в config/params-web.php

## Импорт терминов из Google Speadsheet:
1. Добавить config/credentials.json
2. Добавить диапазон значений таблицы, идентификатор таблицы и ключи столбцов в config/params-console.php
3. Вызвать команду
```
    php yii google-api
```
При первом запуске будет сгенерирован token.json,
при повторном запуске - скачивание и запись терминов в файл.

# API
/api/term/index - возвращает список терминов

доступные параметры
  - limit, количество терминов
  - offset, смещение относительно начала массива
  - search, поиск по термину