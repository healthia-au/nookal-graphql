<?php
	/** @noinspection PhpMissingFieldTypeInspection */
	
	namespace Healthia\Nookal\Tests;
	
	use Healthia\Nookal\GraphQL\Utility\ArrayConvertible;
	use Healthia\Nookal\GraphQL\Utility\Convert;
	use Throwable;
	
	class NestedObject
	{
		public float $number;
	}
	
	class ParentObject extends ArrayConvertible
	{
		public $zero;
		public int $one;
		public int $two;
		public string $three;
		public NestedObject $nested;
		public bool $custom;
		
		/** @var NestedObject[] $items */
		public array $items;
		
		/** @var int[] $ints */
		public array $ints;
		
		protected const ArrayPropertyTypes = [
			'items' => NestedObject::class,
			'ints' => 'integer'
		];
		
		public function hasPropertyConverter(string $name): bool
		{
			return $name === 'custom';
		}
		
		function convertValueForProperty(string $name, $value)
		{
			switch($name)
			{
				case 'custom':
					return strtolower($value) === 'true';
					
				default:
					return $value;
			}
		}
	}
	
	class MiscellaneousTests extends TestCase
	{
		/**
		 * @throws Throwable
		 */
		function testBasicPropertyAssignment()
		{
			$data = [
				'zero' => [ 0 ],
				'one' => '1',
				'two' => 2,
				'three' => 3,
				'nested' => [
					'number' => '3.14'
				],
				'custom' => 'TRUE',
				'items' => [
					[ 'number' => '3.14' ],
					[ 'number' => '1.5' ]
				],
				'ints' => [
					'1',
					2.1,
					3,
					true
				]
			];
			
			/** @var ParentObject $object */
			$object = Convert::arrayToClass($data, ParentObject::class);
			
			$this->assertIsArray($object->zero);
			$this->assertSame(1, $object->one);
			$this->assertSame(2, $object->two);
			$this->assertSame('3', $object->three);
			$this->assertEqualsWithDelta(3.14, $object->nested->number, 0.01);
			$this->assertSame(true, $object->custom);
			$this->assertCount(2, $object->items);
			$this->assertEqualsWithDelta(1.5, $object->items[1]->number, 0.01);
			$this->assertCount(4, $object->ints);
			$this->assertSame(1, $object->ints[0]);
			$this->assertSame(2, $object->ints[1]);
			$this->assertSame(3, $object->ints[2]);
			$this->assertSame(1, $object->ints[3]);
		}
	}
