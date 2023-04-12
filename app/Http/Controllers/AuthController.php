<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repository\UserManagementRepository;
use Illuminate\Support\Facades\Http;
use Validator;
use App\Models\User;
use App\Models\UserKids;
use App\Models\UserImages;
use App\Models\Order;
use App\Models\FreePlanSettings;
use App\Models\UsersLikes;
use App\Models\UsersReport;
use App\Models\UsersMessages;
use App\Models\Notifcation;
use App\Models\UserDefaultSettings;
use App\Models\ReportsManagement;
use App\Models\Passion;
use App\Models\Settings;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
use Twilio\Exception\TwilioException;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;

class AuthController extends Controller
{
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $params = $request->all();
        $credentials = $request->only('email', 'password');
        if (Auth::attempt(['email' => $params['email'], 'password' => $params['password'], 'user_type' => 'admin'])) {
            return redirect('/')->withSuccess('You have Successfully loggedin');
        }
  
        return redirect("login")->with('error', 'Oppes! You have entered invalid credentials');

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function profile()
    {
        $user = User::where('user_type', "admin")->first();
        return view('admin.create', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $messages = array(
            'email.required'      => 'Email field is required.',
            'email.email'         => 'Please enter valid email.',
            'first_name.required' => 'First name field is required.',
            'last_name.required'  => 'Last name field is required.',
        );

        $request->validate([
            'email'      => 'required|email',
            'first_name' => 'required',
            'last_name'  => 'required',
        ]);
        $params             = $request->all();
        if(isset($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        }

        $params['id']       = $params['user_id'];
        $result             = User::addUpdateUser($params);

        return redirect('profile')->withSuccess('Profile successfully Added.');
    }
}