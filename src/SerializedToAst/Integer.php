<?php declare(strict_types=1);
/**
 * Integer
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * Integer
 */
class Integer implements KeyType
{
    /**
     * @var int
     */
    private $value;

    /**
     * Integer constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
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
        return strlen("i:{$this->value};");
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
     * Get the key type's raw value
     *
     * @return int|string
     */
    public function getRawValue()
    {
        return $this->value;
    }

    /**
     * Convert the node into an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            "type" => "integer",
            "value" => $this->value,
        ];
    }
}