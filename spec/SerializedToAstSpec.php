<?php

namespace spec\Prewk;

use Prewk\SerializedToAst;
use PhpSpec\ObjectBehavior;
use Prewk\SerializedToAst\Arr;
use Prewk\SerializedToAst\Boolean;
use Prewk\SerializedToAst\Double;
use Prewk\SerializedToAst\Integer;
use Prewk\SerializedToAst\Obj;
use Prewk\SerializedToAst\Str;

require_once("TestClass.php");

class SerializedToAstSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SerializedToAst::class);
    }

    function it_should_parse_bools()
    {
        $this->parse(serialize(true))->shouldHaveType(Boolean::class);
        $this->parse(serialize(false))->shouldHaveType(Boolean::class);

        $this->parse(serialize(true))->toArray()->shouldBeLike([
            "type" => "boolean",
            "value" => true,
        ]);
    }

    function it_should_parse_ints()
    {
        $this->parse(serialize(123))->shouldHaveType(Integer::class);

        $this->parse(serialize(123))->toArray()->shouldBeLike([
            "type" => "integer",
            "value" => 123,
        ]);
    }

    function it_should_parse_floats()
    {
        $this->parse(serialize(123.45))->shouldHaveType(Double::class);

        $this->parse(serialize(123.45))->toArray()->shouldBeLike([
            "type" => "float",
            "value" => 123.45,
        ]);
    }

    function it_should_parse_strings()
    {
        $this->parse(serialize("Lorem ipsum"))->shouldHaveType(Str::class);
        $this->parse(serialize("😬"))->shouldHaveType(Str::class);

        $this->parse(serialize("😬"))->toArray()->shouldBeLike([
            "type" => "string",
            "value" => "😬",
        ]);
    }

    function it_should_parse_arrays()
    {
        $this->parse(serialize(["foo", "bar", "baz"]))->shouldHaveType(Arr::class);
        $this->parse(serialize(["foo" => 123, "bar" => 456, "baz" => 789]))->shouldHaveType(Arr::class);
        $this->parse(serialize(["foo", "bar" => 456, "baz"]))->shouldHaveType(Arr::class);

        $this->parse(serialize(["foo", "bar" => 456, "baz"]))->toArray()->shouldBeLike([
            "type" => "array",
            "items" => [
                0 => [
                    "type" => "string",
                    "value" => "foo",
                ],
                "bar" => [
                    "type" => "integer",
                    "value" => 456,
                ],
                1 => [
                    "type" => "string",
                    "value" => "baz",
                ],
            ]
        ]);
    }

    function it_should_parse_objects()
    {
        $this->parse(serialize(new TestClass))->shouldHaveType(Obj::class);
        $this->parse(serialize((object)["foo" => "bar"]))->shouldHaveType(Obj::class);

        $this->parse(serialize(new TestClass))->toArray()->shouldBeLike([
            "type" => "object",
            "name" => TestClass::class,
            "public_properties" => [
                "foo" => [
                    "type" => "string",
                    "value" => "Public foo",
                ],
            ],
            "protected_properties" => [
                "baz" => [
                    "type" => "string",
                    "value" => "Protected baz",
                ],
            ],
            "private_properties" => [
                "bar" => [
                    "type" => "string",
                    "value" => "Private bar",
                ],
            ],
        ]);
    }
}
