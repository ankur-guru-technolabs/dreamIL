<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repository\UserManagementRepository;
use Illuminate\Support\Facades\Http;
use Validator;
use App\Models\User;
use App\Models\UserKids;
use App\Models\UserImages;
use App\Models\Plan;
use App\Models\FreePlanSettings;
use App\Models\Settings;
use App\Models\UsersLikes;
use App\Models\Order;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
use Twilio\Exception\TwilioException;
use DB;

class OrderController extends Controller
{
	public function purchasePlan(Request $request)
	{
        $messages = array(
            'subscription_id.required' => 'Plan Id field is required.',
        );

        $validator = Validator::make($request->all(),[
            'subscription_id'        => 'required',
        ],$messages);

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();

            foreach ($error as $er) {
                $error_msg[] = array($er);
            }

            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params = $request->all();
        $user   = $request->user();
        $plan   = Plan::where('id',$params['subscription_id'])->first();
        $requestParams = [
            'user_id'         => $user->id,
            'category_id'     => isset($plan->category_id) ? $plan->category_id : '',
            'subscription_id' => $params['subscription_id'],
            'amount'          => isset($plan->price) ? $plan->price : '',
            'currency_code'   => isset($plan->currency_code) ? $plan->currency_code : '',
            'qty'             => isset($plan->ads) ? $plan->ads : '',
            'status'          => 'Pending'
        ];

        $result = Order::addUpdateOrder($requestParams);

        if(!$result) {
            return $this->errorResponse([], 'Something went worng!');
        }

        return $this->successResponse($result, 'Success');

	}

    public function getPlanList(Request $request)
    {
        $user                 = $request->user();
        $freeSettings         = FreePlanSettings::get()->pluck('value', 'name');
        $freeLikesCount       = $freeSettings['likes_per_day'];
        $freeReviewLaterCount = $freeSettings['review_later_per_day'];

        $freePlan = [];
        $freePlan['title']                = 'Free Plan';
        $freePlan['description']          = 'Free Plan';
        $freePlan['currency_code']        = 'Israeli Shekel';
        $freePlan['likes_per_day']        = $freeLikesCount;
        $freePlan['review_later_per_day'] = $freeReviewLaterCount;
        $plan                             = Plan::all();

        return $this->successResponse($plan, 'Success');
    }

    public function orderHistory(Request $request)
    {
        $user   = $request->user();
        $orders = Order::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        if($orders) { 
            foreach ($orders as $key => $value) {
                $plan             = Plan::where('id', $value->subscription_id)->first();
                $value->plan_name = isset($plan->title) ? $plan->title : '';
            }
        }

        $successResponse = [
            'service_ads'   => isset($user->service_ads) ? $user->service_ads : '',
            'community_ads' => isset($user->community_ads) ? $user->community_ads : '',
            'orders'        => $orders
        ];

        return $this->successResponse($successResponse, 'Success');
    }
}