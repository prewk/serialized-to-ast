<?php declare(strict_types=1);
/**
 * ProtectedProp
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * ProtectedProp
 */
class ProtectedProp implements PropertyType
{
    /**
     * @var string
     */
    private $name;

    /**
     * ProtectedProp constructor
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->name = substr($value, 3);
    }

    /**
     * Character length of the serialized type
     *
     * @return int
     */
    public function len(): int
    {
        $all = chr(0) . "*" . chr(0) . $this->name;

        return strlen("s:" . strlen($all) . ":\"$all\";");
    }

    /**
     * Get a property name without NUL/asterisk/FQCN
     *
     * @return string
     */
    public function getCleanName(): string
    {
        return $this->name;
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
            "type" => "protected_property",
            "name" => $this->name,
        ];
    }
}