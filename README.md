Превью доступно по ссылке:
http://croproppej.temp.swtest.ru/

# Описание проекта

Проект представляет собой систему управления товарами для интернет-магазина. Система позволяет:

- Управлять категориями товаров
- Добавлять, редактировать и удалять товары
- Импортировать товары из Excel файла
- Загружать фотографии товаров и привязывать их к товарам

## Структура базы данных

### Таблицы

#### goods_category

- `id` (INT, Primary Key, Auto Increment) — идентификатор категории
- `name` (VARCHAR) — название категории
- `alias` (VARCHAR) — алиас категории для URL

#### goods

- `id` (INT, Primary Key, Auto Increment) — идентификатор товара
- `name` (VARCHAR) — название товара
- `alias` (VARCHAR) — алиас товара для URL
- `article` (VARCHAR) — артикул товара (уникальный)
- `price` (DECIMAL) — цена товара
- `quantity` (INT) — количество товара на складе
- `category_id` (INT, Foreign Key) — идентификатор категории

#### goods_photos

- `id` (INT, Primary Key, Auto Increment) — идентификатор фотографии
- `goods_id` (INT, Foreign Key) — идентификатор товара
- `photo_url` (VARCHAR) — URL фотографии товара

### Связи между таблицами

Связи между таблицами обеспечивают целостность данных и позволяют выполнять сложные запросы для получения информации о товарах и их категориях.

- Таблица `goods` связана с таблицей `goods_category` по полю `category_id`.
- Таблица `goods_photos` связана с таблицей `goods` по полю `goods_id`.

### Индексы

Индексы используются для ускорения поиска данных в таблицах. Основные индексы в проекте:

- Первичные ключи (`id`) в таблицах `goods`, `goods_category`, `goods_photos`.
- Уникальный индекс на поле `article` в таблице `goods` для обеспечения уникальности артикулов.

## Функциональность

### Административная часть

#### Список товаров:

- Отображение товаров с основной информацией: фото, название, категория, цена, количество.
- Сортировка по полям (название, цена, категория, количество).
- Постраничная разбивка списка товаров.

#### CRUD операции с товарами:

- Создание, редактирование, удаление товаров.
- Загрузка фотографий товаров или указание URL.

#### Импорт товаров из файла Excel:

- Импорт товаров с указанием всех необходимых данных, включая фотографии.
- Обновление существующих товаров или добавление новых.

### Пользовательская часть

- Отображение карточки товара с основной информацией (фото, название, категория, цена, кол-во).

- Постраничная навигация с возможностью перехода на конкретную страницу.

