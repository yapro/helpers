<?php

namespace YaPro\Helper\Tests\String;

use Generator;
use PHPUnit\Framework\TestCase;
use YaPro\Helper\String\StringHelper;

class StringHelperTest extends TestCase
{
    public function providerGetHtmlWithoutIndentions(): Generator
    {
        yield [
            'html' => '<h2>word word</h2>
<p>word word</p>

<h2>word word</h2>
<p>word word.</p>
<ul>
  <li>word word.</li>
</ul>
<p>word word.</p>',
            'expected' => '<h2>word word</h2>' . PHP_EOL .
                '<p>word word</p>' . PHP_EOL .
                '<h2>word word</h2>' . PHP_EOL .
                '<p>word word.</p>' . PHP_EOL .
                '<ul>' . PHP_EOL .
                '<li>word word.</li>' . PHP_EOL .
                '</ul>' . PHP_EOL .
                '<p>word word.</p>',
        ];
    }

    /**
     * @dataProvider providerGetHtmlWithoutIndentions()
     */
    public function testGetHtmlWithoutIndentions(string $html, string $expected): void
    {
        $object = new StringHelper();
        $actual = $object->getHtmlWithoutIndents($html);
        $this->assertSame($expected, $actual);
    }
    
    public function provider_getHtmlWithoutFirstHeading(): Generator
    {
        yield [// удаляется первый заголовок
            'html' => '<h2>hello hello</h2><p>world world</p>',
            'expected' => '<p>world world</p>',
        ];
        yield [// ничего не должно быть удалено:
            'html' => '<p>hello world</p>',
            'expected' => '<p>hello world</p>',
        ];
        yield [// ничего не должно быть удалено:
            'html' => '<p>hello hello</p><h2>world world</h2>',
            'expected' => '<p>hello hello</p><h2>world world</h2>',
        ];
        yield [// ничего не должно быть удалено:
            'html' => '<p>hello hello</p><h2>world world</h2><p>world world</p>',
            'expected' => '<p>hello hello</p><h2>world world</h2><p>world world</p>',
        ];
    }

    /**
     * @dataProvider provider_getHtmlWithoutFirstHeading()
     */
    public function test_getHtmlWithoutFirstHeading(string $html, string $expected): void
    {
        $object = new StringHelper();
        $actual = $object->getHtmlWithoutFirstHeading($html);
        $this->assertSame($expected, $actual);
    }

    public function isMatchProvider(): Generator
    {
        yield [
            'needle' => 'любое место',
            'haystack' => 'а любое место в тексте',
            'expected' => false, // чтобы нашло, должны быть звездочки по бокам
        ];
        yield [
            'needle' => '*середина*',
            'haystack' => 'а середина становится ',
            'expected' => true,
        ];
        yield [
            'needle' => '*Слева справа*',
            'haystack' => 'Слева середина: справа',
            'expected' => false, // нет * между словами, поэтому false
        ];
        yield [
            'needle' => '*Слева*справа*',
            'haystack' => 'Слева середина: справа',
            'expected' => true,
        ];
        yield [
            'needle' => 'Слева*справа*',
            'haystack' => 'Слева середина: справа',
            'expected' => true,
        ];
        yield [
            'needle' => 'Слева*справа',
            'haystack' => 'Слева середина: справа',
            'expected' => true,
        ];
        yield [
            'needle' => 'середина*справа',
            'haystack' => 'Слева середина: справа',
            'expected' => false,
        ];
        yield [ // проверяем на правильное эскепирование:
            'needle' => '*[Имя ABC](https://example.ru)*',
            'haystack' => '[Имя ABC](https://example.ru)',
            'expected' => true,
        ];
        yield [ // как выше, но строка в тексте:
            'needle' => '*[Имя ABC](https://example.ru)*',
            'haystack' => 'Вот [Имя ABC](https://example.ru) в тексте',
            'expected' => true,
        ];
        yield [ // проверяем на правильное эскепирование:
            'needle' => '*доступна по [ссылке](#)*',
            'haystack' => 'доступна по [ссылке](#)',
            'expected' => true,
        ];
        yield [ // как выше, но строка в тексте:
            'needle' => '*доступна по [ссылке](#)*',
            'haystack' => 'Вот доступна по [ссылке](#) в тексте',
            'expected' => true,
        ];
    }

    /**
     * @dataProvider isMatchProvider
     */
    public function test_isMatch(string $needle, string $haystack, bool $expected): void
    {
        $result = (new StringHelper())->isMatch($needle, $haystack);
        $this->assertEquals($expected, $result);
    }
    
    public function cleanupProvider(): Generator
    {
        yield [
            'input' => 'представлены в РФ: Avatr, Deepal, eπ. На выход',
            'output' => 'представлены в РФ: Avatr, Deepal, e. На выход',
        ];
        yield [
            'input' => '<p>В 2022 году ключева́я ставка',
            'output' => '<p>В 2022 году ключевая ставка',
        ];
        yield [
            'input' => '«Ипотека 0,01% … зафиксировала, что спірна́я информация',
            'output' => '«Ипотека 0,01% … зафиксировала, что спірная информация',
        ];
        yield [
            'input' => 'В итоге收益 за весь',
            'output' => 'В итоге за весь',
        ];
        yield [
            'input' => '<p>🟢 поддерживается',
            'output' => '<p>🟢 поддерживается',
        ];
    }
    
    /**
     * @dataProvider cleanupProvider
     */
    public function test_cleanup(string $input, string $expected): void
    {
        $result = (new StringHelper())->cleanup($input);
        $this->assertEquals($expected, $result);
    }

    public function providerGetWithoutInvisibleSymbols(): array
    {
        // Несколько невидимых символов для тестов
        // U+200B = ZERO WIDTH SPACE
        $zwsp = "\u{200B}";
        // U+00A0 = NO-BREAK SPACE
        $nbsp = "\u{00A0}";
        // U+2800 = INVISIBLE SEPARATOR
        $inv  = "\u{2800}";

        return [

            // 1. Полное удаление невидимых символов + сжатие пробелов
            "remove invisible + collapse" => [
                "input"    => "Привет{$zwsp}мир{$nbsp}!",
                "expected" => "Привет мир !",
                "removeDoubleBlanks" => true
            ],

            // 2. Без сжатия пробелов
            "no collapsing blanks" => [
                "input"    => "Тест{$zwsp}{$zwsp}строка",
                // невидимые символы заменяются на пробелы, но не схлопываются
                "expected" => "Тест  строка",
                "removeDoubleBlanks" => false
            ],

            // 3. Чистая строка — не меняется
            "clean string" => [
                "input"    => "Hello world",
                "expected" => "Hello world",
                "removeDoubleBlanks" => true
            ],

            // 4. Только невидимые символы
            "only invisible" => [
                "input"    => $zwsp . $nbsp . $inv,
                "expected" => "", // превращаются в пробелы -> схлопываются -> пусто
                "removeDoubleBlanks" => true
            ],

            // 5. Смешанное: много подряд разных невидимых символов
            "mixed combined invisible" => [
                "input"    => "A{$zwsp}{$inv}{$nbsp}{$zwsp}B",
                "expected" => "A B",
                "removeDoubleBlanks" => true
            ],

            // 6. Проверка, что \n НЕ удаляется и НЕ считается невидимым
            "new line stays" => [
                "input"    => "A{$zwsp}B\nC",
                "expected" => "A B\nC",
                "removeDoubleBlanks" => true
            ],
        ];
    }

    /**
     * @dataProvider providerGetWithoutInvisibleSymbols
     */
    public function testGetWithoutInvisibleSymbols(string $input, string $expected, bool $removeDoubleBlanks = true)
    {
        $helper = new StringHelper();
        $this->assertSame($expected, $helper->getWithoutInvisibleSymbols($input, $removeDoubleBlanks));
    }
}
