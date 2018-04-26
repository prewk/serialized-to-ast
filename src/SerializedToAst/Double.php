<?php declare(strict_types=1);
/**
 * Double
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * Double
 */
class Double implements Type
{
    /**
     * @var float
     */
    private $value;

    /**
     * Double constructor
     *
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * Character length of the serialized type
     *
     * @return int
     */
    public function len(): int
    {
        return strlen("d:{$this->value};");
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the node into an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            "type" => "float",
            "value" => $this->value,
        ];
    }
}