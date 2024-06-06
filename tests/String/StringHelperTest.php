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
}
