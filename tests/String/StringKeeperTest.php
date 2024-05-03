<?php

namespace YaPro\Helper\Tests\String;

use Generator;
use PHPUnit\Framework\TestCase;
use YaPro\Helper\String\StringHelper;
use YaPro\Helper\String\StringKeeper;

class StringKeeperTest extends TestCase
{
    public function testKeep(): void
    {
        $originalString = 'my password';
        $object = new StringKeeper();
        // сохраняем значение 'my'
        $hash = $object->keep('password');
        // меняем password на hash
        $actual = str_replace('password', $hash, $originalString);
        $this->assertSame('my '.$hash, $actual);
        // восстанавливаем password:
        $actual = $object->restore($actual);
        $this->assertSame($originalString, $actual);
    }
    
    public function testKeepHtmlElementA(): void
    {
        $originalString = 'my <a href="/path">link</a> in the text';
        $object = new StringKeeper();
        // сохраняем элемент A
        $processedString = $object->keepHtmlElementA($originalString);
        $this->assertNotSame($originalString, $processedString);
        // восстанавливаем элемент A:
        $actual = $object->restore($processedString);
        $this->assertSame($originalString, $actual);
    }
}
