<?php
declare(strict_types=1);

namespace YaPro\Helper\String;

class StringHelper
{
    // Вытащил все символы с сайта https://invisible-characters.com/ (если нужно добавить новый, то узнать его код можно
    // на странице https://invisible-characters.com/view.html )
    // Важно: среди этого списка нет и не должно быть символа \n ведь иногда нам нужен текст, а не html
    private string $invisibleSymbols = 'U+0020,U+3164,U+2800,U+3164,U+200E,U+200C,U+3164,U+0009,U+0020,U+00A0,U+00AD,U+034F,U+061C,U+115F,U+1160,U+17B4,U+17B5,U+180B,U+180C,U+180D,U+180E,U+2000,U+2001,U+2002,U+2003,U+2004,U+2005,U+2006,U+2007,U+2008,U+2009,U+200A,U+200B,U+200C,U+200D,U+200E,U+200F,U+202A,U+202B,U+202C,U+202D,U+202E,U+202F,U+205F,U+2060,U+2061,U+2062,U+2063,U+2064,U+2066,U+2067,U+2068,U+2069,U+206A,U+206B,U+206C,U+206D,U+206E,U+206F,U+2800,U+3000,U+3164,U+FE00,U+FE01,U+FE02,U+FE03,U+FE04,U+FE05,U+FE06,U+FE07,U+FE08,U+FE09,U+FE0A,U+FE0B,U+FE0C,U+FE0D,U+FE0E,U+FE0F,U+FEFF,U+FFA0,U+FFFC,U+133FC,U+1D159,U+1D173,U+1D174,U+1D175,U+1D176,U+1D177,U+1D178,U+1D179,U+1D17A,U+E0001,U+E0020,U+E0021,U+E0022,U+E0023,U+E0024,U+E0025,U+E0026,U+E0027,U+E0028,U+E0029,U+E002A,U+E002B,U+E002C,U+E002D,U+E002E,U+E002F,U+E0030,U+E0031,U+E0032,U+E0033,U+E0034,U+E0035,U+E0036,U+E0037,U+E0038,U+E0039,U+E003A,U+E003B,U+E003C,U+E003D,U+E003E,U+E003F,U+E0040,U+E0041,U+E0042,U+E0043,U+E0044,U+E0045,U+E0046,U+E0047,U+E0048,U+E0049,U+E004A,U+E004B,U+E004C,U+E004D,U+E004E,U+E004F,U+E0050,U+E0051,U+E0052,U+E0053,U+E0054,U+E0055,U+E0056,U+E0057,U+E0058,U+E0059,U+E005A,U+E005B,U+E005C,U+E005D,U+E005E,U+E005F,U+E0060,U+E0061,U+E0062,U+E0063,U+E0064,U+E0065,U+E0066,U+E0067,U+E0068,U+E0069,U+E006A,U+E006B,U+E006C,U+E006D,U+E006E,U+E006F,U+E0070,U+E0071,U+E0072,U+E0073,U+E0074,U+E0075,U+E0076,U+E0077,U+E0078,U+E0079,U+E007A,U+E007B,U+E007C,U+E007D,U+E007E,U+E007F,U+E0100,U+E0101,U+E0102,U+E0103,U+E0104,U+E0105,U+E0106,U+E0107,U+E0108,U+E0109,U+E010A,U+E010B,U+E010C,U+E010D,U+E010E,U+E010F,U+E0110,U+E0111,U+E0112,U+E0113,U+E0114,U+E0115,U+E0116,U+E0117,U+E0118,U+E0119,U+E011A,U+E011B,U+E011C,U+E011D,U+E011E,U+E011F,U+E0120,U+E0121,U+E0122,U+E0123,U+E0124,U+E0125,U+E0126,U+E0127,U+E0128,U+E0129,U+E012A,U+E012B,U+E012C,U+E012D,U+E012E,U+E012F,U+E0130,U+E0131,U+E0132,U+E0133,U+E0134,U+E0135,U+E0136,U+E0137,U+E0138,U+E0139,U+E013A,U+E013B,U+E013C,U+E013D,U+E013E,U+E013F,U+E0140,U+E0141,U+E0142,U+E0143,U+E0144,U+E0145,U+E0146,U+E0147,U+E0148,U+E0149,U+E014A,U+E014B,U+E014C,U+E014D,U+E014E,U+E014F,U+E0150,U+E0151,U+E0152,U+E0153,U+E0154,U+E0155,U+E0156,U+E0157,U+E0158,U+E0159,U+E015A,U+E015B,U+E015C,U+E015D,U+E015E,U+E015F,U+E0160,U+E0161,U+E0162,U+E0163,U+E0164,U+E0165,U+E0166,U+E0167,U+E0168,U+E0169,U+E016A,U+E016B,U+E016C,U+E016D,U+E016E,U+E016F,U+E0170,U+E0171,U+E0172,U+E0173,U+E0174,U+E0175,U+E0176,U+E0177,U+E0178,U+E0179,U+E017A,U+E017B,U+E017C,U+E017D,U+E017E,U+E017F,U+E0180,U+E0181,U+E0182,U+E0183,U+E0184,U+E0185,U+E0186,U+E0187,U+E0188,U+E0189,U+E018A,U+E018B,U+E018C,U+E018D,U+E018E,U+E018F,U+E0190,U+E0191,U+E0192,U+E0193,U+E0194,U+E0195,U+E0196,U+E0197,U+E0198,U+E0199,U+E019A,U+E019B,U+E019C,U+E019D,U+E019E,U+E019F,U+E01A0,U+E01A1,U+E01A2,U+E01A3,U+E01A4,U+E01A5,U+E01A6,U+E01A7,U+E01A8,U+E01A9,U+E01AA,U+E01AB,U+E01AC,U+E01AD,U+E01AE,U+E01AF,U+E01B0,U+E01B1,U+E01B2,U+E01B3,U+E01B4,U+E01B5,U+E01B6,U+E01B7,U+E01B8,U+E01B9,U+E01BA,U+E01BB,U+E01BC,U+E01BD,U+E01BE,U+E01BF,U+E01C0,U+E01C1,U+E01C2,U+E01C3,U+E01C4,U+E01C5,U+E01C6,U+E01C7,U+E01C8,U+E01C9,U+E01CA,U+E01CB,U+E01CC,U+E01CD,U+E01CE,U+E01CF,U+E01D0,U+E01D1,U+E01D2,U+E01D3,U+E01D4,U+E01D5,U+E01D6,U+E01D7,U+E01D8,U+E01D9,U+E01DA,U+E01DB,U+E01DC,U+E01DD,U+E01DE,U+E01DF,U+E01E0,U+E01E1,U+E01E2,U+E01E3,U+E01E4,U+E01E5,U+E01E6,U+E01E7,U+E01E8,U+E01E9,U+E01EA,U+E01EB,U+E01EC,U+E01ED,U+E01EE,U+E01EF,';

    public function getWithoutInvisibleSymbols(string $string, bool $removeDoubleBlanks = true): string
    {
        $remove = str_replace('U+', '\x{', str_replace(',', '}', $this->invisibleSymbols));
        $withoutInvisible = preg_replace('/['.$remove.']/u', ' ', $string);
        if ($removeDoubleBlanks) {
            $withoutDoubleBlanks = preg_replace('/ {2,}/', ' ', $withoutInvisible);
        }

        return $withoutDoubleBlanks;
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
        return (new FileHelper())->getFileContent(__DIR__ . '/PopularNouns.txt');
    }

    // убирает отступы у строк слева, чтобы когда мы заменим PHP_EOL на '</p><p>', мы спокойно убрали лишние теги,
    // например до функции было: '<ul>\n  <li>', а после '<ul>\n<li>'
    public function getHtmlWithoutIndents(string $html)
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

    // Удаляет заголовок в HTML, если заголовок это первое, что есть в HTML
    public function getHtmlWithoutFirstHeading(string $html)
    {
        $html = trim($html);
        $tagName = mb_strtolower(mb_substr($html, 1, 2));
        // если первый тег является тегом заголовка:
        if (mb_substr($html, 0, 1) === '<' &&
            mb_substr($html, 3, 1) === '>' &&
            in_array($tagName, ['h1', 'h2', 'h3', 'h4', 'h5'], true)
        ) {
            // удаляем заголовок:
            $parts = explode('</' . $tagName . '>', $html);
            array_shift($parts);
            $html = implode('</' . $tagName . '>', $parts);
        }
        
        return $html;
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

    // Преобразовывает первый символ строки в верхний регистр
    public function noneWithNum($num = 0, string $str1 = '', string $str2 = '', string $str5 = ''): string
    {
        return $num . ' ' . $this->noun($num, $str1, $str2, $str5);
    }

    // Преобразовывает первый символ строки в верхний регистр
    public function ucfirst(string $string): string
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }

    // Преобразовывает первый символ строки в нижний регистр
    public function lcfirst(string $string): string
    {
        return mb_strtolower(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }

    public function isMatch(string $needle, string $html): bool
    {
        $pattern = '/^' . str_replace('\*', '(.*)', preg_quote($needle, '/')) . '$/sui';
        return preg_match($pattern, $html) === 1;
    }

    public function cleanup(string $string): string
    {
        $result = preg_replace('/[^\p{Cyrillic}\p{Common}\p{Latin}]+/u', '', $string);
        if (!empty($result)) {
            $string = $result;
        }

        return $this->getWithoutInvisibleSymbols($string);
    }

    public function getCleanedTextWhereEachSentenceAsNewLine(?string $string): string
    {
        if (!is_string($string)) {
            return '';
        }
        // преобразовываем мнемоники вида &nbsp; в юникод-символы вида  , а затем заменяем данные юникод-символы на пробел:
        $string = trim($this->cleanup(html_entity_decode($string)));
        $tags = ['<br>', '<br/>', '<br />', '</li>', '</p>', '</div>', '</th>', '</td>', '</blockquote>', '</section>', '</article>', '</header>', '</footer>', '</aside>', '</main>', '</nav>', '</h1>', '</h2>', '</h3>', '</h4>', '</h5>', '</h6>', '</pre>', '</hr>', '</address>', '</figure>', '</figcaption>'];
        // не регуляркой, т.к. больше уверенности + понятности + нестандартное закрые BR-тегов
        foreach ($tags as $tag) {
            $string = str_replace($tag, $tag . PHP_EOL, $string);
            $tag = mb_strtoupper($tag);
            $string = str_replace($tag, $tag . PHP_EOL, $string);
        }

        // str_replace('><', '> <', подстраховка от ситуации, когда в результате strip_tags два слова сливаются в одно в
        // результате изменения поведения HTML-тега с строчного вида, на блочный, например когда <span> становится блоком:
        $string = trim(strip_tags(str_replace('><', '> <', $string)));
        $sentences = explode(PHP_EOL, $string);
        // подчищаем хвосты строк:
        $result = [];
        foreach ($sentences as $sentence) {
            $trimmed = trim($sentence);
            if (!empty($trimmed)) {
                $result[] = $trimmed;
            }
        }

        return implode(PHP_EOL, $result);
    }

    /**
     * @param string $previousRows - текст 1
     * @param string $currentRows - текст 2
     * @return string - разница между текстом 1 и текстом 2 в виде HTML:
     * в предыдущем тексте нет:
     * 1) строка 1
     * 2) строка 2
     * 3) и т.д.
     * в текущем тексте нет:
     * 1) строка 1
     * 2) строка 2
     * 3) и т.д.
     */
    public function getTextRowsDiff(string $previousRows, string $currentRows): string
    {
        if (empty($previousRows) && empty($currentRows)) {
            return 'previous пуст';
        }
        if (empty($currentRows)) {
            return 'current пуст';
        }
        if (empty($previousRows)) {
            return str_replace(PHP_EOL, '<br>', $currentRows);
        }
        $previousStrings = explode(PHP_EOL, $previousRows);
        $currentStrings = explode(PHP_EOL, $currentRows);
        $previousCurrentDiff = array_diff($previousStrings, $currentStrings);
        $currentPreviousDiff = array_diff($currentStrings, $previousStrings);
        $result = '';
        if (!empty($currentPreviousDiff)) {
            $strings = [];
            foreach ($currentPreviousDiff as $i => $value) {
                $strings[] = ($i + 1) . ') ' . $value;
            }
            $result .= '<div>в предыдущем тексте нет:</div><code>' . implode('<br>', $strings) . '</code>';
        }
        if (!empty($previousCurrentDiff)) {
            $strings = [];
            foreach ($previousCurrentDiff as $i => $value) {
                $strings[] = ($i + 1) . ') ' . $value;
            }
            $result .= '<div>в текущем тексте нет:</div><code>' . implode('<br>', $strings) . '</code>';
        }

        return empty($result) ? '-тексты одинаковы-' : $result;
    }

    /**
     * Разбивает строку согласно сразу нескольким делиметрам:
     * @param string $string - строка которую нужно разбить
     * @param array $delimiters - разделители, по которым нужно разбить, например ["<br>", "</p>", "\n"]
     * @return array - список строк в результате разбития
     */
    public function multiExplode(string $string, array $delimiters): array
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        return explode($delimiters[0], $ready);
    }

    public function explodi(string $delimiter, string $string): array
    {
        // альтернатива: preg_split("/'.preg_quote($delimiter, '/').'/ui", $text);
        return explode($delimiter, str_ireplace($delimiter, $delimiter, $string));
    }

    // Синтаксис $result = $str ?: '' работает плохо, ведь при $str === '0', будет $result = '' + не делается trim при проверке и результате
    public static function getNotEmptyTrimmed(string $value, string $defaut = ''): string
    {
        $result = trim($value); // обрезаем, на всякий случай
        if ($result === '') { // empty($value) нельзя, ведь empty('0') возвращает true
            return trim($defaut); // обрезаем, на всякий случай
        }
        return $result;
    }
}
