<?php
declare(strict_types=1);

namespace YaPro\Helper;

class StringHelper
{
    private $translit = array(
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'j',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sh',
        'ъ' => 'i',
        'ы' => 'i',
        'ь' => 'i',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        'є' => 'e',
        'і' => 'i',
        'ї' => 'yi');

    // Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($string); работает не так как хотелось, поэтому:
    public function transliterate(string $text): string
    {
        $text = mb_strtolower($text);
        $text = strtr($text, $this->translit);
        $text = preg_replace('/[^-a-z0-9]/sUi', '-', $text);
        $text = preg_replace('/[\-]{2,}/', '-', $text);

        return trim($text, '-');
    }

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
        return (new FileHelper())->getFileContent(__DIR__ . '/String/PopularNouns.txt');
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
}
