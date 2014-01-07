<?php

namespace MagdKudama\Phatic\Tests\Collection;

use MagdKudama\Phatic\Tests\TestCase;
use Mockery as m;
use PHPUnit_Framework_Error;
use MagdKudama\Phatic\Collection\ProcessorCollection;

class ProcessorCollectionTest extends TestCase
{
    /** @var ProcessorCollection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new ProcessorCollection();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testAddingAnElementWorks()
    {
        $mock = m::mock("MagdKudama\\Phatic\\AbstractProcessor");
        $this->collection->add($mock);

        $this->assertEquals(
            1,
            count($this->collection),
            'Add method works as expected'
        );
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testClassIsTypeHinted()
    {
        $this->collection->add("fake");
    }

    public function testAddingElementChecksIfItsContained()
    {
        $mock = m::mock("MagdKudama\\Phatic\\AbstractProcessor");
        $this->collection->add($mock);
        $this->collection->add($mock);

        $this->assertEquals(
            1,
            count($this->collection),
            'Only non-added elements are added'
        );
    }
}