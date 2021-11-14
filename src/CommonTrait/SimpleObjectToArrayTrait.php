<?php

declare(strict_types=1);

namespace YaPro\Helper\CommonTrait;

use App\Infrastructure\Oltp\Enum\DateFormatEnum;

trait SimpleObjectToArrayTrait
{
    public function toArray(): array
    {
        // get_object_vars($this) может включать вложенные объекты, поэтому правильно будет так:
        $result = [];
        foreach ($this as $k => $v) {
            if (is_scalar($v)) {
                $result[$k] = $v;
            }
            if ($v instanceof \DateTimeInterface) {
                $result[$k] = $v->format(DateFormatEnum::FORMAT);
            }
        }

        return $result;
    }
}
