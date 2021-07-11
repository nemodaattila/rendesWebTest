<?php

namespace coreServices;


class DataForView
{
    private array $data=[];

    private static ?DataForView $instance = null;

    public static function getInstance(): DataForView
    {
        if (self::$instance === null)
        {
            self::$instance = new DataForView();
        }

        return self::$instance;
    }

    public function setValue(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    public function getValue(string $name)
    {
        return $this->data[$name];
    }

}
