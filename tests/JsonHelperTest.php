<?php
declare(strict_types = 1);

namespace YaPro\Helper;

use PHPUnit\Framework\TestCase;

use function json_encode;

use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

class JsonHelperTest extends TestCase
{
    public function testJsonEncode()
    {
        $parameters = [
            [
                'name' => 'str 1',
            ],
            'my_key' => [
                'name' => 'str 2',
            ],
        ];
        self::assertEquals(
            '{{"name":"str 1"},"my_key":{"name":"str 2"}}',
            (new JsonHelper())->jsonEncode($parameters)
        );
        self::assertEquals(
            '{"0":{"name":"str 1"},"my_key":{"name":"str 2"}}',
            json_encode($parameters)
        );
    }
    public function testJsonEncodeTheSame()
    {
        $parameters = [
            'name' => 'Ken',
            'my_object' => [
                // т.к. boy - ключ, jsonEncode превратит array в object
                'boy' => [
                    'name' => 'str 1',
                ],
            ],
            'my_array' => [
                // т.к. ключа нет, jsonEncode оставит array как есть
                [
                    'name' => 'str 2',
                ],
                [
                    'name' => 'str 3',
                ],
            ],
        ];
        $expected = '{"name":"Ken","my_object":{"boy":{"name":"str 1"}},"my_array":[{"name":"str 2"},{"name":"str 3"}]}';
        self::assertEquals($expected, (new JsonHelper())->jsonEncode($parameters));
        self::assertEquals($expected, json_encode($parameters));
        self::assertEquals($expected, json_encode($parameters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $parameters = [
            'key 1' => 'Ken',
            'key-2' => 'Barbie ("girl")',
            'key\3' => 'Todd\Cat',
            'key"4' => 'Stacie' . PHP_EOL . 'Mouse',
        ];
        $expected = '{"key 1":"Ken","key-2":"Barbie (\"girl\")","key\\\3":"Todd\\\Cat","key\"4":"Stacie\nMouse"}';
        self::assertEquals($expected, (new JsonHelper())->jsonEncode($parameters));
        self::assertEquals($expected, json_encode($parameters));
        self::assertEquals($expected, json_encode($parameters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
