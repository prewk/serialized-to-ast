<?php declare(strict_types=1);
/**
 * Key
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * Describes a Key
 */
interface KeyType extends Type
{
    /**
     * Get the key type's raw value
     *
     * @return int|string
     */
    public function getRawValue();
}