<?php

namespace coreServices;

/**
 * Class DataForView class for storing data for view
 * @package coreServices
 */
class DataForView
{

    /**
     * @var array data to be displayed in <string>key => <any>value from
     */
    private array $data = [];

    /**
     * @var DataForView|null singleton instance of DataForView
     */
    private static ?DataForView $instance = null;

    public static function getInstance(): DataForView
    {
        if (self::$instance === null) {
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
