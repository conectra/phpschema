<?php

namespace Solis\Expressive\Schema\Entries;

use Solis\Expressive\Schema\Contracts\Entries\DynamicFunction\DynamicFunctionContract;
use Solis\Expressive\Schema\SchemaException;

/**
 * Class DinamyFunction
 *
 * @package Solis\Expressive\Schema\Entries
 */
class DinamycFunction implements DynamicFunctionContract
{

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $function;

    /**
     * @var array|string
     */
    private $params;

    /**
     * __construct
     *
     * @param $function
     */
    protected function __construct($function)
    {
        $this->setFunction($function);
    }

    /**
     * make
     *
     * @param array $format
     *
     * @return self
     * @throws SchemaException
     */
    public static function make($format)
    {
        if (!array_key_exists(
            'function',
            $format
        )
        ) {
            throw new SchemaException(
                __CLASS__,
                __METHOD__,
                "'function' field has not been found for defining 'format' schema entry ",
                400
            );
        }
        $instance = new self($format['function']);

        if (array_key_exists(
            'class',
            $format
        )) {
            if (!class_exists($format['class'])) {
                throw new SchemaException(
                    __CLASS__,
                    __METHOD__,
                    "class {$format['class']} has not been defined",
                    400
                );
            }

            if (!method_exists(
                $format['class'],
                $format['function']
            )
            ) {
                throw new SchemaException(
                    __CLASS__,
                    __METHOD__,
                    "function {$format['function']} has not been defined in class {$format['class']}",
                    400
                );
            }

            $instance->setClass($format['class']);
        }

        if (array_key_exists(
            'params',
            $format
        )) {
            $instance->setParams($format['params']);
        }

        return $instance;
    }

    /**
     * setClass
     *
     * Atribui classe qual será instanciada como ferramenta de formatação
     *
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * getClass
     *
     * Retorna a classe qual será instanciada como ferramenta de formatação
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * setFunction
     *
     * Atribui o nome da função pertencente a classe instanciada que será chamada para formatação
     *
     * @param string $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * getFunction
     *
     * Retorna o nome da função pertencente a classe instanciada que será chamada para formatação
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * setParams
     *
     * Atribui os argumentos a serem fornecidos a função de formatação
     *
     * @param array|string $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * getParams
     *
     * Retorna os argumentos a serem fornecidos a função de formatação
     *
     * @return array|string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * toArray
     *
     * Retorna representação em array do registro
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        $array['function'] = $this->getFunction();

        if (!empty($this->getClass())) {
            $array['class'] = $this->getClass();
        }

        if (!empty($this->getParams())) {
            $array['params'] = $this->getParams();
        }

        return $array;
    }
}
