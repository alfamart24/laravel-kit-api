
# Laravel TK kit api

Api для работы с ТК КИТ. Рализована работа калькулятора и многих других функций. По мере разработки буду обновлять документацию.<br>
По вопросам работы пишите в телеграмм WStanley

<br>


# Installation
```
composer require wstanley/kitapi:dev-master
```

<br>

# Usage

Добавляем токен в файл .env
```
TOKEN_KIT=
```

Подключаем сервис в контроллере:

```
use Wstanley\Kitapi\KitService;

$service = new KitService();
```

<br>
<br>


## Реализованные функции:

#### Получение списка городов - `geography/city/get-list`<br>
```
$service->cityGeography()->all()
```
<br>

#### Получение списка электронных адресов - `geography/email/get-list`<br>
```
$service->email()->all()
```
<br>

#### Получение списка телефонов - `geography/phone/get-list`<br>
```
$service->phone()->all()
```
<br>

#### Получение списка режимов работы - `geography/schedule/get-list`<br>
```
$service->schedule()->all()
```
<br>

#### Получение списка видов графиков работы - `geography/schedule-group/get-list`<br>
```
$service->scheduleGroup()->all()
```
<br>

#### Расчет стоимости - `order/calculate`<br>
```
$service->calculate(...)->all()
```
<br>

#### Получение списка валют - `order/currency/get-list`<br>
```
$service->currency()->all()
```
<br>

#### Список страховых агентов - `order/insurance/get-list`<br>
```
$service->insurance()->all()
```
<br>

#### Оформление заказа - `order/service/get-list`<br>
```
$service->service()->all()
```
<br>

#### Статус заказа - `order/status/get`<br>
```
$service->status()->all()
```
<br>

#### Список городов - `tdd/city/get-list`<br>
```
$service->cityTdd()->all()
```
<br>

#### Получение списка стран - `tdd/country/get-list`<br>
```
$service->country()->all()
```
<br>

#### Список регионов - `tdd/region/get-list`<br>
```
$service->region()->all()
```
<br>
<br>

## Примеры работы с функциями:

#### Список городов - `tdd/city/get-list`<br>

Чтобы получить весь список городов используем all().<br> all() используется для получения полного ответа от сервера.
```
$cyties = $service->cityTdd()->all()
```
Для отправки запроса передаем параметры в функцию
```
$cyti = $service->cityTdd(
                [
                    "code"         => "660002900000",
                    "region_code"  => "66",
                    "country_code" => "RU"
                ])->all()
```
Либо так
```
$cyti = $service->cityTdd(["code" => "660002900000"])->all()
```
В документации описано что тип принимаемого параметра "строка либо массив", но по факту если отправить "code" => ["660002900000", "660002900000"] то приходит ошибка 500. Поэтому либо получаем полный список, либо передаем строкой "code" => "660002900000".
<br>

Какие параметры принимает конкретная функция вы можете узнать из документации, либо в классе функции, в поле optional перечислены необязательные параметры, в поле  necessary перечислены обязательные параметры.

<br>

#### Список страховых агентов - `order/insurance/get-list`<br>

Чтобы получить весь ответ от сервера используем all()

```
$response = $service->insurance()->all()
```
В полном ответе от сервера содержатся типы данных `agent` и `cargo_type`<br>
Получение только типа `agent`
```
$agents = $service->insurance()->agent()
```
Получение типа `cargo_type`
```
$cargoType = $service->insurance()->cargo_type()
```
С детальной информацией об ответах от сервера вы можете ознакомиться в документации.

<br>
<br>

## Расчет стоимости перевозки

#### Расчет стоимости - `order/calculate`

#### Есть 2 варианта расчета стоимости:

### С указанием объема багажа `volume`
Получение полного ответа от сервера

```
$response = $service->calculate($request->all())->all();
```
Обязательные параметры для расчета:<br>
```
'city_pickup_code'      => 'Код города откуда',<br>
'city_delivery_code'    => 'Код города куда',<br>
'declared_price'        => 'Объявленная стоимость груза (руб)',<br>
'count_place'           => 'Количество мест в позиции',<br>
'weight'                => 'Масса КГ позиции',<br>
'volume'                => 'Объем М³ позиции',
```
Необязательные параметры, для более дтального расчета, также можно передать в функцию:
```
'have_doc'              => 'Есть документы подтверждающие стоимость груза',
'insurance'             => 'Услуга страхования груза',
'insurance_agent_code'  => 'Код страхового агента',
'service'               => 'массив кодов услуг',
'pick_up'               => 'Забор груза по городу',
'delivery'              => 'Доставка груза по городу',
'cargo_type_code'       => 'Код характера груза',
'currency_code'         => 'Валюта результата расчета',
'all_places_same'       => 'Все места одинаковы по размеру'
```
Параметры о месте багажа ожидаются такого формата:
```
"count_place" => [
    0 => "1"
    1 => "3"],
"weight" => [
    0 => "2"
    1 => "3"],
"volume" => [
    0 => "8"
    1 => "2"],
```
Пример поля `input` на форме калькулятора:
 ```
<input type="text" name="volume[]" placeholder="Объем">
 ```
 Также у функции `canculate` <u>есть зависимые параметры</u>, с полной работой функции вы можете ознакомиться в документации.<br>
 Функция не отправит запрос пока не передадут все требуемяе параметры для расчета стоимости, выпадающие исключения подскажут чего не хватает.
 <br>
 <br>

Получить стоимость перевозки можно так
```
$price = $response->standart()->cost;
$price = $response->economy()->cost;
$price = $response->express()->cost;
$price = $response->standard_courier()->cost;
$price = $response->express_courier()->cost;
```
Либо сразу так
```
$price = $service->calculate($request->all())->standart()->cost;
```

<br>

### С указанием размеров багажа `height`, `width`, `length`
В случае работы с размерами в функцию требуется передать флаг `false` вторым параметром, ианче расчет будет вестись как для объема. 

```
$response = $service->calculate($request->all(), false)->all();
```
Обязательные параметры для расчета:<br>
```
'city_pickup_code'      => 'Код города откуда',<br>
'city_delivery_code'    => 'Код города куда',<br>
'declared_price'        => 'Объявленная стоимость груза (руб)',<br>
'count_place'           => 'Количество мест в позиции',<br>
'weight'                => 'Масса КГ позиции',<br>
'height'                => 'Высота груза (см) позиции',
'width'                 => 'Ширина груза (см) позиции',
'length'                => 'Длина груза (см) позиции',
```
Необязательные параметры, для более дтального расчета, также можно передать в функцию:
```
'have_doc'              => 'Есть документы подтверждающие стоимость груза',
'insurance'             => 'Услуга страхования груза',
'insurance_agent_code'  => 'Код страхового агента',
'service'               => 'массив кодов услуг',
'pick_up'               => 'Забор груза по городу',
'delivery'              => 'Доставка груза по городу',
'cargo_type_code'       => 'Код характера груза',
'currency_code'         => 'Валюта результата расчета',
'all_places_same'       => 'Все места одинаковы по размеру'
```
Параметры о месте багажа ожидаются такого формата:
```
"count_place" => [
    0 => "1"
    1 => "3"],
"weight" => [
    0 => "2"
    1 => "3"],
"height" => [
    0 => "8"
    1 => "2"],
"width" => [
    0 => "4"
    1 => "6"],
"length" => [
    0 => "1"
    1 => "8"],
```
Пример поля `input` на форме калькулятора:
 ```
<input type="text" name="width[]" placeholder="Укажите ширину">
 ```
 Также у функции `canculate` <u>есть зависимые параметры</u>, с полной работой функции вы можете ознакомиться в документации.<br>
 Функция не отправит запрос пока не передадут все требуемяе параметры для расчета стоимости, выпадающие исключения подскажут чего не хватает.
  <br>
  <br>
 
 Получить стоимость перевозки можно так
 
 ```
 $price = $response->standart()->cost;
 $price = $response->economy()->cost;
 $price = $response->express()->cost;
 $price = $response->standard_courier()->cost;
 $price = $response->express_courier()->cost;
 ```
 Либо сразу так
 ```
 $price = $service->calculate($request->all(), false)->standart()->cost;
 ```