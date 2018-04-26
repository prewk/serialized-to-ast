<?php declare(strict_types=1);
/**
 * Str
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * Str
 */
class Str implements KeyType
{
    /**
     * @var string
     */
    private $value;

    /**
     * Str constructor
     *
     * @param string $value
     */
    public function __construct(string $value)
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
        return strlen("s:" . strlen($this->value) . ":\"" . $this->value . "\";");
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
            "type" => "string",
            "value" => $this->value,
        ];
    }
}