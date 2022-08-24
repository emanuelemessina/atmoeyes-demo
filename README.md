# AtmoEyes Demo

<br>

## What is this?

This repo contains all the code used for the demo
 presented by _Group A_ of UNIPA (Università degli Studi di Palermo)
for "Samsung Innovation Campus 2021 Smart Things Edition" in Palermo. 
Eventually 3 of the 4 members of _Group A (atmoeyes)_ won the edition.

<br>

Below are developement notes.

<br>

---

<br>

## Routes

<br>

### `/frontend`
| URI | Method | Description |
| --- | --- | --- |
| / | GET | Live Map page |

### `/backend`
| URI | Method | Description |
| --- | --- | --- |
| /data/aqi | GET | Returns GeoJSON aqi data layer
| /data/aqi/send | POST | Send sensor data to aqi data layer (**CURRENTLY UNPROTECTED**)

<br>

## Data

<br>

#### AQI Data - Post Body

| Value | Lon | Lat |
|--|--|--|
| Int | Float | Float |


<br>


### GeoJSON Structure

<br>

##### Tool: [Edit GeoJSON Data](https://geojson.io/#map=15/38.1004/13.3392)

```json
{
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "properties": {
                "aqi": 7
            },
            "geometry": {
                "type": "Point",
                "coordinates": [-30, 10]
            }
        }, 
    ...
}
```
<br>

## Frontend code maintenance

<br>

Specify project specific parameters in `.atmoeyes`
```json
{
    "backendUrl": "https://emanuelemessina.altervista.org/uni/ssmic2021/atmoeyes/backend"
}
```

<br> 

## Backend code maintenance

<br> 

### Routing Flow

1. Calls to `/backend` are redirected to `kernel.php` via `.htaccess` RewriteRule.
2. `kernel.php` - calls db, calls router.
3. `/db.php` - initializes db connection, declares db methods.
3. `/router/router.php` - declares route matching methods, calls `routes.php`.
4. `/router/routes.php` - sets `application/json` response header, calls declared routes, which are then parsed by the router. If no match is found, 404 is thrown.
5. When a match is found, execution is passed to the matched route controller.


<br>

### Examples

<br>

#### Update a record with QuickQuery
```php
$input_schema = [
    "id" => "int",
    "value" => "int"
];

$qq = new QuickQuery('json-input', $input_schema);
$validated_data = $qq->getValidatedData();
$response = $qq->update(
    'data', // table
    ['value'], // fields to update
    [ // where clause
        'id' => $validated_data['id'] 
    ]);

echo json_encode($response);
```
Validation is automatically done and all errors are reported in the response. <br>
Table names are already prefixed with `'atmoeyes_'`.

<br>

#### Declaring routes in `routes.php`
```php 
get('/data/aqi/$optional_get_param', 'aqi-data.php'); 
```
- Controller files are placed under `/controllers`.
- Optional GET parameters are accessible in the controller via $parameters array.

<br>

## Docs
---
<br>

### Info
[CAQI](https://airly.org/en/air-quality-index-caqi-and-aqi-methods-of-calculation/)

### Mapbox

[Heatmap](https://docs.mapbox.com/mapbox-gl-js/example/heatmap-layer/)

[Live Data Updates](https://docs.mapbox.com/mapbox-gl-js/example/live-geojson/)

### PHP

[PHP Router](https://phprouter.com/)

[Medoo](https://medoo.in/api/new)

### Firebase

[Get Started](https://firebase.google.com/docs/hosting/quickstart)
