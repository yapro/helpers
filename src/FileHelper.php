<?php
declare(strict_types=1);

namespace YaPro\Helper;

/**
 * Хелпер для работы с файлами
 * Class FileUtility
 * @package com\calltouch\api\utility
 */
class FileHelper
{
    /**
     * Возвращает содержимое файла
     * @param string $fileName Имя файла
     * @return string|false
     */
    public static function getFileContent($fileName)
    {
        $file = new \SplFileObject($fileName, "r");
        return $file->fread($file->getSize());
    }
}