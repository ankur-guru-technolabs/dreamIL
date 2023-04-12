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
use App\Models\Advertisements;
use App\Models\AdvertisementImages;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
use Twilio\Exception\TwilioException;
use DB;

class AdvertisementsController extends Controller
{
	public function createAdvertisements(Request $request)
	{
		$messages = array(
            'name.required'         => 'Name field is required.',
            'category_id.required'  => 'Category id field is required.',
            'description.required'  => 'Description field is required.',
            'action_name.required'  => 'Action name field is required.',
            'action_value.required' => 'Action value field is required.',
        );

        $validator = Validator::make($request->all(),[
            'name'         => 'required',
            'category_id'  => 'required',
            'description'  => 'required',
            'action_name'  => 'required',
            'action_value' => 'required',
        ],$messages);

        if ($validator->fails()) {
        	$error = $validator->messages()->toArray();

            foreach ($error as $er) {
                $error_msg[] = array($er);
            }

            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $user              = $request->user();
        $params            = $request->all();
        $params['user_id'] = $user->id;

        if($params['category_id'] == 1) {
            if($user->service_ads < 0) {
                return $this->errorResponse(['is_plan' => 1], 'Please purchase your plan');
            }
        }

        if($params['category_id'] == 2) {
            if($user->community_ads < 0) {
                return $this->errorResponse(['is_plan' => 1], 'Please purchase your plan');
            }
        }

        $result = Advertisements::addUpdateAdvertisements($params);

        if(isset($params['images'])) {
            $images = [];
            if($files=$request->file('images'))
            {
                foreach($files as $file) {
                    $imageName       = rand().'.'.$file->extension();
                    $file->move(public_path('advertisements_images'), $imageName);
                    $images[] = [
                        'image'   => $imageName,
                        'advertisement_id' => $result->id,
                    ];
                }
            }
            if(!empty($images)) {
                AdvertisementImages::where('advertisement_id', $result->id)->delete();
                AdvertisementImages::insert($images);
            }
        }

        if($params['category_id'] == 1) {
            $user->service_ads -= 1;
            $user->save();
        } else {
            $user->community_ads -= 1;
            $user->save();
        }

        $advertisement = Advertisements::with('advertisementImages')->where('id', $result->id)->first();
        if(!$result) {
            return $this->errorResponse([], 'Something went wrong!');
        }

        return $this->successResponse($advertisement, 'Success');
	}

    public function getUserAdvertisements(Request $request)
    {
        $messages = array(
            'category_id.required'  => 'Category id field is required.',
        );

        $validator = Validator::make($request->all(),[
            'category_id'  => 'required',
        ],$messages);

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();

            foreach ($error as $er) {
                $error_msg[] = array($er);
            }

            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $user          = $request->user();
        $params        = $request->all();
        $advertisement = Advertisements::with('advertisementImages')->where('category_id', $params['category_id'])->where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        return $this->successResponse($advertisement, 'Success');
    }

    public function getAdvertisements(Request $request)
    {
        $messages = array(
            'category_id.required'  => 'Category id field is required.',
        );

        $validator = Validator::make($request->all(),[
            'category_id'  => 'required',
        ],$messages);

        $params        = $request->all();
        $page          = isset($params['page']) ? $params['page'] : 1;
        $pageSize      = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $advertisement = Advertisements::with('advertisementImages')->where('status', 'Approved')->where('category_id', $params['category_id'])->orderBy('updated_at', 'desc')->paginate($pageSize, ['*'], 'page', $page);
        return $this->successResponse($advertisement, 'Success');
    }

    public function getAdvertisementDetails($id, Request $request)
    {
        $advertisements = Advertisements::with('advertisementImages')->where('id', $id)->first();
        return $this->successResponse($advertisements, 'Success');
    }
}