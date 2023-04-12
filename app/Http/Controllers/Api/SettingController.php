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
use App\Models\UserSettings;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
use Twilio\Exception\TwilioException;
use DB;

class SettingController extends Controller
{
    public function userSettings(Request $request)
    {
        $params = $request->all();
        $user   = $request->user();
        if(empty($params)) {
            return $this->errorResponse([], 'Something went wrong!');
        }

        UserSettings::where('user_id', $user->id)->delete();
        $params['user_id'] = $user->id;
        $userSettings      = UserSettings::addUpdateUserSetting($params);

        return $this->successResponse($userSettings, 'Success');
    }

    public function getUserSettings(Request $request)
    {
        $user         = $request->user();
        $userSettings = UserSettings::where('user_id', $user->id)->first();

        return $this->successResponse($userSettings, 'Success');
    }

    public function support(Request $request)
    {
        $messages = array(
            'subject.required'      => 'Subject field is required.',
            'description.required'  => 'Description field is required.',
        );

        $validator = Validator::make($request->all(),[
            'subject'  => 'required',
            'description'  => 'required',
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
        $email  = isset($user->email) ? $user->email : '';

        $data   = [
            'description' => $params['description'],
            'subject'     => 'Dream IL Support : '.$params['subject']
        ];
        $user = User::where('user_type', 'admin')->first();
        $adminEmail = isset($user->email) ? $user->email : '';
        $result = $this->sendMail('contact_support', $data, 
            $adminEmail, $user->email);

        return $this->successResponse([], 'Success');
    }
}