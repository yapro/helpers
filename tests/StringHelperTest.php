<?php

namespace YaPro\Helper\Tests;

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
}
