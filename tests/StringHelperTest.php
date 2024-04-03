<?php

namespace YaPro\Helper\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use YaPro\Helper\StringHelper;

class StringHelperTest extends TestCase
{
    public function testTransliterate(): void
    {
        $object = new StringHelper();
        $actual = $object->transliterate(' Привет Мир $! ');
        $this->assertSame('privet-mir', $actual);
    }


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
}
