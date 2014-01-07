<?php

namespace MagdKudama\Phatic;

class Utils
{
    public static function getSystemConfigFileName()
    {
        return self::getBaseDirectory() . 'phatic.yml';
    }

    public static function getBaseDirectory()
    {
        return rtrim(getcwd(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public static function getSystemDirectory()
    {
        return __DIR__ . '/../../../';
    }

    public static function getBootDirectory()
    {
        return __DIR__ . '/';
    }
}