<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Images\Handlers\GDHandler;
use CodeIgniter\Images\Handlers\ImageMagickHandler;

class Images extends BaseConfig
{
    /**
     * Default handler used if no other handler is specified.
     */
    public string $defaultHandler = 'gd';

    /**
     * The maximum image file size in kilobytes.
     * Set to 10240 for 10MB
     */
    public $maxSize = 10240;

    /**
     * The maximum image width in pixels.
     * Set to 0 for no limit
     */
    public $maxWidth = 0;

    /**
     * The maximum image height in pixels.
     * Set to 0 for no limit
     */
    public $maxHeight = 0;


    /**
     * The path to the image library.
     * Required for ImageMagick, GraphicsMagick, or NetPBM.
     */
    public string $libraryPath = '/usr/local/bin/convert';

    /**
     * The available handler classes.
     *
     * @var array<string, string>
     */
    public array $handlers = [
        'gd'      => GDHandler::class,
        'imagick' => ImageMagickHandler::class,
    ];
}
