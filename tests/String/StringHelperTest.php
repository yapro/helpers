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
        $actual = $object->getHtmlWithoutIndentions($html);
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
}
