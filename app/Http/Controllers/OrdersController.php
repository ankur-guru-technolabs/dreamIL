<?php

namespace App\Http\Controllers;

use App\Models\AdvertisementCategory;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersLikes;
use App\Models\Categories;
use App\Models\FreePlanSettings;
use App\Models\Order;
use App\Models\Plan;
use DB;

class OrdersController extends Controller
{
    public function subscriptionPlanCreate(){
        $advertisementCategory   = AdvertisementCategory::all();
        return view('subscription_plan.create', ['advertisementCategory' => $advertisementCategory]); 
    }

    public function subscriptionPlanStore(Request $request)
    {
        $messages = array(
            'title.required'       => 'Title field is required.',
            'description.required' => 'Description field is required.',
            'price.required'       => 'Price field is required.',
            'ads.required'         => 'Number of Ads field is required.',
        );

        $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'price'       => 'required',
            'ads'         => 'required',
        ],$messages);

        $params       = $request->all();
        $params['currency_code']  = "ILS";
        $result       = Plan::create($params);

        return redirect('subscription_plan')->withSuccess('Subscription Plan successfully updated.');
    }
    
    public function subscriptionOrders()
    {
        $orders     = Order::with('user')->orderby('id', 'DESC')->get();
        $categories = Categories::all();
        if(!empty($categories)) {
            $categories = $categories->toArray();
            $categories = array_column($categories, 'category', 'id');
        }
        return view('orders.order', ['orders' => $orders,'categories' => $categories]); 
    }

    public function approveOrder($id, Request $request)
    {
    	$order = Order::find($id);

    	if($order) {
    		$order->status = 'Active';
    		$order->save();

    		$user = User::find($order->user_id);
    		if($user) {
    			if($order->category_id == 1) {
    				$user->service_ads   += $order->qty;
    			} else {
    				$user->community_ads += $order->qty;
    			}

				$user->save();
    		}

			$pushData      = [
	            'custom' => ['order_id' => $id]
	        ];

	       // $title = 'Your order #'.$id.' is approved';
            $title = 'Your subscription request has been accepted. Your order id is #'.$id;

            if($user) {
    	        $token                = isset($user->fcm_token) ? $user->fcm_token : '';
    			$noticationStatus     = $this->sendPushNotifcation($token,'Congratulations', $title, $user->id, 0, $pushData, 0, 'custom');
            }

    	}

    	return redirect('subscription_orders')->withSuccess('Order approved successfully.');
    }

	public function rejectOrder($id, Request $request)
    {
    	$order = Order::find($id);

    	if($order) {
    		$order->status = 'Deactivate';
    		$order->save();

    		$user = User::find($order->user_id);
			$pushData      = [
	            'custom' => ['order_id' => $id]
	        ];

	        $title = 'Your order #'.$id.' is rejected';

	        $token                = isset($user->fcm_token) ? $user->fcm_token : '';
			$noticationStatus     = $this->sendPushNotifcation($token,'Dream IL', $title, $user->id, 0, $pushData, 0, 'custom');

    	}

    	return redirect('subscription_orders')->withSuccess('Order rejected successfully.');
    }

    public function subscriptionPlan(Request $request)
    {
        $plan = Plan::all();
        return view('subscription_plan.index', ['plans' => $plan]); 
    }

    public function editSubscriptionPlan($id,Request $request)
    {
        $plan = Plan::where('id',$id)->first();
        return view('subscription_plan.edit', ['plans' => $plan]); 
    }

    public function udpateSubscriptionPlan(Request $request)
    {
        $messages = array(
            'title.required'       => 'Title field is required.',
            'description.required' => 'Description field is required.',
            'price.required'       => 'Price field is required.',
            'ads.required'         => 'Number of Ads field is required.',
        );

        $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'price'       => 'required',
            'ads'         => 'required',
        ],$messages);

        $params       = $request->all();
        $params['id'] = $params['plan_id'];
        $result       = Plan::addUpdatePlan($params);

        return redirect('subscription_plan')->withSuccess('Subscription Plan successfully updated.');
    }
}
