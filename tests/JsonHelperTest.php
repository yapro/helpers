<?php
declare(strict_types = 1);

namespace YaPro\Helper;

use PHPUnit\Framework\TestCase;

class JsonHelperTest extends TestCase
{
    public function testJsonEncode()
    {
        $parameters = [
            'name' => 'Ken',
            'wife' => [
                'name' => 'Barbie',
            ],
            'kids' => [
                // boy (ключ использовать нельзя, иначе json_encode превратит array в object)
                [
                    'name' => 'Todd',
                ],
                // girl (ключ использовать нельзя, иначе json_encode превратит array в object)
                [
                    'name' => 'Stacie',
                ],
            ],
        ];
        self::assertEquals(
            (new JsonHelper())->jsonEncode($parameters),
            '{"name":"Ken","wife":{"name":"Barbie"},"kids":[{"name":"Todd"},{"name":"Stacie"}]}'
        );
    }
}
