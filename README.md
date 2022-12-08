# tiny-qrcode

![screenshot](screenshot.png)

A tiny QR Code generator (single file, no dependencies)


## Requirements:

  - PHP 5.4 or newer
  - PHP-GD exntension


## Install

Via composer:

```bash
composer require esyede/tiny-qrcode
```

Manual install:

  1. Download the archive from the [release page](https://github.com/esyede/tiny-qrcode/releases/latest)
  2. Extract the downloaded archive file


## Load file

```php
require 'vendor/autoload.php'; // via composer

require 'path/to/TinyQRCode.php'; // manual
```

## Display image

```php
$data = 'https://github.com/esyede/tiny-qrcode';

$qr = new \Esyede\TinyQRCode\TinyQRCode($data);
$qr->display();
```

## Save image

```php
$data = 'https://github.com/esyede/tiny-qrcode';

$qr = new \Esyede\TinyQRCode\TinyQRCode($data);
$qr->store('path/to/store/qr.png');
```


## Adjust QRCode options

### Altering the error correction level

```php
$data = 'https://github.com/esyede/tiny-qrcode';
$options = ['errorCorrectionLevel' => 'H'];

$qr = new \Esyede\TinyQRCode\TinyQRCode($data, $options);
$qr->display();
```
**Available ERROR correction levels:**

| Code | Correction Level |
|------|------------------|
| `L`  | 7%               |
| `M`  | 15% (default)    |
| `Q`  | 25%              |
| `H`  | 30%              |



### Changing the image size

The size of the generated QR image can be adjusted as follows.
This does not affect error correction or similar.

```php
$data = 'https://github.com/esyede/tiny-qrcode';
$options = ['imageSize' => 30];

$qr = new \Esyede\TinyQRCode\TinyQRCode($data, $options);
$qr->display();
```


### Changing the code version

The version of the QR code can be altered by passing options to the constructor.
Note that in most cases this will result in an increase in image size.

```php
$data = 'https://github.com/esyede/tiny-qrcode';
$options = ['version' => 5]; // range: 1 - 40

$qr = new \Esyede\TinyQRCode\TinyQRCode($data, $options);
$qr->display();
```


## Possible option lists:

| Key                      | Type    | Description                   |
|--------------------------|---------|-------------------------------|
| `'version'`              | integer | Set QR Code version           |
| `'imageSize'`            | integer | Set output image size         |
| `'errorCorrectionLevel'` | string  | Set error correction level    |
| `'dataPath'`             | string  | Set custom stub data folder   |
| `'imagesPath'`           | string  | Set custom stub images folder |



## License

Released under the [MIT License](https://github.com/esyede/tiny-qrcode/LICENSE)
