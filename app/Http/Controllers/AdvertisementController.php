<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Categories;
use App\Models\User;
use App\Models\Advertisements;

class AdvertisementController extends Controller
{
	public function getAdvertisement()
	{
		$advertisements = Advertisements::with(['advertisementImages','advertisementCategory','user'])->orderBy('id', 'DESC')->get();
        $advertisementsArray = [];
		if($advertisements) {
			$advertisementsArray = $advertisements->toArray(); 
		}
		return view('advertisement.index', ['advertisements' => $advertisementsArray]);
	}

	public function viewAdvertisement($id)
	{
		$advertisement = Advertisements::with(['advertisementImages','advertisementCategory'])->where('id', $id)->first();
		return view('advertisement.view', ['advertisement' => $advertisement]);
	}

	public function viewAdvertisementDetails($id)
	{
		$advertisement = Advertisements::with(['advertisementImages','advertisementCategory'])->where('id', $id)->first();
		return view('advertisement.view_details', ['advertisement' => $advertisement]);
	}

	public function updateAdvertisement($id, Request $request)
	{
		$messages = array(
            'status.required' => 'Status field is required.',
        );

        $request->validate([
            'status'          => 'required',
        ],$messages);

		$params         = $request->all();
		$params['id']   = $id;
		if($params['status'] != 'Approved') {
			$params['start_date'] = '';
			$params['end_date']   = '';
		}else{
			$messages = array(
            	'start_date.required' => 'Start date field is required.',
            	'end_date.required' => 'End date field is required.',
            	'end_date.after_or_equal' => 'The end date must be after or equal to the start date',
			);

			$request->validate([
				'start_date'          => 'required',
				'end_date'          => 'required|date_format:d/m/Y|after_or_equal:start_date',
			],$messages);
		}
		$advertisement = Advertisements::find($id);
		$result        = Advertisements::addUpdateAdvertisements($params);
		$userId        = isset($advertisement->user_id) ? $advertisement->user_id : 0;
		$user          = User::find($userId);
		$pushData      = [
            'custom' => ['advertisement_id' => $id]
        ];

        if($params['status'] == 'Approved') {
	        $token = isset($user->fcm_token) ? $user->fcm_token : '';
	        $title = 'Your '.$advertisement->name.' advertisement is published now.';
			$noticationStatus     = $this->sendPushNotifcation($token,'Advertisement Published', $title, $user->id, 0, $pushData, 0, 'advertisement');
		}

		if($params['status'] == 'Rejected') {
			if($advertisement->category_id == 1) {
				$user->service_ads += 1; 
			} else {
				$user->community_ads += 1; 
			}
			$user->save();
		}

		if($result) {
            return redirect('advertisement')->withSuccess('Advertisement Successfully Updated.');
        }
	}

	public function deleteAdvertisement($id)
	{
		Advertisements::where('id', $id)->delete();
		return redirect('advertisement')->withSuccess('Advertisement Successfully Deleted.');
	}
}