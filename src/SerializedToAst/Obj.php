<?php declare(strict_types=1);
/**
 * Obj
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * Obj
 */
class Obj implements Type
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var PropertyType[]
     */
    private $props;

    /**
     * @var Type[]
     */
    private $values;

    /**
     * Obj constructor.
     *
     * @param string $name
     * @param PropertyType[] $props
     * @param Type[] $values
     */
    public function __construct(string $name, array $props, array $values)
    {
        $this->name = $name;
        $this->props = $props;
        $this->values = $values;
    }

    /**
     * Character length of the serialized type
     *
     * @return int
     */
    public function len(): int
    {
        return strlen("O:" . strlen($this->name) . ":\"{$this->name}\":" . count($this->props) . ":{}") +
            array_reduce($this->props, function(int $sum, Type $prop): int {
                return $sum + $prop->len();
            }, 0)
            + array_reduce($this->values, function(int $sum, Type $prop): int {
                return $sum + $prop->len();
            }, 0);
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
        $publicProps = [];
        $protectedProps = [];
        $privateProps = [];

        foreach ($this->props as $i => $prop) {
            if ($prop instanceof PublicProp) {
                $publicProps[$prop->getCleanName()] = $this->values[$i]->toArray();
            } else if ($prop instanceof ProtectedProp) {
                $protectedProps[$prop->getCleanName()] = $this->values[$i]->toArray();
            } else if ($prop instanceof PrivateProp) {
                $privateProps[$prop->getCleanName()] = $this->values[$i]->toArray();
            }
        }

        return [
            "type" => "object",
            "public_properties" => $publicProps,
            "protected_properties" => $protectedProps,
            "private_properties" => $privateProps,
        ];
    }
}