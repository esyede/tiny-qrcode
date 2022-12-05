# TinyQRCode

![screenshot](screenshot.png)

A tiny QRCode generator (single file, no dependencies)


## Requirements:

  - PHP 5.4 or newer
  - PHP-GD exntension


## Install

Via composer:

```bash
composer require esyede/tiny-qrcode
```

Manual install:

  1. Download the archive from the [release page](https://githun.com/esyede/tiny-qrcode/releases/latest)
  2. Extract the downloaded archive file


### Load file

```php
require 'vendor/autoload.php'; // via composer

require 'path/to/TinyQRCode.php'; // manual
```

### Display image

```php
$data = 'https://github.com/esyede/tiny-qrcode';

$qr = new \Esyede\TinyQRCode\TinyQRCode($data);
$qr->display();
```

### Save image

```php
$data = 'https://github.com/esyede/tiny-qrcode';

$qr = new \Esyede\TinyQRCode\TinyQRCode($data);
$qr->store('path/to/store/qr.png');
```


### Adjust QRCode options

#### Altering the error correction level

```php
$data = 'https://github.com/esyede/tiny-qrcode';
$options = ['errorCorrectionLevel' => 'H'];

$qr = new \Esyede\TinyQRCode\TinyQRCode($data, $options);
$qr->display();
```

TinyQRCode supports 4 error correction levels:
  1. `'L'`: 7%  error level
  2. `'M'`: 15% error level (default)
  3. `'Q'`: 25% error level
  4. `'H'`: 30% error level


#### Changing the image size

The size of the generated QR image can be adjusted as follows.
This does not affect error correction or similar.

```php
$data = 'https://github.com/esyede/tiny-qrcode';
$options = ['imageSize' => 30];

$qr = new \Esyede\TinyQRCode\TinyQRCode($data, $options);
$qr->display();
```


#### Changing the code version

The version of the QR code can be altered by passing options to the constructor.
Note that in most cases this will result in an increase in image size.

```php
$data = 'https://github.com/esyede/tiny-qrcode';
$options = ['version' => 5];

$qr = new \Esyede\TinyQRCode\TinyQRCode($data, $options);
$qr->display();
```

## License

Released under the [MIT License](https://github.com/esyede/tiny-qrcode/LICENSE)
