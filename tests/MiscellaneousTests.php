<?php
	
	namespace Healthia\Nookal\Tests;
	
	use Healthia\Nookal\GraphQL\Contracts\IClassConvertible;
	use Healthia\Nookal\GraphQL\Convert;
	use Throwable;
	
	class NestedObject
	{
		public float $number;
	}
	
	class ParentObject implements IClassConvertible
	{
		public $zero;
		public int $one;
		public int $two;
		public string $three;
		public NestedObject $nested;
		public bool $custom;
		
		function useCustomConverter(string $name): bool
		{
			return $name === 'custom';
		}
		
		function convertProperty(string $name, $value)
		{
			if ($name === 'custom')
			{
				return strtolower($value) === 'true';
			}
			return $value;
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
				'custom' => 'TRUE'
			];
			
			/** @var ParentObject $object */
			$object = Convert::arrayToClass($data, ParentObject::class);
			
			$this->assertIsArray($object->zero);
			$this->assertSame(1, $object->one);
			$this->assertSame(2, $object->two);
			$this->assertSame('3', $object->three);
			$this->assertEqualsWithDelta(3.14, $object->nested->number, 0.01);
			$this->assertSame(true, $object->custom);
		}
	}
