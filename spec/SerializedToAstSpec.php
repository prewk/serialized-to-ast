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
use Prophecy\Argument;

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
    }

    function it_should_parse_ints()
    {
        $this->parse(serialize(123))->shouldHaveType(Integer::class);
    }

    function it_should_parse_floats()
    {
        $this->parse(serialize(123.45))->shouldHaveType(Double::class);
    }

    function it_should_parse_strings()
    {
        $this->parse(serialize("Lorem ipsum"))->shouldHaveType(Str::class);
        $this->parse(serialize("😬"))->shouldHaveType(Str::class);
    }

    function it_should_parse_arrays()
    {
        $this->parse(serialize(["foo", "bar", "baz"]))->shouldHaveType(Arr::class);
        $this->parse(serialize(["foo" => 123, "bar" => 456, "baz" => 789]))->shouldHaveType(Arr::class);
        $this->parse(serialize(["foo", "bar" => 456, "baz"]))->shouldHaveType(Arr::class);
    }

    function it_should_parse_objects()
    {
        $this->parse(serialize(new TestClass))->shouldHaveType(Obj::class);
        $this->parse(serialize((object)["foo" => "bar"]))->shouldHaveType(Obj::class);
    }
}
