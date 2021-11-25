<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Subscription;

use DB

class SubscriptionController extends Controller
{
    

    public function subscribeToWebsite(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
            'website_id'    => 'required',
        ],[
            'user_id.required' => 'You need to provide/select a user subscribing to the website',
            'website_id.required' => 'You need to specify the website you are subscribing for',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'Validation error',
                'data'      => $validator->errors()->all()
            ]);
        }

        try {

            $data = null;
            $status = null;
            $message = null;

            DB::transaction(function() use ($request, &$data, &$status, &$message) {

                $subscription = Subscription::create([
                    'website_id'        => $request->website_id,
                    'user_id'       => $request->user_id,
                ]);

                $data = $subscription;
                $status = true;
                $message = 'You have successfully subscribed to the website';

            });

            
            
        } catch (\Exception $e) {

            $data = null;
            $status = false;
            $message = 'Failed to subscribe to the website, plesase try again';
            
        }

        return response()->json([
            'status'    => $status,
            'message'   => $message,
            'data'      => $data
        ]);

    }
}
