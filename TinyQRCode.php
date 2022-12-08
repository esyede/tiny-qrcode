<?php

namespace Esyede\TinyQRCode;

class TinyQRCode
{
    /**
     * Path to the stubs data directory.
     *
     * @var string
     */
    protected $dataPath;

    /**
     * Path to the stubs images directory.
     *
     * @var string
     */
    protected $imagesPath;

    /**
     * Error correction.
     *
     * @var int
     */
    protected $errorCorrection = 0;

    /**
     * Version of QRCode to use.
     *
     * @var int
     */
    protected $version;

    /**
     * Upper limit for version.
     *
     * @var int
     */
    protected $maxVersion;

    /**
     * String / data to encode.
     *
     * @var string
     */
    protected $dataString;

    /**
     * Image size.
     *
     * @var int
     */
    protected $resultSize = 0;

    /**
     * Base image resource.
     *
     * @var resource
     */
    protected $baseImage;

    /**
     * Output image resource.
     *
     * @var resource
     */
    protected $outputImage;

    /**
     * Total number of data bits.
     *
     * @var int
     */
    protected $totalDataBits;

    /**
     * Error correction level indicator.
     *
     * @var string
     */
    protected $errorCorrectionLevel;

    /**
     * Maximum number of data bits.
     *
     * @var int
     */
    protected $maxDataBits;

    /**
     * Size of the resulted image.
     *
     * @var int
     */
    protected $imageSize;

    /**
     * Data values.
     *
     * @var array
     */
    protected $dataValues = [];

    /**
     * Data length.
     *
     * @var int
     */
    protected $dataLength = 0;

    /**
     * Incremental counter, pointer into the data array.
     *
     * @var int
     */
    protected $dataCounter;

    /**
     * Data bits array.
     *
     * @var array
     */
    protected $dataBits = [];

    /**
     * Codeword pointer, incremental.
     *
     * @var int
     */
    protected $codewordNumCounterValue;

    /**
     * Codeword details.
     *
     * @var array
     */
    protected $codewordNumPlus = [];

    /**
     * RS-ECC codewords.
     *
     * @var string
     */
    protected $rsEccCodewords;

    /**
     * Codewords calculated prior to matrix generation.
     *
     * @var array
     */
    protected $codewords = [];

    /**
     * Maximum number of data codewords.
     *
     * @var int
     */
    protected $maxDataCodewords;

    /**
     * Final matrix details for plotting barcode.
     *
     * @var array
     */
    protected $matrixContents = [];

    /**
     * Matrix X array.
     *
     * @var array
     */
    protected $matrixListX = [];

    /**
     * Matrix Y array.
     *
     * @var array
     */
    protected $matrixListY = [];

    /**
     * Masking array - used in final matrix generation.
     *
     * @var array
     */
    protected $maskLists = [];

    /**
     * RS Block order array.
     *
     * @var array
     */
    protected $rsBlockOrder = [];

    /**
     * RS Calculation table array.
     *
     * @var array
     */
    protected $rsCalTableLists = [];

    /**
     * Byte number counter.
     *
     * @var int
     */
    protected $byteNum;

    /**
     * Lookup table for alphanumeric characters.
     *
     * @var array
     */
    protected $alphaNumericHash = [
        '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4,
        '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, 'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14,
        'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23,
        'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29, 'U' => 30, 'V' => 31,
        'W' => 32, 'X' => 33, 'Y' => 34, 'Z' => 35, ' ' => 36, '$' => 37, '%' => 38, '*' => 39,
        '+' => 40, '-' => 41, '.' => 42, '/' => 43, ':' => 44,
    ];

    /**
     * Maximum data bits lookup.
     *
     * @var array
     */
    protected $maxDataBitLists = [
        0, 128, 224, 352, 512, 688, 864, 992, 1232, 1456, 1728,
        2032, 2320, 2672, 2920, 3320, 3624, 4056, 4504, 5016, 5352,
        5712, 6256, 6880, 7312, 8000, 8496, 9024, 9544, 10136, 10984,
        11640, 12328, 13048, 13800, 14496, 15312, 15936, 16816, 17728, 18672,

        152, 272, 440, 640, 864, 1088, 1248, 1552, 1856, 2192,
        2592, 2960, 3424, 3688, 4184, 4712, 5176, 5768, 6360, 6888,
        7456, 8048, 8752, 9392, 10208, 10960, 11744, 12248, 13048, 13880,
        14744, 15640, 16568, 17528, 18448, 19472, 20528, 21616, 22496, 23648,

        72, 128, 208, 288, 368, 480, 528, 688, 800, 976,
        1120, 1264, 1440, 1576, 1784, 2024, 2264, 2504, 2728, 3080,
        3248, 3536, 3712, 4112, 4304, 4768, 5024, 5288, 5608, 5960,
        6344, 6760, 7208, 7688, 7888, 8432, 8768, 9136, 9776, 10208,

        104, 176, 272, 384, 496, 608, 704, 880, 1056, 1232,
        1440, 1648, 1952, 2088, 2360, 2600, 2936, 3176, 3560, 3880,
        4096, 4544, 4912, 5312, 5744, 6032, 6464, 6968, 7288, 7880,
        8264, 8920, 9368, 9848, 10288, 10832, 11408, 12016, 12656, 13328,
    ];

    /**
     * Maximum number of codewords, dependant on barcode version.
     *
     * @var array
     */
    protected $maxCodewordLists = [
        0, 26, 44, 70, 100, 134, 172, 196, 242,
        292, 346, 404, 466, 532, 581, 655, 733, 815, 901, 991, 1085, 1156,
        1258, 1364, 1474, 1588, 1706, 1828, 1921, 2051, 2185, 2323, 2465,
        2611, 2761, 2876, 3034, 3196, 3362, 3532, 3706,
    ];

    /**
     * Formatting data for the final barcode.
     *
     * @var array
     */
    protected $formatLists = [
        '101010000010010', '101000100100101',
        '101111001111100', '101101101001011', '100010111111001', '100000011001110',
        '100111110010111', '100101010100000', '111011111000100', '111001011110011',
        '111110110101010', '111100010011101', '110011000101111', '110001100011000',
        '110110001000001', '110100101110110', '001011010001001', '001001110111110',
        '001110011100111', '001100111010000', '000011101100010', '000001001010101',
        '000110100001100', '000100000111011', '011010101011111', '011000001101000',
        '011111100110001', '011101000000110', '010010010110100', '010000110000011',
        '010111011011010', '010101111101101',
    ];

    /**
     * Class constructor.
     *
     * @param string $data
     * @param array  $options
     */
    public function __construct($data, array $options = [])
    {
        $this->dataPath = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'data';
        $this->imagesPath = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'images';
        $this->version = 0;
        $this->maxVersion = 40;
        $this->dataString = '';
        $this->totalDataBits = 0;
        $this->errorCorrectionLevel = 'M';
        $this->imageSize = 0;
        $this->matrixContents = [];

        if (array_key_exists('version', $options)) {
            $this->setVersion($options['version']);
        }

        if (array_key_exists('imageSize', $options)) {
            $this->setImageSize($options['imageSize']);
        }

        if (array_key_exists('errorCorrectionLevel', $options)) {
            $this->setErrorCorrectionLevel($options['errorCorrectionLevel']);
        }

        if (array_key_exists('dataPath', $options)) {
            $this->setDataPath($options['dataPath']);
        }

        if (array_key_exists('imagesPath', $options)) {
            $this->setImagesPath($options['imagesPath']);
        }

        $this->setData($data);
        $this->generate();
    }

    /**
     * Set the data path.
     *
     * @param string $dataPath
     *
     * @return void
     */
    protected function setDataPath($dataPath)
    {
        $dataPath = trim($dataPath);

        if (! is_dir($dataPath) || ! is_readable($dataPath)) {
            throw new \Exception(sprintf(
                'Data path should be a readable directory: %s',
                $dataPath
            ));
        }

        $this->dataPath = $dataPath;
    }

    /**
     * Set the images path.
     *
     * @param string $imagesPath
     *
     * @return void
     */
    protected function setImagesPath($imagesPath)
    {
        $imagesPath = trim($imagesPath);

        if (! is_dir($imagesPath) || ! is_readable($imagesPath)) {
            throw new \Exception(sprintf(
                'Images path should be a readable directory: %s',
                $imagesPath
            ));
        }

        $this->imagesPath = $imagesPath;
    }

    /**
     * Set the data to encode.
     *
     * @param string $str
     *
     * @return void
     */
    protected function setData($str)
    {
        $str = trim($str);

        if (strlen($str) === 0) {
            throw new \Exception('Data cannot be empty');
        }

        $this->dataString = $str;
        $this->dataLength = strlen($str);
    }


    /**
     * Set the error correction level (L: 7%, M: 15%, Q: 25%, H: 30%).
     *
     * @param string $levelCode
     *
     * @return void
     */
    protected function setErrorCorrectionLevel($levelCode)
    {
        $levelCode = strtoupper(trim($levelCode));
        $allowed = ['L', 'M', 'Q', 'H'];

        if (! in_array($levelCode, $allowed)) {
            throw new \Exception(sprintf(
                'Error correction level should be one of %s, got %s (%s)',
                implode(', ', $allowed), $levelCode, gettype($levelCode)
            ));
        }

        $this->errorCorrectionLevel = $levelCode;
    }

    /**
     * Set the default image size (defaults are PNG: 4, JPEG: 8).
     *
     * @param int $size
     *
     * @return void
     */
    protected function setImageSize($size)
    {
        $size = (int) $size;

        if ($size < 1) {
            throw new \Exception(sprintf(
                'Image size should be greater than 0, got %s (%s)',
                $size, gettype($size)
            ));
        }

        $this->imageSize = $size;
    }

    /**
     * Set the version number to use (between 1 and 40).
     * Version 1 is 21*21 matrix and 4 images increases whenever 1 version increases.
     * So version 40 is 177*177 matrix.
     *
     * @param int $version
     *
     * @return void
     */
    protected function setVersion($version)
    {
        $version = (int) $version;

        if ($version < 1 || $version > $this->maxVersion) {
            throw new \Exception(sprintf(
                'QR code version should be between 1 to %s, got %s (%s)',
                $this->maxVersion, $version, gettype($version)
            ));
        }

        $this->version = $version;
    }

    /**
     * Performs all necessary calculations and returns an image.
     *
     * @param string $data
     * @param array  $options
     *
     * @return resource
     */
    protected function generate()
    {
        $this->imageSize = ($this->imageSize <= 0) ? 4 : $this->imageSize;
        $this->dataCounter = 0;
        $this->dataBits[$this->dataCounter] = 4;
        $this->determineEncoding();

        if (isset($this->dataBits[$this->dataCounter]) && $this->dataBits[$this->dataCounter] > 0) {
            $this->dataCounter++;
        }

        $this->totalDataBits = 0;
        $i = 0;

        while ($i < $this->dataCounter) {
            $this->totalDataBits += $this->dataBits[$i];
            $i++;
        }

        $eccChars = ['L' => '1', 'l' => '1', 'M' => '0', 'm' => '0', 'Q' => '3', 'q' => '3', 'H' => '2', 'h' => '2'];
        $this->errorCorrection = isset($eccChars[$this->errorCorrectionLevel]) ? $eccChars[$this->errorCorrectionLevel] : 0;
        $this->checkVersion();
        $this->totalDataBits += $this->codewordNumPlus[$this->version];
        $this->dataBits[$this->codewordNumCounterValue] += $this->codewordNumPlus[$this->version];

        $maxCodewords = $this->maxCodewordLists[$this->version];
        $maxImageOneside = 17 + ($this->version << 2);
        $matrixRemainingBit = [
            0, 0, 7, 7, 7, 7, 7, 0, 0, 0, 0, 0, 0, 0, 3, 3, 3, 3, 3, 3, 3,
            4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 3, 3, 3, 3, 0, 0, 0, 0, 0, 0,
        ];

        $this->emptyMatrix($maxImageOneside);

        $formatInfo = $this->performEccOperation($matrixRemainingBit, $maxCodewords);
        $this->attachCodewordData($maxCodewords, $matrixRemainingBit);

        $maskNumber = $this->maskSelection($maxImageOneside);
        $this->calculateFormatInformation($formatInfo, $maskNumber);

        $mib = $this->createBaseImage($maxImageOneside);
        $this->addMatrixToImage($maxImageOneside, 1 << $maskNumber);
        imagecopyresized($this->outputImage, $this->baseImage, 0, 0, 0, 0, $this->resultSize, $this->resultSize, $mib, $mib);

        return $this;
    }

    /**
     * Performs all necessary calculations and outputs an image.
     *
     * @return void
     */
    public function display()
    {
        $image = imagepng($this->outputImage);

        if (is_resource($this->outputImage)) {
            imagedestroy($this->outputImage);
        }

        if (is_resource($image)) {
            imagedestroy($image);
        }

        $this->outputImage = null;
        $image = null;

        header('Content-type: image/png');
        echo $image;
    }

    public function store($filePath)
    {
        $image = imagepng($this->outputImage, $filePath);

        if (is_resource($this->outputImage)) {
            imagedestroy($this->outputImage);
        }

        if (is_resource($image)) {
            imagedestroy($image);
        }

        $this->outputImage = null;
        $image = null;

        return true;
    }

    /**
     * Performs version checking / calculation.
     *
     * @return void
     */
    protected function checkVersion()
    {
        if (! isset($this->version) || ! is_numeric($this->version)) {
            $this->version = 0;
        }

        if (! $this->version) {
            $i = 1 + 40 * $this->errorCorrection;
            $j = $i + 39;
            $this->version = 1;

            while ($i <= $j) {
                $cw = $this->codewordNumPlus[$this->version];
                $max = $this->maxDataBitLists[$i];
                $tdb = $this->totalDataBits + $cw;

                if ($max >= $tdb) {
                    $this->maxDataBits = $this->maxDataBitLists[$i];
                    break;
                }

                $i++;
                $this->version++;
            }
        } else {
            $bits = $this->maxDataBitLists[$this->version + (40 * $this->errorCorrection)];
            $this->maxDataBits = $bits;
        }

        if ($this->version > $this->maxVersion) {
            throw new \Exception('Version number is too large');
        }
    }

    /**
     * Determines the encoding needed for the data provided.
     *
     * @return void
     */
    protected function determineEncoding()
    {
        if (false !== preg_match('/[^0-9]/', $this->dataString)) {
            if (false !== preg_match('/[^0-9A-Z \$\*\%\+\.\/\:\-]/', $this->dataString)) {
                // 8-bit byte mode
                $this->codewordNumPlus = [
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                    8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8,
                    8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8,
                ];

                $this->dataValues[$this->dataCounter] = 4;
                $this->dataCounter++;
                $this->dataValues[$this->dataCounter] = $this->dataLength;
                $this->dataBits[$this->dataCounter] = 8; // version 1 - 9
                $this->codewordNumCounterValue = $this->dataCounter;

                $this->dataCounter++;
                $i = 0;

                while ($i < $this->dataLength) {
                    $this->dataValues[$this->dataCounter] = ord(substr($this->dataString, $i, 1));
                    $this->dataBits[$this->dataCounter] = 8;
                    $this->dataCounter++;
                    $i++;
                }
            } else {
                // alphanumeric mode
                $this->codewordNumPlus = [
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                    2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                    4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4,
                ];

                $this->dataValues[$this->dataCounter] = 2;
                $this->dataCounter++;
                $this->dataValues[$this->dataCounter] = $this->dataLength;
                $this->dataBits[$this->dataCounter] = 9; // version 1 - 9
                $this->codewordNumCounterValue = $this->dataCounter;

                $this->dataCounter++;
                $i = 0;

                while ($i < $this->dataLength) {
                    if (($i % 2) === 0) {
                        $c = substr($this->dataString, $i, 1);
                        $h = $this->alphaNumericHash[$c];
                        $this->dataValues[$this->dataCounter] = $h;
                        $this->dataBits[$this->dataCounter] = 6;
                    } else {
                        $c = substr($this->dataString, $i, 1);
                        $h = $this->alphaNumericHash[$c];
                        $this->dataValues[$this->dataCounter] = ($this->dataValues[$this->dataCounter] * 45) + $h;
                        $this->dataBits[$this->dataCounter] = 11;
                        $this->dataCounter++;
                    }

                    $i++;
                }
            }
        } else {
            // numeric mode
            $this->codewordNumPlus = [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4,
            ];

            $this->dataValues[$this->dataCounter] = 1;
            $this->dataCounter++;
            $this->dataValues[$this->dataCounter] = $this->dataLength;
            $this->dataBits[$this->dataCounter] = 10; // version 1 - 9
            $this->codewordNumCounterValue = $this->dataCounter;

            $this->dataCounter++;
            $i = 0;

            while ($i < $this->dataLength) {
                if (($i % 3) === 0) {
                    $this->dataValues[$this->dataCounter] = substr($this->dataString, $i, 1);
                    $this->dataBits[$this->dataCounter] = 4;
                } else {
                    $c = substr($this->dataString, $i, 1);
                    $this->dataValues[$this->dataCounter] = ($this->dataValues[$this->dataCounter] * 10) + $c;

                    if (($i % 3) === 1) {
                        $this->dataBits[$this->dataCounter] = 7;
                    } else {
                        $this->dataBits[$this->dataCounter] = 10;
                        $this->dataCounter++;
                    }
                }

                $i++;
            }
        }
    }

    /**
     * Performs the error correction and code generation operations.
     *
     * @param array $matrixRemainingBit
     * @param int   $maxCodewords
     *
     * @return array
     */
    protected function performEccOperation(array $matrixRemainingBit, $maxCodewords)
    {
        $formatInfo = $this->readEccData($matrixRemainingBit, $maxCodewords);
        $codewordsCounter = $this->setPaddingCharacter($this->divideBy8Bit());
        $this->doRsCalculations();

        return $formatInfo;
    }

    /**
     * Read in correct baseline data from ECC/RS files.
     *
     * @param array $matrixRemainingBit
     * @param int   $maxCodewords
     *
     * @return array
     */
    protected function readEccData($matrixRemainingBit, $maxCodewords)
    {
        $this->byteNum = $matrixRemainingBit[$this->version] + ($maxCodewords << 3);
        $file = $this->dataPath.DIRECTORY_SEPARATOR.'qrv'.$this->version.'_'.$this->errorCorrection.'.dat';

        if (! is_file($file)) {
            throw new \Exception('Cannot open ECC data file');
        }

        $fp1 = fopen($file, 'rb');
        $matrixX = fread($fp1, $this->byteNum);
        $matrixY = fread($fp1, $this->byteNum);
        $masks = fread($fp1, $this->byteNum);
        $formatInfoX = fread($fp1, 15);
        $formatInfoY = fread($fp1, 15);
        $this->rsEccCodewords = ord(fread($fp1, 1));
        $rso = fread($fp1, 128);
        fclose($fp1);

        $this->matrixListX = unpack('C*', $matrixX);
        $this->matrixListY = unpack('C*', $matrixY);
        $this->maskLists = unpack('C*', $masks);
        $this->rsBlockOrder = unpack('C*', $rso);
        $formatInfo = [
            'x1' => [0, 1, 2, 3, 4, 5, 7, 8, 8, 8, 8, 8, 8, 8, 8],
            'x2' => unpack('C*', $formatInfoX),
            'y1' => [8, 8, 8, 8, 8, 8, 8, 8, 7, 5, 4, 3, 2, 1, 0],
            'y2' => unpack('C*', $formatInfoY),
        ];

        $this->maxDataCodewords = ($this->maxDataBits >> 3);
        $file = $this->dataPath.DIRECTORY_SEPARATOR.'rsc'.$this->rsEccCodewords.'.dat';

        if (! is_file($file)) {
            throw new \Exception('Cannot open rsc data file');
        }

        $fp0 = fopen($file, 'rb');
        $i = 0;

        while ($i < 256) {
            $this->rsCalTableLists[$i] = fread($fp0, $this->rsEccCodewords);
            $i++;
        }

        fclose($fp0);

        if ($this->totalDataBits <= $this->maxDataBits - 4) {
            $this->dataValues[$this->dataCounter] = 0;
            $this->dataBits[$this->dataCounter] = 4;
        } else {
            if ($this->totalDataBits < $this->maxDataBits) {
                $this->dataValues[$this->dataCounter] = 0;
                $this->dataBits[$this->dataCounter] = $this->maxDataBits - $this->totalDataBits;
            } else {
                if ($this->totalDataBits > $this->maxDataBits) {
                    throw new \Exception('Overflow exception');
                }
            }
        }

        return $formatInfo;
    }

    /**
     * Gets data into 8-bit format.
     *
     * @return int
     */
    protected function divideBy8Bit()
    {
        $counter = 0;
        $this->codewords[0] = 0;
        $remaining = 8;
        $i = 0;

        while ($i <= $this->dataCounter) {
            $buffer = isset($this->dataValues[$i]) ? $this->dataValues[$i] : 0;
            $bufferBits = isset($this->dataBits[$i]) ? $this->dataBits[$i] : 0;
            $flag = 1;

            while ($flag) {
                if ($remaining > $bufferBits) {
                    $this->codewords[$counter] = isset($this->codewords[$counter]) ? $this->codewords[$counter] : 0;
                    $this->codewords[$counter] = (($this->codewords[$counter] << $bufferBits) | $buffer);
                    $remaining -= $bufferBits;
                    $flag = 0;
                } else {
                    $bufferBits -= $remaining;
                    $this->codewords[$counter] = (($this->codewords[$counter] << $remaining) | ($buffer >> $bufferBits));

                    if ($bufferBits === 0) {
                        $flag = 0;
                    } else {
                        $buffer = ($buffer & ((1 << $bufferBits) - 1));
                        $flag = 1;
                    }

                    $counter++;

                    if ($counter < $this->maxDataCodewords - 1) {
                        $this->codewords[$counter] = 0;
                    }

                    $remaining = 8;
                }
            }

            $i++;
        }

        if ($remaining !== 8) {
            $this->codewords[$counter] = $this->codewords[$counter] << $remaining;
        } else {
            $counter--;
        }

        return $counter;
    }

    /**
     * Sets the padding character to pad out data.
     *
     * @param int $codewordsCounter
     *
     * @return int
     */
    protected function setPaddingCharacter($codewordsCounter)
    {
        if ($codewordsCounter < ($this->maxDataCodewords - 1)) {
            $flag = 1;

            while ($codewordsCounter < ($this->maxDataCodewords - 1)) {
                $codewordsCounter++;
                $this->codewords[$codewordsCounter] = ($flag === 1) ? 236 : 17;
                $flag = $flag * -1;
            }
        }

        return $codewordsCounter;
    }

    /**
     * Calculates the format information for the barcode.
     *
     * @param array $formatInfo
     * @param int   $maskNumber
     *
     * @return void
     */
    protected function calculateFormatInformation($formatInfo, $maskNumber)
    {
        $formatValue = (($this->errorCorrection << 3) | $maskNumber);
        $i = 0;

        while ($i < 15) {
            $content = substr($this->formatLists[$formatValue], $i, 1);
            $this->matrixContents[$formatInfo['x1'][$i]][$formatInfo['y1'][$i]] = $content * 255;
            $this->matrixContents[$formatInfo['x2'][$i + 1]][$formatInfo['y2'][$i + 1]] = $content * 255;
            $i++;
        }
    }

    /**
     * Perform the actual RS calculations on the data.
     *
     * @return void
     */
    protected function doRsCalculations()
    {
        $rsBlockNumber = 0;
        $rsTemp[0] = '';
        $i = 0;
        $j = 0;

        while ($i < $this->maxDataCodewords) {
            $rsTemp[$rsBlockNumber] .= chr($this->codewords[$i]);
            $j++;
            $v = $this->rsBlockOrder[$rsBlockNumber + 1] - $this->rsEccCodewords;

            if ($j >= $v) {
                $j = 0;
                $rsBlockNumber++;
                $rsTemp[$rsBlockNumber] = '';
            }

            $i++;
        }

        $rsBlockNumber = 0;
        $rsBlockOrderNum = count($this->rsBlockOrder);

        while ($rsBlockNumber < $rsBlockOrderNum) {
            $rsCodewords = $this->rsBlockOrder[$rsBlockNumber + 1];
            $rsDataCodewords = $rsCodewords - $this->rsEccCodewords;
            $temp = $rsTemp[$rsBlockNumber].str_repeat(chr(0), $this->rsEccCodewords);
            $paddingData = str_repeat(chr(0), $rsDataCodewords);
            $j = $rsDataCodewords;

            while ($j > 0) {
                $first = ord(substr($temp, 0, 1));

                if ($first) {
                    $leftChar = substr($temp, 1);
                    $cal = $this->rsCalTableLists[$first].$paddingData;
                    $temp = $leftChar ^ $cal;
                } else {
                    $temp = substr($temp, 1);
                }

                $j--;
            }

            $this->codewords = array_merge($this->codewords, unpack('C*', $temp));
            $rsBlockNumber++;
        }
    }

    /**
     * Attach the calculated codeword data to the matrix.
     *
     * @param int $maxCodewords
     * @param int $matrixRemainingBit
     *
     * @return void
     */
    protected function attachCodewordData($maxCodewords, $matrixRemainingBit)
    {
        $i = 0;

        while ($i < $maxCodewords) {
            $codewordIndex = $this->codewords[$i];
            $j = 8;

            while ($j >= 1) {
                $codewordBitsNumber = ($i << 3) + $j;
                $x = $this->matrixListX[$codewordBitsNumber];
                $y = $this->matrixListY[$codewordBitsNumber];
                $this->matrixContents[$x][$y] = ((255 * ($codewordIndex & 1)) ^ $this->maskLists[$codewordBitsNumber]);
                $codewordIndex = $codewordIndex >> 1;
                $j--;
            }

            $i++;
        }

        $matrixRemaining = $matrixRemainingBit[$this->version];

        while ($matrixRemaining) {
            $tempBitRemaining = $matrixRemaining + ($maxCodewords << 3);
            $x = $this->matrixListX[$tempBitRemaining];
            $y = $this->matrixListY[$tempBitRemaining];
            $this->matrixContents[$x][$y] = (255 ^ $this->maskLists[$tempBitRemaining]);
            $matrixRemaining--;
        }
    }

    /**
     * Selects the mask to use.
     *
     * @param int $maxImageOneside
     *
     * @return int
     */
    protected function maskSelection($maxImageOneside)
    {
        $demeritScoreMin = 0;
        $horMaster = '';
        $verMaster = '';
        $k = 0;

        while ($k < $maxImageOneside) {
            $l = 0;

            while ($l < $maxImageOneside) {
                $horMaster = $horMaster.chr($this->matrixContents[$l][$k]);
                $verMaster = $verMaster.chr($this->matrixContents[$k][$l]);
                $l++;
            }

            $k++;
        }

        $allMatrix = $maxImageOneside * $maxImageOneside;
        $i = 0;

        while ($i < 8) {
            $demeritN1 = 0;
            $bit = 1 << $i;
            $bitRev = (~$bit) & 255;
            $bitMask = str_repeat(chr($bit), $allMatrix);
            $hor = $horMaster & $bitMask;
            $ver = $verMaster & $bitMask;

            $verShift1 = $ver.str_repeat(chr(170), $maxImageOneside);
            $verShift2 = str_repeat(chr(170), $maxImageOneside).$ver;
            $verShift1_0 = $ver.str_repeat(chr(0), $maxImageOneside);
            $verShift2_0 = str_repeat(chr(0), $maxImageOneside).$ver;
            $verOr = chunk_split(~($verShift1 | $verShift2), $maxImageOneside, chr(170));
            $verAnd = chunk_split(~($verShift1_0 & $verShift2_0), $maxImageOneside, chr(170));
            $hor = chunk_split(~$hor, $maxImageOneside, chr(170));
            $ver = chunk_split(~$ver, $maxImageOneside, chr(170));
            $hor = $hor.chr(170).$ver;
            $regexN1 = '/'.str_repeat(chr(255), 5).'+|'.str_repeat(chr($bitRev), 5).'+/';
            $regexN3 = chr($bitRev).chr(255).chr($bitRev).chr($bitRev).chr($bitRev).chr(255).chr($bitRev);
            $demeritN3 = substr_count($hor, $regexN3) * 40;
            $demeritN4 = floor(abs(((100 * (substr_count($ver, chr($bitRev)) / ($this->byteNum))) - 50) / 5));
            $demeritN4 *= 10;
            $regexN2_1 = '/'.chr($bitRev).chr($bitRev).'+/';
            $regexN2_2 = '/'.chr(255).chr(255).'+/';
            $demeritN2 = 0;

            $matches = [];
            preg_match_all($regexN2_1, $verAnd, $matches);

            foreach ($matches[0] as $match) {
                $demeritN2 += (strlen($match) - 1);
            }

            $matches = [];
            preg_match_all($regexN2_2, $verOr, $matches);

            foreach ($matches[0] as $match) {
                $demeritN2 += (strlen($match) - 1);
            }

            $demeritN2 *= 3;
            $matches = [];

            preg_match_all($regexN1, $hor, $matches);

            foreach ($matches[0] as $match) {
                $demeritN1 += (strlen($match) - 2);
            }

            $demeritScore = $demeritN1 + $demeritN2;
            $demeritScore += ($demeritN3 + $demeritN4);

            if ($demeritScore <= $demeritScoreMin || $i === 0) {
                $maskNumber = $i;
                $demeritScoreMin = $demeritScore;
            }

            $i++;
        }

        return $maskNumber;
    }

    /**
     * Clear the output matrix array, ready for new data.
     *
     * @param int $maxImageOneside
     *
     * @return void
     */
    protected function emptyMatrix($maxImageOneside)
    {
        $i = 0;

        while ($i < $maxImageOneside) {
            $j = 0;

            while ($j < $maxImageOneside) {
                $this->matrixContents[$j][$i] = 0;
                $j++;
            }

            $i++;
        }
    }

    /**
     * Create the base square image, based on the image size.
     *
     * @param int $maxImageOneside
     *
     * @return int
     */
    protected function createBaseImage($maxImageOneside)
    {
        $mib = $maxImageOneside + 8;
        $this->resultSize = $mib * $this->imageSize;

        if ($this->resultSize > 1480) {
            throw new \Exception('Image size is too large');
        }

        // image will always square-shaped
        $this->outputImage = imagecreate($this->resultSize, $this->resultSize);
        $imagesPath = $this->imagesPath.DIRECTORY_SEPARATOR.'qrv'.$this->version.'.png';

        if (! is_file($imagesPath)) {
            throw new \Exception('Base image not found');
        }

        $this->baseImage = imagecreatefrompng($imagesPath);

        if (! $this->baseImage) {
            throw new \Exception('Could not load base image');
        }

        return $mib;
    }

    /**
     * Adds the calculated matrix data to the base image.
     *
     * @param int $maxImageOneside
     * @param int $maskContent
     *
     * @return void
     */
    protected function addMatrixToImage($maxImageOneside, $maskContent)
    {
        $max = 4 + $maxImageOneside;
        $i = 4;
        $j = 0;

        while ($i < $max) {
            $k = 4;
            $l = 0;

            while ($k < $max) {
                if ($this->matrixContents[$j][$l] & $maskContent) {
                    imagesetpixel($this->baseImage, $i, $k, imagecolorallocate($this->baseImage, 0, 0, 0));
                }

                $k++;
                $l++;
            }

            $i++;
            $j++;
        }
    }
}
