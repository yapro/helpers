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
}
