<?php

declare(strict_types=1);

namespace Chat\Util\ConverterClass;

trait ToStringTrait
{
    /**
     * @return string
     */
    public function __toString()
    {
        $data = [];

        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }

        return (string) json_encode($data);
    }
}
