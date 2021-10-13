<?php

namespace YaPro\Helper\Xml;

class XmlFormatter
{
    /**
     * @param string $xmlString
     * @return array
     */
    public function xmlStringToArray($xmlString)
    {
        return json_decode(json_encode((array) simplexml_load_string($xmlString)), true);
    }

    /**
     * обрабатывает простой HTML и возвращает его представление в виде массива
     *
     * @param string $html
     * @return array
     */
    public function htmlToArray($html)
    {
        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<superroot>' . $html . '</superroot>');
        libxml_use_internal_errors(false);
        $node = $dom->getElementsByTagName('superroot')->item(0);
        return $this->getArray($node);
    }

    /**
     * проверяет DOM-элемент на наличие атрибутов и значений и формирует массив
     *
     * @param $node \DOMElement|\DOMText|\DOMNode
     * @return array
     */
    private function getArray($node)
    {
        $array = array();

        if ($node->hasAttributes()) {
            $attributes = $node->attributes;
            foreach ($attributes as $attr) {
                $array[$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            if ($node->childNodes->length === 1) {
                if (!$value = $this->getArray($node->firstChild)) {
                    $value = $node->firstChild->nodeValue;
                }

                if ($node->firstChild->nodeName === '#text' && empty($array)) {
                    return $value;
                } else {
                    $array[$node->firstChild->nodeName] = $value;
                }
            } else {
                foreach ($node->childNodes as $childNode) {
                    if ($childNode->nodeType !== XML_TEXT_NODE) {
                        $array[$childNode->nodeName] = $this->getArray($childNode);
                    }
                }
            }
        }

        return $array;
    }
}
