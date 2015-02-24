<?php namespace Elepunk\Evaluator;

use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Original value before calculation
     * 
     * @var string|integer
     */
    protected $originalValue;

    /**
     * Calculated value after condition
     * 
     * @var string|integer
     */
    protected $calculatedValue;

    /**
     * Set the original value
     * 
     * @param string|integer $value
     */
    public function setOriginalValue($value)
    {
        $this->originalValue = $value;

        return $this;
    }

    /**
     * Set the calculated value
     * 
     * @param string|integer $value
     */
    public function setCalculatedValue($value)
    {
        $this->calculatedValue = $value;

        return $this;
    }

    /**
     * Retrieve the original value
     * 
     * @param string|integer $value
     */
    public function getOriginal()
    {
        return $this->originalValue;
    }

    /**
     * Retrieve the calculated value
     * 
     * @param string|integer $value
     */
    public function getResult()
    {
        return $this->calculatedValue;
    }
}
