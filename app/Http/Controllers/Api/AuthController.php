<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Repository\UserManagementRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserKids;
use App\Models\UserImages;
use App\Models\Settings;
use App\Models\UserSettings;
use App\Models\Temp;
use App\Models\Categories;
use App\Models\ProfileQuestion;
use App\Models\Advertisements;
use App\Models\UserQuestions;
use App\Models\AdvertisementCategory;
use App\Models\GeneralQuestionAnswer;
use App\Models\QuestionAnswer;
use App\Models\Order;
use App\Models\ArticalCategory;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
use Twilio\Exception\TwilioException;
use Session;

class AuthController extends Controller
{
	 /**
     * @auther Jaydip ghetiya (20200716) user register.
     *
     * @param  $request Object
     * @return Array
     */
	Protected function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone'        => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params     = $request->all();
        $otp        = substr(number_format(time() * rand(),0,'',''),0,4);
        $phone      = $params['phone'];
        $response   = $this->sendOtp($phone, $otp);

        if($response) {
            return $this->errorResponse([], $response);
        }

        $user       = User::where('phone', $params['phone'])->first();
        $userStatus = 0; 
        if(empty($user)) {
            $user        = new User();
            $user->phone = $params['phone']; 

            $user->save();
            $userStatus = 1;
        }

        $user->login_otp        = $otp;
        $user->otp_expird_time  = date('Y-m-d H:i:s');
        $user->save();

        $user              = User::find($user->id);
        $tokenResult       = $user->createToken('authToken');
        $data              = [
            'login_otp'  => $otp,
            'token_type' => 'Bearer',
            'session_id' => isset($response->Details) ? $response->Details : '',
            'token'      => $tokenResult->accessToken,
            'userStatus' => $userStatus,
        ];

        return $this->successResponse($data, 'Success');
    }

    public function signIn(Request $request)
    {
        $messages = array(
            'email.required'    => 'Email field is required.',
            'email.email'       => 'Please enter valid email.',
            'password.required' => 'Password field is required.',
        );

        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
            'password'  => 'required',
        ],$messages);

       /* if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }*/
        if ($validator->fails()) {
            $error = $validator->messages()->toArray();
                foreach ($error as $er) {
                    $error_msg[] = array($er);
                }
            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params = $request->all();
        $user   = User::where('email', $params['email'])->where('password', md5($params['password']))->first();

        if(!$user) {
            return $this->errorResponse([], 'Email and password id invalid!');
        }

        if(isset($user->status) && $user->status != 'active') {
            return $this->errorResponse([], 'Your account is suspended or deactivated, please contact to administration!');
        }

        $user = User::with('userQuestion','categories')->where('id', $user->id)->first();

        if(!empty($user)) {
            if($user->userQuestion) {
                foreach ($user->userQuestion as $key => $value) {
                    $question        = ProfileQuestion::where('id', $value->question_id)->first();
                    $answer          = QuestionAnswer::where('id', $value->answer_id)->first();
                    $value->question = isset($question->question) ? $question->question : '';
                    $value->answer   = isset($answer->answer) ? $answer->answer : '';
                }
            }
        }

        $tokenResult     = $user->createToken('authToken');
        $successResponse = [
            'token'          => $tokenResult->accessToken,
            'token_type'     => 'Bearer',
            'user'           => $user
        ];

        $user->api_token = $tokenResult->accessToken;

        if(isset($params['fcm_token'])) {
            $user->fcm_token = $params['fcm_token'];
        }

        if(isset($params['device_type'])) {
            $user->device_type = $params['device_type'];
        }

        $user->save();

        return $this->successResponse($successResponse, 'Login Successfully!');
    }

    Protected function forgetPassword(Request $request)
    {
        $messages = array(
            'email.required'    => 'Email field is required.',
            'email.email'       => 'Please enter valid email.',
        );

        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params = $request->all();
        $user   = User::where('email', $params['email'])->first();

        if(!$user) {
            return $this->errorResponse([], 'Your email is wrong!');
        }

        $otp    = substr(number_format(time() * rand(),0,'',''),0,4);
        $data   = [
            'otp'     => $otp,
            'subject' => 'Forget Password OTP - Dream IL',
        ];

        $temp         = new Temp();
        $temp->key    = $params['email'].'_'.$otp.'forget';
        $temp->value  =  $otp;
        $temp->save();
        $this->sendMail('email_verify', $data, $params['email'], '');
        $msg = 'We have send you verify mail in your email account, Please check and verify!';

        return $this->successResponse([], $msg);
    }

    Protected function updateNewPassword(Request $request)
    {
       $messages = array(
            'new_password.required_with'    => 'Password field is required.',
            'new_password.same'             => 'Your password and confirmation password do not match.',
            'confirm_password.required'     => 'Confirm Password field is required.',
            'key.required'                  => 'Key field is required.',
        );

        $validator = Validator::make($request->all(),[
            'new_password'          => 'required_with:confirm_password|same:confirm_password',
            'confirm_password'      => 'required',
            'key'                   => 'required'
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params = $request->all();
        $temp   = Temp::where('key', $params['key'])->first();

        if(!$temp) {
            return $this->errorResponse([], 'Something went wrong!');
        }

        $user = User::where('email', $temp->value)->first();

        if(!$user) {
            return $this->errorResponse([], 'Something went wrong!');
        }

        $user->password = md5($params['new_password']);
        $user->save();
        $temp->delete();

        return $this->successResponse([], 'Your password successfully changed!');
    }

    Protected function forgetPasswordVerification(Request $request)
    {
        $messages = array(
            'email.required'    => 'Email field is required.',
            'email.email'       => 'Please enter valid email.',
            'otp.required'      => 'Otp field is required.',
        );

        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
            'otp'       => 'required',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params = $request->all();
        $key    = $params['email'].'_'.$params['otp'].'forget';
        $temp   = Temp::where('key', $key)->first();

        if(!$temp) {
            return $this->errorResponse([], 'OTP is invalid!');
        }

        $temp->delete();
        $key         = substr(sha1(rand()), 0, 15);
        $temp        = new Temp();
        $temp->key   = $key;
        $temp->value = $params['email'];
        $temp->save();

        $successResponse = [
            'key' => $key
        ];

        return $this->successResponse($successResponse, 'Success!');
    }

    Protected function changePassword(Request $request)
    {
       $messages = array(
            'new_password.required_with'    => 'Password field is required.',
            'new_password.same'             => 'Your password and confirmation password do not match.',
            'confirm_password.required'     => 'Confirm Password field is required.',
            'old_password.required'         => 'Old Password field is required.',
        );

        $validator = Validator::make($request->all(),[
            'old_password'     => 'required',
            'new_password'     => 'required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $user   = $request->user();
        $params = $request->all();
        if($user->password != md5($params['old_password'])) {
            return $this->errorResponse([], 'Old password is wrong!');
        }

        $user->password = md5($params['new_password']);
        $user->save();

        return $this->successResponse([], 'Password changed successfully!');
    }

    /**
     * @auther Jaydip ghetiya (20200716) user register.
     *
     * @param  $request Object
     * @return Array
     */
    Protected function register(Request $request)
    {
       $messages = array(
            'email.required'                 => 'Email field is required.',
            'first_name.required'            => 'First Name field is required.',
            'last_name.required'            =>  'Last Name field is required.',
            'email.unique'                   => 'Email is already registered',
            'email.email'                    => 'Please enter valid email.',
            'password.required'              => 'Password field is required.',
            'password.confirmed'             => 'Your password and confirmation password do not match.',
            'password_confirmation.required' => 'Confirm Password field is required.',
            'category.required'              => 'Category field is required.',
            'city.required'                  => 'City field is required.',
            'phone.required'                 => 'Phone field is required.',
        );

        $validator = Validator::make($request->all(),[
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
            'city'                  => 'required',
            'category'              => 'required',
            'phone'                 => 'required',
        ],$messages);

/*        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }*/

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();
                foreach ($error as $er) {
                    $error_msg[] = array($er);
                }
            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params = $request->all();
        $otp    = substr(number_format(time() * rand(),0,'',''),0,4);
        $data   = [
            'otp'     => $otp,
            'subject' => 'Email OTP Verification - Dream IL',
        ];

        $temp         = new Temp();
        $temp->key    = $params['email'].'_'.$otp;
        $temp->value  = $otp;
        $temp->save();
        $this->sendMail('email_verify', $data, $params['email'], '');
        $msg = 'We have send you verify mail in your email account, Please check and verify!';

        $temp->save();
        return $this->successResponse($msg, 'Success');
    }

    public function sendOtp($to, $otp)
   {
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $authToken  = env('TWILIO_AUTH_TOKEN');
        $verifySid  = env('TWILIO_VERIFY_SID');
        $twilio     = new Client($accountSid, $authToken);

        try
        {
            $twilio->messages->create(
            $to,
            array(
                'from' => '+16097987679',
                'body' => 'Your Meshaina SMS Verification Code is: '.$otp,
            )
        );
        } catch (\Exception $e) {
            return $e->getMessage();
        }

   }

    /**
     * @auther Jaydip ghetiya (20200720) user confirmation.
     *
     * @param  $request Object
     * @return Array
     */
    Protected function UserConformation(Request $request)
    {
       $messages = array(
            'email.required'                 => 'Email field is required.',
            'first_name.required'            =>  'Frist Name field is required.',
            'last_name.required'             =>  'Frist Name field is required.',
            'email.unique'                   => 'Email is already registered',
            'email.email'                    => 'Please enter valid email.',
            'password.required'              => 'Password field is required.',
            'category.required'              => 'Category field is required.',
            'city.required'                  => 'City field is required.',
            //'questions.required'             => 'Questions field is required.',
            'login_otp.required'             => 'Login OTP field is required.',
            'profile_image.required'         => 'Profile image field is required.',
            'phone.required'                 => 'Phone field is required.',
        );

        $validator = Validator::make($request->all(),[
            'last_name'             => 'required',
            'first_name'            => 'required',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required',
            'city'                  => 'required',
            'category'              => 'required',
           // 'questions'             => 'required',
            'login_otp'             => 'required',
            'profile_image'         => 'required',
            'phone'                 => 'required',
        ],$messages);


        /*if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }*/

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();
                foreach ($error as $er) {
                    $error_msg[] = array($er);
                }
            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params        = $request->all();
        $key           = $params['email'].'_'.$params['login_otp'];
        $temp          = Temp::where('key', $key)->first();
        if(empty($temp)) {
            return $this->errorResponse([], 'Otp is invalid!');
        }

        $imageName = time().'.'.$request->profile_image->extension();  
        $request->profile_image->move(public_path('images'), $imageName);

        $user = new User();
        $user->email_verified       = 1;
        $user->fcm_token            = isset($params['fcm_token']) ? $params['fcm_token'] : '';
        $user->device_type          = isset($params['device_type']) ? $params['device_type'] : '';
        $user->email                = isset($params['email']) ? $params['email'] : '';
        $user->password             = isset($params['password']) ? md5($params['password']) : '';
        $user->category             = isset($params['category']) ? $params['category'] : '';
        $user->first_name           = isset($params['first_name']) ? $params['first_name'] : '';
        $user->last_name            = isset($params['last_name']) ? $params['last_name'] : '';
        $user->phone                = isset($params['phone']) ? $params['phone'] : '';
        $user->city                 = isset($params['city']) ? $params['city'] : '';
        $user->country              = isset($params['country']) ? $params['country'] : '';
        $user->profile_image        = $imageName;
        $user->save();

        $tokenResult                = $user->createToken('Personal Access Token');
        $user->api_token            = $tokenResult->accessToken;
        $user->save();

        $temp->delete();

        if(isset($params['questions'])) {
            foreach ($params['questions'] as $key => $value) {
                $userQuestions =new UserQuestions();
                $userQuestions->user_id = $user->id;
                $userQuestions->question_id = $key;
                $userQuestions->answer_id = $value;
                $userQuestions->save();
            }
        }

        $user = User::with('userQuestion','categories')->where('id', $user->id)->first();

        if(!empty($user)) {
            if($user->userQuestion) {
                foreach ($user->userQuestion as $key => $value) {
                    $question        = ProfileQuestion::where('id', $value->question_id)->first();
                    $answer          = QuestionAnswer::where('id', $value->answer_id)->first();
                    $value->question = isset($question->question) ? $question->question : '';
                    $value->answer   = isset($answer->answer) ? $answer->answer : '';
                }
            }
        }

        $userSettings =new UserSettings();
        $userSettings->user_id           = $user->id;
        $userSettings->show_notification = 1;
        $userSettings->save();

        $successResponse = [
            'token'          => $tokenResult->accessToken,
            'token_type'     => 'Bearer',
            'user'           => $user
        ];

        return $this->successResponse($successResponse, 'User Verified Successfully!');
    }

    public function activeFreePlan($userId = 0, $month = 3)
    {
        $params                    = [];
        $month                     = '+'.$month." month";
        $params['start_date']      = date("Y-m-d");
        $params['end_date']        = date("Y-m-d", strtotime($month));
        $params['payment_status']  = 'Paid';
        $params['status']          = 'Active';
        $params['user_id']         = $month;
        $params['month']           = $userId;
        $params['subscription_id'] = 1;

        $order = Order::addUpdateOrder($params);
    }

    public function updateProfile(Request $request)
    {
        $user      = $request->user();
        $messages = array(
            'email.required'                 => 'Email field is required.',
            'first_name.required'            =>  'First Name field is required.',
            'last_name.required'             =>  'Last Name field is required.',
            'email.unique'                   => 'Email is already registered',
            'email.email'                    => 'Please enter valid email.',
            'category.required'              => 'Category field is required.',
            'city.required'                  => 'City field is required.',
            'phone.required'                 => 'Phone field is required.',
        );

        $validator = Validator::make($request->all(),[
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required|email|unique:users,email,'.$user->id,
            'city'                  => 'required',
            'category'              => 'required',
            'phone'                 => 'required',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params        = $request->all();
        if($user->email != $params['email']) {
            // $params['email_verified'] = 0;
        } else {
            $params['email_verified'] = 1;
        }

        if(isset($params['profile_image'])) {
            $imageName               = time().'.'.$request->profile_image->extension();  
            $request->profile_image->move(public_path('images'), $imageName);
            $params['profile_image'] = $imageName;
        }

        $params['id'] = $user->id;
        $result       = User::addUpdateUser($params);

        if(isset($params['questions'])) {
            UserQuestions::where('user_id',$user->id)->delete();
            foreach ($params['questions'] as $key => $value) {
                $userQuestions =new UserQuestions();
                $userQuestions->user_id = $user->id;
                $userQuestions->question_id = $key;
                $userQuestions->answer_id = $value;
                $userQuestions->save();
            }
        }
        $user = User::with('userQuestion','categories')->where('id', $user->id)->first();

        if(!empty($user)) {
            if($user->userQuestion) {
                foreach ($user->userQuestion as $key => $value) {
                    $question        = ProfileQuestion::where('id', $value->question_id)->first();
                    $answer          = QuestionAnswer::where('id', $value->answer_id)->first();
                    $value->question = isset($question->question) ? $question->question : '';
                    $value->answer   = isset($answer->answer) ? $answer->answer : '';
                }
            }
        }

        $msg  = 'User profile Updated successfully!';

        //  if((isset($user->email_verified) && $user->email_verified  == 0)) {
        if($user->email != $params['email']){
            $otp  = substr(number_format(time() * rand(),0,'',''),0,4);
            $data = [
                'otp'     => $otp,
                'subject' => 'Email OTP Verification - Dream IL',
            ];
            $user->email_verified_otp = $otp;
            $user->save();
            $this->sendMail('email_verify', $data, $params['email'], '');
            $msg = 'We have send you verify mail in your email account, Please check and verify!';
            $user['user_update_email'] = 1;
            $user['updated_email'] = $params['email'];
        }else{
            $user['user_update_email'] = 0;
            $user['updated_email'] = '';
        }

        $successResponse = [
            'user'           => $user
        ];
        if($result) {
            return $this->successResponse($successResponse, $msg);
        }

        return $this->errorResponse([], 'Something went wrong!');
    }

    public function emailVerification(Request $request)
    {
        $user  = $request->user();
        $id    = $user->id;
        $validator = Validator::make($request->all(),[
            'email' => 'required|unique:users,email,'.$id,
            'otp'   => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params = $request->all();
        if($user->email_verified_otp == $params['otp']) {
            $user->email              = $params['email'];
            $user->email_verified_otp = 0;
            $user->email_verified     = 1;
            $user->save();

            return $this->successResponse([], 'Success');
        } 
        return $this->errorResponse([], 'Invalid OTP!');


    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'        => 'required|email',
        ]);

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();
                foreach ($error as $er) {
                    $error_msg[] = array($er);
                }
            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params      = $request->all();
        $otp         = substr(number_format(time() * rand(),0,'',''),0,4);
        $temp        = new Temp();
        $temp->key   = $params['email'].'_'.$otp;
        $temp->value =  $otp;
        $temp->save();
        $data        = [
            'otp'     => $otp,
            'subject' => 'Email OTP Verification - Dream IL',
        ];
        $this->sendMail('email_verify', $data, $params['email'], '');
        $msg         = 'We have send you verify mail in your email account, Please check and verify!';

        $temp->save();

        return $this->successResponse($msg, 'Success');

    }

    public function resendOtpUpdateUser(Request $request)
    {
        $user      = $request->user();
        $validator = Validator::make($request->all(),[
            'email'        => 'required|email',
        ]);

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();
                foreach ($error as $er) {
                    $error_msg[] = array($er);
                }
            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params      = $request->all();
        $otp         = substr(number_format(time() * rand(),0,'',''),0,4);
        $data        = [
            'otp'     => $otp,
            'subject' => 'Email OTP Verification - Dream IL',
        ];
        $this->sendMail('email_verify', $data, $params['email'], '');
        $msg         = 'We have send you verify mail in your email account, Please check and verify!';

        $user = User::where('id', $user->id)->first();
        $user->email_verified_otp = $otp;
        $user->save();
        return $this->successResponse($msg, 'Success');

    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        $user = User::with('userQuestion','categories')->where('id', $user->id)->first();

        if(!empty($user)) {
            if($user->userQuestion) {
                foreach ($user->userQuestion as $key => $value) {
                    $question        = ProfileQuestion::where('id', $value->question_id)->first();
                    $answer          = QuestionAnswer::where('id', $value->answer_id)->first();
                    $value->question = isset($question->question) ? $question->question : '';
                    $value->answer   = isset($answer->answer) ? $answer->answer : '';
                }
            }
        }

        $successResponse = [
            'user'           => $user
        ];
        return $this->successResponse($successResponse, 'Success');
    }

    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'login_id'   => 'required',
            'login_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params = $request->all();
        if(isset($params['login_type']) && $params['login_type'] == 'facebook') {
            $user  = User::where('fb_id', $params['login_id'])->first();
            if(empty($user)) {
                $user = new User();
            }
            $user->fb_id      =  $params['login_id'];
        } elseif(isset($params['login_type']) && $params['login_type'] == 'apple') {
            $user = User::where('apple_id', $params['login_id'])->first();
            if(empty($user)) {
                $user = new User();
            }
            $user->apple_id  =  $params['login_id'];
        } elseif(isset($params['login_type']) && $params['login_type'] == 'google') {
            $user  = User::where('google_id', $params['login_id'])->first();
            if(empty($user)) {
                $user = new User();
            }
            $user->google_id  =  $params['login_id'];
        }

        $tokenResult      = $user->createToken('Personal Access Token');
        $user->api_token  = $tokenResult->accessToken;
        $user->login_type = $params['login_type'];

        if(isset($params['email'])) {
        	$user->email = $params['email'];
        }

        if(isset($params['last_name'])) {
        	$user->first_name = $params['first_name'];
        }

        if(isset($params['last_name'])) {
        	$user->first_name = $params['last_name'];
        }

        if(isset($params['device_type'])) {
        	$user->device_type = $params['device_type'];
        }

        if(isset($params['fcm_token'])) {
        	$user->fcm_token = $params['fcm_token'];
        }

        $user->save();

        $user = User::with('UserImages','userKids')->where('id', $user->id)->first();
        $successResponse = [
            'token'      => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'user'       => $user
        ];

        return $this->successResponse($successResponse, 'Success');
    }

    public function logout(Request $request)
    {
         $user = $request->user();

         Session::flush();

         $user->device_type = '';
         $user->fcm_token   = '';
         $user->api_token   = '';

         $user->save();

         return $this->successResponse([], 'Logout Successfully');
    }

    public function updateUserLastseen(Request $request)
    {
        $user = $request->user();
        $messages = array(
            'time.required'  => 'Time field is required.',
        );
        $validator = Validator::make($request->all(),[
            'time'         => 'required',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }
        $params = $request->all();
        $user->lastseen = $params['time'];
        $user->save();

        $user = User::with('userKids')->where('id', $user->id)->first();
        return $this->successResponse($user, 'Success');
    }

    public function getUserDetails(Request $request)
    {
        $messages = array(
            'user_id.required'  => 'User id is required.',
        );
        $validator = Validator::make($request->all(),[
            'user_id'         => 'required',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params = $request->all();
        $user = User::with('userKids')->where('id', $params['user_id'])->first();

        return $this->successResponse($user, 'Success');
    }

    public function getCategories(Request $request)
    {
        $params     = $request->all();
        $type       = '';
        if(isset($params['type']) && $params['type'] == 'other') {
            $type = 'other';
        } elseif(isset($params['type']) && $params['type'] == 'user') {
            $type = 'user';
        } else {
            $type = '';
        }
        if(!empty($type)) {
            $categories            = Categories::where('type',$type)->get();
        } else {
            $categories            = Categories::all();
        }
        $advertisementCategory = AdvertisementCategory::all();
        $artical = ArticalCategory::all();
        $successResponse = [
            'category' => $categories,
            'advertisement_types' => $advertisementCategory,
            'article_category' => $artical,
        ];
        return $this->successResponse($successResponse, 'Success');
    }

    public function getQuestions(Request $request)
    {
        $messages = array(
            'category_id.required'  => 'Category id is required.',
        );
        $validator = Validator::make($request->all(),[
            'category_id'         => 'required',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $params    = $request->all();
        $questions = ProfileQuestion::with('questionAnswer')->where('cat_id', $params['category_id'])->get();

        return $this->successResponse($questions, 'Success');
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        GeneralQuestionAnswer::where('user_id', $user->id)->delete();
        UserQuestions::where('user_id', $user->id)->delete();
        User::where('id', $user->id)->delete();

        return $this->successResponse([], 'Success');
    }

    public function userWalletDetails(Request $request)
    {
        $user     = $request->user();
        $response = [
            'service_ads'   => isset($user->service_ads) ? $user->service_ads : '',
            'community_ads' => isset($user->community_ads) ? $user->community_ads : '',
        ];
        return $this->successResponse($response, 'Success');
    }
}