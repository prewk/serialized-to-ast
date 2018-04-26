<?php declare(strict_types=1);
/**
 * Boolean
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * Boolean
 */
class Boolean implements Type
{
    /**
     * @var bool
     */
    private $value;

    /**
     * Boolean constructor
     *
     * @param bool $value
     */
    public function __construct(bool $value)
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
        return strlen("b:1;");
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
            "type" => "boolean",
            "value" => $this->value,
        ];
    }
}