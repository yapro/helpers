<?php
declare(strict_types=1);

namespace YaPro\Helper\String;

class StringHelper
{
    public function getRandomPopularNounsAsString(int $count, string $delimeter = ', '): string
    {
        return implode($delimeter, $this->getRandomPopularNouns($count));
    }

    public function getRandomPopularNouns(int $count): array
    {
        $nouns = explode(PHP_EOL, $this->getPopularNounsAsString());
        return array_rand(array_flip($nouns), $count);
    }

    public function getPopularNounsAsString(): string
    {
        return (new FileHelper())->getFileContent(__DIR__ . '/PopularNouns.txt');
    }

    // убирает отступы у строк слева, чтобы когда мы заменим PHP_EOL на '</p><p>', мы спокойно убрали лишние теги,
    // например до функции было: '<p>  <li>', а после '<p>  <li>', которые мы можем заменить на <li>
    public function getHtmlWithoutIndentions(string $html)
    {
        // делаем единообразные переносы строк (вдруг попадется текст написанный на Windows):
        $html = str_replace("\r\n", PHP_EOL, $html);

        // удаляем пробелы между реальными строками:
        $paragraphs = explode(PHP_EOL, $html);
        $paragraphs = array_map(fn($paragraph) => trim($paragraph), $paragraphs);

        // удаляем пустые строки:
        $paragraphs = array_filter($paragraphs);
        // снова соединяем все строки в один текст:
        $html = implode(PHP_EOL, $paragraphs);
        
        return trim($html);
    }

    // Склонение существительных, например echo noun(7, 'яблоко', 'яблока', 'яблок');
    function noun($num = 0, string $str1 = '', string $str2 = '', string $str5 = ''): string
    {
        $num = abs($num);
        if ($num > 100) $num %= 100;
        if ($num > 20) $num %= 10;
        switch ($num) {
            case 1:
                return $str1;
            case 2:
                return $str2;
            case 3:
                return $str2;
            case 4:
                return $str2;
            default:
                return $str5;
        }
    }
}
