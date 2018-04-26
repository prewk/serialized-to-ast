<?php declare(strict_types=1);
/**
 * SerializedToAst
 *
 * @author Oskar Thornblad
 */

namespace Prewk;

use Exception;
use Prewk\SerializedToAst\Arr;
use Prewk\SerializedToAst\Boolean;
use Prewk\SerializedToAst\Double;
use Prewk\SerializedToAst\Integer;
use Prewk\SerializedToAst\KeyType;
use Prewk\SerializedToAst\Nul;
use Prewk\SerializedToAst\Obj;
use Prewk\SerializedToAst\ParseException;
use Prewk\SerializedToAst\PrivateProp;
use Prewk\SerializedToAst\PropertyType;
use Prewk\SerializedToAst\ProtectedProp;
use Prewk\SerializedToAst\PublicProp;
use Prewk\SerializedToAst\Str;
use Prewk\SerializedToAst\Type;

/**
 * SerializedToAst
 */
class SerializedToAst
{
    /**
     * Parse the serialized string into an AST node
     *
     * @param string $serialized
     * @return Type
     * @throws Exception
     */
    public function parse(string $serialized): Type
    {
        if (preg_match("/^b:(1|0);/", $serialized, $matches) === 1) {
            return new Boolean($matches[1] === "1");
        } else if (preg_match("/^i:(\d+);/", $serialized, $matches) === 1) {
            return new Integer(intval($matches[1]));
        } else if (preg_match("/^d:([0-9.]+);/", $serialized, $matches) === 1) {
            return new Double(floatval($matches[1]));
        } else if (preg_match("/^s:(\d+):\"/", $serialized, $matches) === 1) {
            $str = substr($serialized, strlen($matches[1]) + 4, intval($matches[1]));

            if (strlen($str) > 2 && $str[0] === chr(0)) {
                if ($str[1] === "*") {
                    return new ProtectedProp($str);
                } else {
                    return new PrivateProp($str);
                }
            }

            return new Str($str);
        } else if (substr($serialized, 0, 2) === "N;") {
            return new Nul;
        } else if (preg_match("/^a:(\d+):\{/", $serialized, $matches) === 1) {
            $keys = [];
            $values = [];
            $chunk = substr($serialized, strlen($matches[1]) + 4);
            $key = true;
            while ($chunk[0] !== "}") {
                $item = $this->parse($chunk);
                $chunk = substr($chunk, $item->len());

                if ($key) {
                    if (!$item instanceof KeyType) {
                        throw new ParseException("Invalid key type in array");
                    }

                    $keys[] = $item;
                } else {
                    $values[] = $item;
                }
                $key = $key ? false : true;
            }
            return new Arr($keys, $values);
        } else if (preg_match("/^O:(\d+):\"/", $serialized, $matches) === 1) {
            $fqcn = substr($serialized, strlen($matches[1]) + 4, intval($matches[1]));

            if (preg_match("/^(\d+):/", substr($serialized, strlen($matches[1]) + 6 + strlen($fqcn)), $propMatches) !== 1) {
                throw new ParseException("Encountered invalid object serialization");
            }

            $propCount = intval($propMatches[1]);

            $chunk = substr($serialized, 2 + strlen($matches[1]) + 2 + strlen($fqcn) + 2 + strlen($propMatches[1]) + 2);
            $props = [];
            $values = [];
            for ($i = 0; $i < $propCount; $i++) {
                $prop = $this->parse($chunk);
                $chunk = substr($chunk, $prop->len());

                $value = $this->parse($chunk);
                $chunk = substr($chunk, $value->len());

                if ($prop instanceof Str) {
                    $prop = new PublicProp($prop);
                }

                if (!$prop instanceof PropertyType) {
                    throw new ParseException("Invalid property type in object");
                }

                $props[] = $prop;
                $values[] = $value;
            }

            return new Obj($fqcn, $props, $values);
        } else {
            throw new ParseException("Unknown token");
        }
    }
}