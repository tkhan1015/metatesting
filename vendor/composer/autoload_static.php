<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit11bfaffc88064682f3841c1e5bd3a932
{
    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'lsolesen\\pel\\' => 13,
        ),
        'C' => 
        array (
            'CSD\\Image\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'lsolesen\\pel\\' => 
        array (
            0 => __DIR__ . '/..' . '/fileeye/pel/src',
        ),
        'CSD\\Image\\' => 
        array (
            0 => __DIR__ . '/..' . '/frameright/image-metadata-parser/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit11bfaffc88064682f3841c1e5bd3a932::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit11bfaffc88064682f3841c1e5bd3a932::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit11bfaffc88064682f3841c1e5bd3a932::$classMap;

        }, null, ClassLoader::class);
    }
}
