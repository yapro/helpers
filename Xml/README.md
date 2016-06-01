Notes to yourself.

Example, we have the xml:

```
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
```
$simpleXml = simplexml_load_string($xml);
```

Get the attribute xmlns:xsi
```
$urlParams['xmlns:xsi'] = $simpleXml->getNamespaces()['xsi'];
```

Get the attribute xsi:noNamespaceSchemaLocation
```
$urlParams['xsi:noNamespaceSchemaLocation'] = $simpleXml->attributes('xsi', true)->getNamespaces()['xsi'];
```

Get the attribute QueryID:
```
$this->skyScanner = json_decode(json_encode((array) $simpleXml), true);

$urlParams['QueryID'] = $this->skyScanner['@attributes']['QueryID'];
```