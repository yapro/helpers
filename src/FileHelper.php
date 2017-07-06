<?php
declare(strict_types=1);

namespace YaPro\Helper;

final class FileHelper
{
    /**
     * @param string $fileName
     * @return string|false
     */
    public function getFileContent($fileName)
    {
        $file = new \SplFileObject($fileName, "r");
        return $file->fread($file->getSize());
    }
}