<?php

namespace mrcrmn\Collection\Tests;

use PHPUnit\Framework\TestCase;
use mrcrmn\Collection\Collection;

class CollectionTest extends TestCase
{
    public function test_is_iterable()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);

        foreach ($collection as $item) {
            $this->assertNotNull($item);
        }
    }

    public function test_it_can_be_counted()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);

        $this->assertCount(3, $collection);
    }

    public function test_it_is_json_serializeable()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        
        $this->assertJson($collection->jsonSerialize());
    }

    public function test_it_can_be_accessed_by_a_key()
    {
        $collection = new Collection(['foo' => 'bar']);

        $this->assertEquals('bar', $collection['foo']);
    }

    public function test_it_can_be_created_statically()
    {
        $collection = Collection::make(['foo' => 'bar']);

        $this->assertEquals('bar', $collection['foo']);
        $this->assertInstanceOf('mrcrmn\Collection\Collection', $collection);

        $collection2 = Collection::make('foo', 'bar');
        $this->assertInstanceOf('mrcrmn\Collection\Collection', $collection2);
        
        $this->assertCount(2, $collection2);
    }

    public function test_it_gets_a_value_by_magic_get_call()
    {
        $collection = new Collection(['foo' => 'bar']);

        $this->assertEquals('bar', $collection->foo);
    }

    public function test_it_sets_a_value_by_magic_set_call()
    {
        $collection = new Collection();
        $collection->foo = 'bar';

        $this->assertEquals('bar', $collection->foo);
    }

    public function test_if_an_unknown_method_is_called_it_tries_to_get_a_value_and_uses_the_method_name_as_a_key()
    {
        $collection = new Collection(['foo' => 'bar']);
        
        $this->assertEquals('bar', $collection->foo());
        $collection->foo('baz');
        $this->assertEquals('baz', $collection->foo());
    }

    public function test_it_can_get_a_value_via_the_get_method_and_returns_null_if_its_not_set()
    {
        $collection = new Collection(['foo' => 'bar']);

        $this->assertEquals('bar', $collection->get('foo'));
        $this->assertNull($collection->get('not_set'));
    }

    public function test_new_values_can_be_set()
    {
        $collection = new Collection();
        $collection->set(1);
        $collection->set('key', 'value');

        $this->assertCount(2, $collection);
        $this->assertEquals(1, $collection->get(0));
        $this->assertEquals('value', $collection->get('key'));
    }

    public function test_unsets_a_key()
    {
        $collection = new Collection(['foo' => 'bar']);
        
        $collection->remove('foo');
        $this->assertNull($collection->get('foo'));
    }

    public function test_it_can_be_reversed()
    {
        $collection = new Collection(['foo', 'bar']);

        $this->assertEquals('bar', $collection->reverse()[0]);
    }

    public function test_it_can_be_mapped()
    {
        $collection = new Collection(['foo', 'bar']);
        
        $mapped = $collection->map(function($item) {
            return strtoupper($item);
        });

        $this->assertEquals('FOO', $mapped[0]);
        $this->assertEquals('BAR', $mapped[1]);
    }

    public function test_the_each_method_works()
    {
        $collection = new Collection(['foo' => 'bar', 'bar' => 'baz']);
        
        $collection->each(function($key, $value) {
            $this->assertTrue(in_array($key, ['foo', 'bar']));
            $this->assertTrue(in_array($value, ['bar', 'baz']));
        });
    }

    public function test_a_collection_can_be_empty() {
        $collection = new Collection();

        $this->assertTrue($collection->isEmpty());
    }

    public function test_it_can_be_summed()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $this->assertEquals(10, $collection->sum());
    }

    public function test_it_can_be_searched()
    {
        $collection = new Collection(['foo' => 'bar', 'baz' => 1]);

        $this->assertEquals('foo', $collection->search('bar'));
        $this->assertEquals('baz', $collection->search(1));
        $this->assertFalse($collection->search('foo'));
    }

    public function test_it_can_be_filtered()
    {
        $collection = new Collection(['foo', 'bar', 'baz', null]);

        $this->assertCount(3, $collection->filter());

        $collection->filter(function($item) {
            return $item === 'baz';
        });

        $this->count(1, $collection);
    }

    public function test_it_can_be_chunked()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $collection = $collection->chunk(2);

        $this->assertCount(2, $collection);
        $this->assertEquals(3, $collection[1][0]);
    }

    public function test_it_can_be_sliced()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $collection = $collection->slice(1, 2);

        $this->assertCount(2, $collection);
        $this->assertEquals(2, $collection[0]);
    }

    public function test_it_can_be_imploded()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $this->assertEquals('1, 2, 3, 4', $collection->implode(', '));
    }

    public function test_it_can_be_exploded()
    {
        $collection = Collection::explode(',', '1,2,3,4');

        $this->assertCount(4, $collection);
    }

    public function test_it_returns_its_keys()
    {
        $collection = new Collection(['foo' => 'bar', 'bar' => 'baz']);
        
        $this->assertEquals('foo', $collection->keys()[0]);
    }

    public function test_it_returns_its_values()
    {
        $collection = new Collection(['foo' => 'bar', 'bar' => 'baz']);

        $this->assertEquals('bar', $collection->values()[0]);
    }

    public function test_it_can_be_popped()
    {
        $collection = new Collection(['foo' => 'bar', 'bar' => 'baz']);
        
        $this->assertEquals('baz', $collection->pop());
    }

    public function test_it_can_be_shifted()
    {
        $collection = new Collection(['foo' => 'bar', 'bar' => 'baz']);

        $this->assertEquals('bar', $collection->shift());
    }

    public function test_it_can_be_checked_if_a_value_exists()
    {
        $collection = new Collection(['foo' => 'bar', 'bar' => 'baz']);
        
        $this->assertTrue($collection->in('bar'));
        $this->assertFalse($collection->in('not_a_value'));
    }

    public function test_it_can_be_checked_if_a_key_exists()
    {
        $collection = new Collection(['foo' => 'bar', 'bar' => 'baz']);

        $this->assertTrue($collection->has('foo'));
        $this->assertFalse($collection->has('not_a_value'));
    }

    public function test_it_can_be_sorted()
    {
        $collection = new Collection([3, 9, 1, 20]);
        
        $collection = $collection->sort();

        $this->assertEquals(1, $collection[0]);
    }

    public function test_it_can_be_reverse_sorted()
    {
        $collection = new Collection([3, 9, 1, 20]);

        $collection = $collection->sortDesc();

        $this->assertEquals(20, $collection[0]);
        $this->assertEquals(9, $collection[1]);
        $this->assertEquals(3, $collection[2]);
        $this->assertEquals(1, $collection[3]);
    }

    public function test_it_can_execute_a_callback_if_a_given_boolean_is_true() {
        $collection = new Collection([3, 9, 1, 20]);

        $collection->when(true, function($collection) {
            return $collection->set(25);
        });

        $this->assertCount(5, $collection);

        $collection->when(false, function($collection) {
            return $collection->set(25);
        });

        $this->assertCount(5, $collection);
    }
}