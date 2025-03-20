<?php
declare(strict_types = 1);

namespace YaPro\Helper\Tests;

use PHPUnit\Framework\TestCase;

use YaPro\Helper\JsonHelper;
use function json_encode;

use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

class JsonHelperTest extends TestCase
{
    public function testSafeFloat()
    {
        $types = [
            [
                'varString' => 'string',
                'varInteger' => 123,
                'varBoolean' => true,
                'varFloat' => 0.0,
                'varNull' => null,
            ],
        ];
        // корректно:
        self::assertEquals('[{"varString":"string","varInteger":123,"varBoolean":true,"varFloat":0.0,"varNull":null}]', (new JsonHelper())->jsonEncode($types));
        // корректно:
        self::assertEquals('[{"varString":"string","varInteger":123,"varBoolean":true,"varFloat":0.0,"varNull":null}]', json_encode($types, JSON_PRESERVE_ZERO_FRACTION));
        // некорректно:
        self::assertEquals('[{"varString":"string","varInteger":123,"varBoolean":true,"varFloat":0,"varNull":null}]', json_encode($types));

    }

    public function testAutoKey()
    {
        $parameters = [
            [
                'name' => 'str 1',
            ],
            'my_key' => [
                'name' => 'str 2',
            ],
        ];
        // корректно:
        self::assertEquals('{"0":{"name":"str 1"},"my_key":{"name":"str 2"}}', (new JsonHelper())->jsonEncode($parameters));
        // корректно:
        self::assertEquals('{"0":{"name":"str 1"},"my_key":{"name":"str 2"}}', json_encode($parameters));
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

    public function testJsonEncodeLineBreak()
    {
        $json = '{"id":"123","name":"Мой текст","fullName":"Мой «ТЕст» (ДА)","description":"<P>Тест от 06.06.2019 № ОД-123 для проверки. </P>\r\n<P>Переносов строк. «А еще»: </P>\r\n<UL>\r\n<LI>с тегами (и разными скобками).</P>","type":"myTest","link":"https://na.site.ru/da/slash/logo_1.svg","isFieldBool":true,"contacts":{"phone":["+7 (123) 456-78-99"],"email":["my@at.ru"],"email2":["\tabc@de.su"],"website":"http://www.site.ru","address":"197374, Санкт-Петербург, ул.Не скажу, д.123, литера Б, помещение 12-Н","hold":"Включаем (9,9%) проценты.","apps":[{"key":"apple","link":"https://itunes.apple.com/us/app/ta/dam?mt=1"},{"key":"android","link":""}],"emptyObject":{}},"fullObject":{"abc":"123","de":"45","fg":"67","and_null":null},"someWebsite":"http://some.ru/page/","andDate":"1984-03-22T10:10:00.000Z"}';
        $data = json_decode($json);
        self::assertEquals($json, (new JsonHelper())->jsonEncode($data));
    }
    
    public function test_nullifyEqualFields()
    {
        $json1 = '{"name": "Alice", "age": 25, "city": "New York", "details": {"height": 170, "weight": 60}}';
        $json2 = '{"name": "Alice", "age": 30, "city": "New York", "details": {"height": 170, "weight": 65}}';

        $expectedJson = '{
    "name": null,
    "age": 25,
    "city": null,
    "details": {
        "height": null,
        "weight": 60
    }
}';

        $result = (new JsonHelper())->nullifyEqualFields($json1, $json2);

        $this->assertJsonStringEqualsJsonString($expectedJson, $result);
    }

    public function test_nullifyEqualFieldsEmptyJson()
    {
        $json1 = '{}';
        $json2 = '{}';

        $expectedJson = '[]';
        $result = (new JsonHelper())->nullifyEqualFields($json1, $json2);

        $this->assertJsonStringEqualsJsonString($expectedJson, $result);
    }

    public function test_nullifyEqualFieldsDifferentKeys()
    {
        $json1 = '{"name": "Alice", "age": 25}';
        $json2 = '{"city": "New York"}';

        $expectedJson = '{"name": "Alice", "age": 25}';
        $result = (new JsonHelper())->nullifyEqualFields($json1, $json2);

        $this->assertJsonStringEqualsJsonString($expectedJson, $result);
    }
}
