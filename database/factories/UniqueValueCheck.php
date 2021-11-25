<?php 

namespace Database\Factories;

trait UniqueValueCheck 
{

	protected $tested = [];

	public function getUniqueValue( $faker, $values ) {

		$unique = false;

		do {

			$value = $faker->randomElement($values);

			if (in_array($value, $this->tested)) {
				continue;
			} else {
				$unique = true;
			}

			$this->tested[] = $value;

		} while (! $unique);

		return $value;

	}
}