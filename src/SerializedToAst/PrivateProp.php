<?php declare(strict_types=1);
/**
 * PrivateProp
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * PrivateProp
 */
class PrivateProp implements PropertyType
{
    /**
     * @var string
     */
    private $fqcn;

    /**
     * @var string
     */
    private $name;

    /**
     * PrivateProp constructor
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $sep = strpos($value, chr(0), 1);

        if ($sep === false) {
            throw new ParseException("Invalid private property");
        }

        $this->fqcn = substr($value, 1, $sep - 1);
        $this->name = substr($value, $sep + 1);
    }

    /**
     * Character length of the serialized type
     *
     * @return int
     */
    public function len(): int
    {
        $all = chr(0) . $this->fqcn . chr(0) . $this->name;

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
            "type" => "private_property",
            "name" => $this->name,
        ];
    }
}