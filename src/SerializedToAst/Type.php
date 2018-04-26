<?php declare(strict_types=1);
/**
 * Type
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

use JsonSerializable;

/**
 * Describes a Type
 */
interface Type extends JsonSerializable
{
    /**
     * Character length of the serialized type
     *
     * @return int
     */
    public function len(): int;

    /**
     * Convert the node into an array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize();
}