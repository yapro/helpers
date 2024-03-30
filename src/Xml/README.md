Notes to yourself.

## Создание XML

1. самый простой способ - пишем атрибуты (как есть), пример:

```php
class Xml
{
    public static function generate($posts)
    {
        $xml = <<<xml
<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0'>
<channel>
<title>Todd Eidson's Programming Blog</title>
<link>https://eidson.info</link>
<description>Programming Blog</description>
<language>en-us</language>
xml;
        foreach ($posts as $post) {

            $title = self::xmlEscape($post->getTitle());
            $url = self::xmlEscape($post->getUrl());
            $slug = self::xmlEscape($post->getHtml());
            $pubDate = $post->getPublished()->format('D, d M Y H:i:s T');
            $xml .= <<<xml
<item>
<title>{$title}</title>
<link>https://eidson.info/post/{$url}</link>
<description>{$slug}</description>
<pubDate>$pubDate</pubDate>
</item>
xml;
        }
        $xml .= "</channel></rss>";

        return $xml;
    }

    private static function xmlEscape($string) {
        return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
    }
}
```
почему это лучший способ - потому что наглядный и простой, легко поддерживать (особенно если нет переиспользования, и
даже если нужно переиспользовать, то можно вынести нужные куски в другой класс/функцию), меня на это подтолкнула статья
https://eidson.info/post/creating-an-rss-feed-for-a-symfony-blog

2. способ более сложный - создать массив и превратить его в хмл с помощью https://github.com/nullivex/lib-array2xml
```php
// Build the array that should be transformed into a XML object.
$array = [
    'title' => 'A title',
    'body' => [
        '@xml' => '<html><body><p>The content for the news item</p></body></html>',
    ],
];

// Use the Array2XML object to transform it.
$xml = Array2XML::createXML('news', $array);
echo $xml->saveXML();
```
This will result in the following.
```xml
<?xml version="1.0" encoding="UTF-8"?>
<news>
  <title>A title</title>
  <body>
    <html>
      <body>
        <p>The content for the news item</p>
      </body>
    </html>
  </body>
</news>
```

3. способ посложнее - написать класс с подготовленными методами, а затем вызвать фабричный метод, который соберет весь
   документ в хмл-файл:
```php
public function executeLastPosts()
{
  $feed = sfFeed::newInstance('atom1');
 
  $feed->setTitle('The mouse blog');
  $feed->setLink('http://www.myblog.com/');
  $feed->setAuthorEmail('pclive@myblog.com');
  $feed->setAuthorName('Peter Clive');
 
  $c = new Criteria;
  $c->addDescendingOrderByColumn(PostPeer::CREATED_AT);
  $c->setLimit(5);
  $posts = PostPeer::doSelect($c);
 
  foreach ($posts as $post)
  {
    $item = new sfFeedItem();
    $item->setFeedTitle($post->getTitle());
    $item->setFeedLink('@permalink?stripped_title='.$post->getStrippedTitle());
    $item->setFeedAuthorName($post->getAuthor()->getName());
    $item->setFeedAuthorEmail($post->getAuthor()->getEmail());
    $item->setFeedPubdate($post->getCreatedAt('U'));
    $item->setFeedUniqueId($post->getStrippedTitle());
    $item->setFeedDescription($post->getDescription());
 
    $feed->addItem($item);
  }
 
  $this->feed = $feed;
}
```
на эту мысль меня навел старый документ https://symfony.com/legacy/doc/cookbook/1_0/en/syndication и прокет
https://github.com/mibe/FeedWriter/tree/master (однако в этом подходе есть одна неприятность, иногда хмл-элементы нужно
расширять атрибутами с значениями, нужно будет как-то расширять ранее созданные методы).

4. способ самый сложный, но самый гибкий, когда мы пользуемся API предоставляемый PHP-библиотекой и возлагаем всю работу
   на них. Таких библиотек много: XMLWriter, SimpleXML и т.д. - https://www.php.net/manual/en/refs.xml.php

# Изменение существуюей XML или работа с ней

Предположим у нас есть хмл-ка, например RSS-ка которую мы хотим разобрать (получить из нее данные), тут нам поможет
предоставляемая PHP-библиотека SimpleXML (см. пример ниже).

Example, we have the xml:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<FareSearchRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:noNamespaceSchemaLocation="https://atd.skyscanner.net/Schemas/FareSearchRQ.xsd"
              QueryID="c796d221-1ebc-4ed5-af55-a2a51b743fcb">
    <TravelerInfo Type="ADT">2</TravelerInfo>
    <OriginDestination>
        <Departure>
            <CityCode>MOW</CityCode>
        </Departure>
    </OriginDestination>
</FareSearchRQ>
```

Make SimpleXMLElement:
```php
$simpleXml = simplexml_load_string($xml);
print_r((array) $simpleXml);
```

Get the attribute xmlns:xsi
```php
$urlParams['xmlns:xsi'] = $simpleXml->getNamespaces()['xsi'];
```

Get the attribute xsi:noNamespaceSchemaLocation
```php
$urlParams['xsi:noNamespaceSchemaLocation'] = $simpleXml->attributes('xsi', true)->getNamespaces()['xsi'];
```

Get the attribute QueryID:
```php
$this->skyScanner = json_decode(json_encode((array) $simpleXml), true);

$urlParams['QueryID'] = $this->skyScanner['@attributes']['QueryID'];
```
