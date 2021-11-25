<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Post;
use App\Models\Subscription;

use App\Events\NewPostCreated;

use DB;

class PostController extends Controller
{

	public function index()
	{

		$posts = Post::join('websites', 'website.id','=','posts.website_id')->orderBy('posts.id', 'asc')
		->get([
			'posts.*',
			'website.website_name',
			'websites.website_description'
		]);

		return response()->json([
			'posts' 	=> $posts,
			'status'   	=> $posts->count() > 0 ? true : false
		]);

	}

	public function createNewPost(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'post_title' => 'required|unique:posts|max:255',
			'post_description' => 'required',
			'website_id' => 'required',
		],[
			'post_title.required' => 'You need to provide the title of the post',
			'post_title.unique' => 'You need to provide a unique title of the post',
			'post_description.required' => 'You need to provide a brief description the post',
			'website_id.required' => 'You need to specify the website you are posting content for',
		]);

		if ($validator->fails()) {
			return response()->json([
				'status'	=> false,
				'message'	=> 'Validation error',
				'data'		=> $validator->errors()->all()
			]);
		}
		
		try {

			$data = null;
			$status = null;
			$message = null;

			DB::transaction(function() use ($request, &$data, &$status, &$message) {

				$post = Post::create([
					'website_id'		=> $request->website_id,
					'post_title'		=> $request->post_title,
					'post_description'	=> $request->post_description,
				]);

				$data = $post;
				$status = true;
				$message = 'Post created successfully';

				$subscriptions = Subscription::where('website_id', $request->website_id)
									->get()
									->pluck('user_id')

				$subscribers User::whereIn('id', $subscriptions->all())
								->get();

				event( new NewPostCreated($subscribers, $post) );

			});
			
		} catch (\Exception $e) {

			$data = null;
			$status = false;
			$message = 'Post creation failed, plesase try again';
			
		}

		return response()->json([
			'status'	=> $status,
			'message'	=> $message,
			'data'		=> $data
		]);

	}
	
}
