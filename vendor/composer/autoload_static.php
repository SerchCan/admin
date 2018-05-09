<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2797215b0ff3c90fa61877d860a2003e
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Plansky\\CreditCard' => 
            array (
                0 => __DIR__ . '/..' . '/rplansky/credit-card/src',
            ),
        ),
    );

    public static $classMap = array (
        'Plansky\\CreditCard\\TestCase' => __DIR__ . '/..' . '/rplansky/credit-card/test/Plansky/CreditCard/TestCase.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit2797215b0ff3c90fa61877d860a2003e::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit2797215b0ff3c90fa61877d860a2003e::$classMap;

        }, null, ClassLoader::class);
    }
}