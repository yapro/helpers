<?php
declare(strict_types=1);

namespace YaPro\Helper;

use UnexpectedValueException;

class FileHelper
{
    /**
     * @param string $filePath
     * @return string|false
     */
    public function getFileContent(string $filePath)
    {
        $file = new \SplFileObject($filePath, "r");

        return $file->fread($file->getSize());
    }

    /**
     * @param string $filePath
     * @param mixed $content
     * @return string path to file
     */
    public function writeToFile(string $filePath, $content): string
    {
        if (false === file_put_contents($filePath, $content)) {
            throw new UnexpectedValueException('Unable to save file');
        }

        return $filePath;
    }

    /**
     * @param mixed $content
     * @return string path to temporary file
     */
    public function writeToTemporaryFile($content): string
    {
        $filePathInTmp = tempnam(sys_get_temp_dir(), '');
        if (false === $filePathInTmp) {
            throw new UnexpectedValueException('Unable to create temporary file');
        }

        return $this->writeToFile($filePathInTmp, $content);
    }
}
