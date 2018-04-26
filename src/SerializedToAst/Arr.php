<?php declare(strict_types=1);
/**
 * Arr
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * Arr
 */
class Arr implements Type
{
    /**
     * @var KeyType[]
     */
    private $keys;

    /**
     * @var Type[]
     */
    private $values;

    /**
     * Arr constructor
     *
     * @param KeyType[] $keys
     * @param Type[] $values
     */
    public function __construct(array $keys, array $values)
    {
        $this->keys = $keys;
        $this->values = $values;
    }

    /**
     * Character length of the serialized type
     *
     * @return int
     */
    public function len(): int
    {
        return strlen("a:" . count($this->keys) . ":{}") +
            array_reduce($this->keys, function(int $sum, Type $type): int {
                return $sum + $type->len();
            }, 0) +
            array_reduce($this->values, function(int $sum, Type $type): int {
                return $sum + $type->len();
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
        $items = [];

        foreach ($this->keys as $i => $key) {
            $items[$key->getRawValue()] = $this->values[$i]->toArray();
        }

        return [
            "type" => "array",
            "items" => $items,
        ];
    }
}