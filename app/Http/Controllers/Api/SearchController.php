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
use App\Models\Order;
use App\Models\FreePlanSettings;
use App\Models\UsersLikes;
use App\Models\UsersReport;
use App\Models\QuestionAnswer;
use App\Models\Questions;
use App\Models\Categories;
use App\Models\GeneralQuestionAnswer;
use App\Models\Notifcation;
use App\Models\UserDefaultSettings;
use App\Models\ReportsManagement;
use App\Models\Passion;
use App\Models\ProfileQuestion;
use App\Models\Settings;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
use Twilio\Exception\TwilioException;
use DB;

class SearchController extends Controller
{
    public function getUserDashboardData(Request $request)
    {
    	 $messages = array(
            'category_id.required' => 'Category id field is required.',
        );

        $validator = Validator::make($request->all(),[
            'category_id' => 'required',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $user          = $request->user();		
        $params        = $request->all();
        $page          = isset($params['page']) ? $params['page'] : 1;
        $pageSize      = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $users         = User::with(['userQuestion'=>function($q){
                            $q->orderBy('id','desc');
                         },'categories','generalQuestionAnswer'])->where('id', '!=' , $user->id)->where('user_type','user')->where('category', $params['category_id'])->paginate($pageSize, ['*'], 'page', $page);

        if(!empty($users)) {
            foreach ($users as $key => $user) {
                if($user->userQuestion) {
                    foreach ($user->userQuestion as $key => $value) {
                        $question        = ProfileQuestion::where('id', $value->question_id)->first();
                        $answer          = QuestionAnswer::where('id', $value->answer_id)->first();
                        $value->is_default = isset($question->question) ? $question->is_default : 'No';                        $value->question = isset($question->question) ? $question->question : '';
                        $value->answer   = isset($answer->answer) ? $answer->answer : '';
                    }
                }
            }
        }

        if(!empty($users)) {
            foreach ($users as $key => $user) {
                if(isset($user->generalQuestionAnswer) && !empty($user->generalQuestionAnswer)) {
                    foreach ($user->generalQuestionAnswer as $key => $answer) {
                        $userData                = User::with('categories')->where('id', $answer->user_id)->first();
                        $question                = Questions::where('id', $answer->question_id)->first();
                        $categoryId              = isset($question->category_id) ? $question->category_id : 0;
                        $category                = Categories::find($categoryId);
                        $answer->question        = isset($question->question) ? $question->question : '';
                        $answer->category        = isset($category->category) ? $category->category : '';
                        $answer->is_hide_details = isset($userData->categories->is_hide_details) ? $userData->categories->is_hide_details : 0;

                    }
                }
            }
        }
        $settings = Settings::all();
        if($settings) {
            $settings                 = $settings->toArray();
            $settings                 = array_column($settings, 'value', 'key');
            $users                    = $users->toArray();
            $users['android_version'] = isset($settings['android_version']) ? $settings['android_version'] : '';
            $users['ios_version']     = isset($settings['ios_version']) ? $settings['ios_version'] : '';

        }

        return $this->successResponse($users, 'success');
    }

    public function getQuestionsDashboardData(Request $request)
    {
        $messages = array(
            'category_id.required' => 'Category id field is required.',
        );

        $validator = Validator::make($request->all(),[
            'category_id' => 'required',
        ],$messages);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors());
        }

        $user          = $request->user();
        $params        = $request->all();
        $page          = isset($params['page']) ? $params['page'] : 1;
        $pageSize      = isset($params['pageSize']) ? $params['pageSize'] : 10;
        
        $questions     = Questions::with(['generalQuestionAnswer'=>function($q){
                            $q->orderBy('id','desc');
                         }])->where('category_id', $params['category_id'])->paginate($pageSize, ['*'], 'page', $page);
        if(!empty($questions)) {
            foreach ($questions as $key => $value) {
                $generalQuestionAnswer = GeneralQuestionAnswer::where('user_id', $user->id)->where('question_id', $value->id)->first();
                if($generalQuestionAnswer) {
                    $value->is_answer = 1;
                } else {
                    $value->is_answer = 0;
                }
                if(isset($value->generalQuestionAnswer) && !empty($value->generalQuestionAnswer)) {
                    foreach ($value->generalQuestionAnswer as $key => $answer) {
                        $userData                = User::with(['categories','userQuestion'])->where('id', $answer->user_id)->first();
                        $userQuestionArray = [];
                        if(!empty($userData)) {
                            if($userData->userQuestion) {
                                foreach ($userData->userQuestion as $key => $value) {
                                    $question        = ProfileQuestion::where('id', $value->question_id)->first();
                                    $questionAnswer  = QuestionAnswer::where('id', $value->answer_id)->first();;
                                    $userQuestionArray[] = [
                                        'id' => $value->id,
                                        'user_id' => $value->user_id,
                                        'question_id' => $value->question_id,
                                        'answer_id' => $value->answer_id,
                                        'question' => isset($question->question) ? $question->question : '',
                                        'answer' => isset($questionAnswer->answer) ? $questionAnswer->answer : ''
                                    ];
                                }
                            }
                        }

                        $answer->first_name      = isset($userData->first_name) ? $userData->first_name : '';
                        $answer->last_name       = isset($userData->last_name) ? $userData->last_name : '';
                        $image                   = isset($userData->profile_image) ? $userData->profile_image : '';
                        $answer->profile_image   = $image;
                        $answer->city            = isset($userData->city) ? $userData->city : '';
                        $answer->category        = isset($userData->categories->category) ? $userData->categories->category : '';
                        $answer->is_hide_details = isset($userData->categories->is_hide_details) ? $userData->categories->is_hide_details : 0;
                        $answer->user_question   = $userQuestionArray;

                    }
                }
            }
        }
        return $this->successResponse($questions, 'success');
    }

    public function userQuestionAnswer(Request $request)
    {
        $messages = array(
            'question_id.required' => 'Question id field is required.',
            'answer.required'      => 'Answer id field is required.',
        );

        $validator = Validator::make($request->all(),[
            'question_id' => 'required',
            'answer'      => 'required',
        ],$messages);

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();

            foreach ($error as $er) {
                $error_msg[] = array($er);
            }

            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params            = $request->all();
        $user              = $request->user();
        $params['user_id'] = $user->id;
        $result            = GeneralQuestionAnswer::addUpdateQuestion($params);

        if(!$result) { 
            return $this->errorResponse([], 'Something went wrong!');
        }

        return $this->successResponse([], 'success');
    }

    public function getNotifcation(Request $request)
    {
        $user              = $request->user();
        $unreadNotifcation = Notifcation::where('receiver_id', $user->id)->where('status', 'unread')->get();
        $readNotifcation   = Notifcation::where('receiver_id', $user->id)->where('status', 'read')->get();
        if($unreadNotifcation) {
            foreach ($unreadNotifcation as $key => $value) {
                $value->icon = url('')."/notifcation_logo.png";
            }
        }

        if($readNotifcation) {
            foreach ($readNotifcation as $key => $value) {
                $value->icon = url('')."/notifcation_logo.png";
            }
        }

        $successResponse = [
            'unread_notification' => $unreadNotifcation,
            'read_notification'   => $readNotifcation,
        ];

        Notifcation::where('receiver_id', $user->id)->update(['status' => 'read']);

        return $this->successResponse($successResponse, 'success');
    }

    public function updateUserQuestionAnswer(Request $request)
    {
        $messages = array(
            'answer_id.required' => 'Answer id field is required.',
            'answer.required' => 'Answer field is required.',
        );

        $validator = Validator::make($request->all(),[
            'answer_id' => 'required',
            'answer'      => 'required',
        ],$messages);

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();

            foreach ($error as $er) {
                $error_msg[] = array($er);
            }

            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params            = $request->all();
        $user              = $request->user();
        $params['id']      = $params['answer_id'];
        $result            = GeneralQuestionAnswer::addUpdateQuestion($params);
        $user              = User::with('userQuestion','categories')->where('id', $user->id)->first();

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
        if(!$result) { 
            return $this->errorResponse([], 'Something went wrong!');
        }

        return $this->successResponse($user, 'success');
    }

    public function readAllNotifcation(Request $request)
    {
     	Notifcation::where('receiver_id', $request->user()->id)->update(['status' => 'read']);
        return $this->successResponse('', 'Success');
    }
}