<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Website;

class WebsiteController extends Controller
{
	
	public function index()
	{

		$websites = Website::orderBy('id', 'asc')->get();

		return response()->json([
			'websites' => $websites,
			'status'   => $websites->count() > 0 ? true : false
		]);

	}

}
