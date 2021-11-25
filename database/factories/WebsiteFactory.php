<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Website;

class WebsiteFactory extends Factory
{

	use UniqueValueCheck;

	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Website::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'website_name'  		=> $this->getUniqueValue($this->faker, $this->websites()),
            'website_description'   => $this->faker->sentence,
		];
	}

	private function websites() {

	        return [
	            'https://www.jumia.ug',
	            'https://www.google.com',
	            'https://www.intanode.co.ug'
	        ];

	    }
}

