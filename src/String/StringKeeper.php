<?php
declare(strict_types=1);

namespace YaPro\Helper\String;

class StringKeeper
{
    private array $strings = [];

    public function keep(string $string): string
    {
        $uniq = $this->getUniq();
        $this->strings[$uniq] = $string;
        return $uniq;
    }

    public function restore(string $string): string
    {
        foreach ($this->strings as $uniq => $value) {
            $string = str_replace($uniq, $value, $string);
        }

        return $string;
    }

    public function keepHtmlElementA(string $html): string
    {
        if ($htmlWithoutA = preg_replace_callback('/<a(.+)<\/a>/sUi', fn($matches) => $this->keep($matches[0]), $html)) {
            return $htmlWithoutA;
        }

        return $html;
    }

    private function getUniq(): string
    {
        return sha1(random_bytes(10));
    }
}
