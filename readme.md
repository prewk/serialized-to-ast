# SerializedToAST

Parses serialized PHP data, without deserializing, into a simple AST.

## Example

```php
class Test {
    private $foo = "Private Foo";
    public $bar = 12345;
    protected $baz = ["lorem", "ipsum" => "dolor amet", 67890];
}

$parser = new Prewk\SerializedToAst;
$node = $parser->parse(serialize(new Test));

// Array representation..
$arrayAst = $node->toArray();

// ..or JSON (see below)
$jsonAst = json_encode($node);
```

```json
{
    "type": "object",
    "public_properties": {
        "bar": {
            "type": "integer",
            "value": 12345
        }
    },
    "protected_properties": {
        "baz": {
            "type": "array",
            "items": {
                "0": {
                    "type": "string",
                    "value": "lorem"
                },
                "ipsum": {
                    "type": "string",
                    "value": "dolor amet"
                },
                "1": {
                    "type": "integer",
                    "value": 67890
                }
            }
        }
    },
    "private_properties": {
        "foo": {
            "type": "string",
            "value": "Private Foo"
        }
    }
}
```

## Installation

```sh
composer require prewk/serialized-to-ast
```

## Works for

* Booleans
* Integers
* Strings
* Floats
* Null
* Arrays
* Objects

Will cry if fed other stuff.

**Warning**: Experimental and not tested for edge cases.

## License

MIT