<?php

declare(strict_types=1);

namespace Chat\Util\ConverterClass;

trait ToArrayTrait
{
    /**
     * @return array
     */
    public function toArray() : array
    {
        $data = [];

        foreach ($this as $key => $value) {
            $data[ucfirst($key)] = $value;
        }

        return $data;
    }
}
