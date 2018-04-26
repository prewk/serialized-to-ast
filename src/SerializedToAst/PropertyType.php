<?php declare(strict_types=1);
/**
 * Property
 *
 * @author Oskar Thornblad
 */

namespace Prewk\SerializedToAst;

/**
 * Describes a Property
 */
interface PropertyType extends Type
{
    /**
     * Get a property name without NUL/asterisk/FQCN
     *
     * @return string
     */
    public function getCleanName(): string;
}