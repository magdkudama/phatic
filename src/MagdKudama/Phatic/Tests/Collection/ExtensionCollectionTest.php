<?php

namespace MagdKudama\Phatic\Tests\Collection;

use MagdKudama\Phatic\Tests\TestCase;
use Mockery as m;
use PHPUnit_Framework_Error;
use MagdKudama\Phatic\Collection\ExtensionCollection;

class ExtensionCollectionTest extends TestCase
{
    /** @var ExtensionCollection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new ExtensionCollection();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testAddingAnElementWorks()
    {
        $mock = m::mock('MagdKudama\Phatic\Extension');
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
        $mock = m::mock('MagdKudama\Phatic\Extension');
        $this->collection->add($mock);
        $this->collection->add($mock);

        $this->assertEquals(
            1,
            count($this->collection),
            'Only non-added elements are added'
        );
    }

    public function testSearchingByNameReturnsFalseIfNotExists()
    {
        $mock = m::mock('MagdKudama\Phatic\Extension');
        $this->collection->add($mock);

        $this->assertFalse(
            $this->collection->findByName('fake'),
            'Searching returns false if class is not added to the collection'
        );
    }

    public function testSearchingByNameReturnsTheElement()
    {
        $mock1 = m::mock('MagdKudama\Phatic\Extension');
        $this->collection->add($mock1);

        $mock2 = m::mock('MagdKudama\Phatic\Extension');
        $this->collection->add($mock2);

        $mock3 = m::mock('MagdKudama\Phatic\Extension');
        $this->collection->add($mock3);

        foreach ([$mock1, $mock2, $mock3] as $mock) {
            $this->assertSame(
                $mock,
                $this->collection->findByName(get_class($mock)),
                'Searching returns the class when it exists in the collection'
            );
        }
    }
}