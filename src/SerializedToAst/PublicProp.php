<?php declare(strict_types=1);
/**
 * PublicProp
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * PublicProp
 */
class PublicProp implements PropertyType
{
    /**
     * @var Str
     */
    private $str;

    /**
     * PublicProp constructor
     *
     * @param Str $str
     */
    public function __construct(Str $str)
    {
        $this->str = $str;
    }

    /**
     * Character length of the serialized type
     *
     * @return int
     */
    public function len(): int
    {
        return $this->str->len();
    }

    /**
     * Get a property name without NUL/asterisk/FQCN
     *
     * @return string
     */
    public function getCleanName(): string
    {
        return (string)$this->str->getRawValue();
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
            "type" => "public_property",
            "name" => $this->str->getRawValue(),
        ];
    }
}