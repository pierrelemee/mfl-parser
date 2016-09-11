<?php

namespace PierreLemee\MflParser\Model;

use Exception;

class GridFile
{
    protected $filename;
    protected $force;
    protected $rows;
    protected $definitions;
    protected $levels;
    protected $pictures;
    protected $dashes;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->rows = array();
        $this->definitions = array();
        $this->levels = array();
        $this->pictures = [];
        $this->dashes = array();
    }

    public function getExtension()
    {
        return ($index = strrpos($this->filename, ".")) ? substr($this->filename, $index + 1) : $this->filename;
    }

    public function getFileHandler()
    {
        return fopen($this->filename, 'r');
    }

    public function getWidth()
    {
        return isset($this->rows[0]) ? strlen($this->rows[0]) : 0;
    }

    public function getHeight()
    {
        return sizeof($this->rows);
    }

    /**
     * @return mixed
     */
    public function getForce()
    {
        return $this->force;
    }

    /**
     * @param mixed $force
     */
    public function setForce($force)
    {
        $this->force = $force;
    }

    public function addRow($row, $index)
    {
        if($this->getWidth() === 0 || $this->getWidth() === strlen($row)){
            if(($index) > sizeof($this->rows)){
                $this->rows[($index - 1)] = $row;
            }
            else{
                throw new Exception("Can't add row #$index before row {($index)}");
            }
        }
        else{
            throw new Exception("Row #$index ($row) doesn't fit with Grid width {$this->getWidth()}");
        }
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    public function addDefinition($definition, $index)
    {
        if($index == sizeof($this->definitions) + 1){
            $this->definitions[$index] = $definition;
        }
        else{
            throw new Exception("Can't add definition #$index before definition {($index - 1)}");
        }
    }

    /**
     * @return array
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    public function addLevels(array $levels)
    {
        $this->levels = $levels;
    }

    /**
     * @param $index int
     * @return mixed
     */
    public function getLevel($index)
    {
        return isset($this->levels[$index]) ? $this->levels[$index] : $this->force;
    }

    /**
     * @return array
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * @param $x int
     * @param $y int
     */
    public function addPicture($x, $y)
    {
        if (!isset($this->pictures[$x])) {
            $this->pictures[$x] = [];
        }
        $this->pictures[$x][$y] = true;
    }

    /**
     * @param $x
     * @param $y
     * @return boolean
     */
    public function isPicture($x, $y)
    {
        return isset($this->pictures[$x][$y]);
    }

    public function addDashes($index, $value)
    {
        if (!isset($this->dashes[$index])) {
            $this->dashes[$index] = [];
        }
        $this->dashes[$index][] = Dashes::getDashes($value);
    }

    /**
     * @return array
     */
    public function getDashes()
    {
        return $this->dashes;
    }

    /**
     * @param $x
     * @param $y
     * @return AbstractCell
     */
    public function getCell($x, $y)
    {
        return $x >= 0 && $x < $this->getWidth() && $y >= 0 && $y < $this->getHeight() ? $this->rows[$y]{$x} : null;
    }

    public function __toString()
    {
        $res = "";
        for ($i = 0; $i < sizeof($this->rows); $i++) {
            for ($j = 0; $j < sizeof($this->rows[$i]); $j++) {
                $res .=  $this->rows[$i][$j];
            }
            $res .= "\n";
        }
        return $res;
    }
}