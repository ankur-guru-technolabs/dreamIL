<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Mail;
use App\Models\Notifcation;
use App\Models\UserSettings;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function errorResponse($data = [], $msg = '')
    {
        $response = [
            'status'      => 0,
            'message'     => !empty($msg) ? $msg : 'error',
            'data'        => !empty($data) ? $data : null
        ];

        return response()->json($response);

    }

    /**
     * @auther Jaydip ghetiya (20200716) success response.
     *
     * @param  $data Array
     * @param  $msg String
     * @return Json
     */
    public function successResponse($data = [], $msg = '')
    {
        $response = [
            'status'      => 1,
            'message'     => !empty($msg) ? $msg : 'error',
            'data'        => !empty($data) ? $data : null
        ];

        return response()->json($response);
    }

    /**
     * @auther Jaydip ghetiya (20200716) mail send.
     *
     * @param  $view Html
     * @param  $data Array
     * @param  $to Mixed
     * @param  $from Mixed
     * @return bool
     */
    public function sendMail($view = '', $data = [], $to = '', $from = '', $attechMent = '')
    {
        if(empty($view) || empty($to)) {
            return false;
        }

        $subject = isset($data['subject']) ? $data['subject'] : '';
        $from    = !empty($from) ? $from : 'jaydip.gurutechnolabs@gmail.com';
        $status  = Mail::send($view, $data, function($message) use ($to, $from, $subject, $attechMent) {
            $message->to($to, '')->subject($subject);
            $message->from($from,'Dream IL App');
        });
      
        return true;
    }

        /**
     * @auther Jaydip ghetiya (20200716) Seller notifcation.
     *
     * @param  $view Html
     * @param  $data Array
     * @param  $to Mixed
     * @param  $from Mixed
     * @return bool
     */
    public function sendSellerPushNotifcation($token = '', $title = '', $body = '', $userId = '', $senderId = 0, $unreadMsgCount = 0)
    {
        if(empty($token)) {
            return false;
        }

        $optionBuilder                 = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder           = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)->setSound('default')->setBadge($unreadMsgCount);
        $dataBuilder                   = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data','setting' => $title]);

        $option                        = $optionBuilder->build();
        $notification                  = $notificationBuilder->build();
        $data                          = $dataBuilder->build();
        $response                      = FCM::sendTo($token, $option, $notification, $data);

        if(!$response) {
            return false;
        }

        //store notication history
        $params = [
            'seller_id'            => $userId,
            'sender_id'            => $senderId,
            'type'                 => $title,
            'message'              => $body,
            'status'               => 1,
            'notification_type'    => $type,
        ];

        NotificationHistory::addNotificationHistory($params);

        return $response;
    }

    public function sendPushNotifcation($token = '', $title = '', $body = '', $userId = '', $senderId = 0, $custom = [], $unreadMsgCount = 0, $type = 'custom')
    {
        if(empty($token)) {
            return false;
        }

        $userSettings = UserSettings::where('user_id', $userId)->first();
        if(is_array($token)) {
            $token = $token;
        } else {
            $token = [$token];
        }

        if(isset($userSettings->show_notification) && $userSettings->show_notification == 1)
        {

            $unreadMsgCount                = Notifcation::where('receiver_id', $userId)->where('status','unread')->count();
            $optionBuilder                 = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);
            $notificationBuilder           = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($body)->setSound('default')->setBadge($unreadMsgCount+1);
          
            $dataBuilder                   = new PayloadDataBuilder();
            $dataBuilder->addData(['a_data' => 'my_data','setting' => $title, 'custom' => $custom]);

            $option                        = $optionBuilder->build();
            $notification                  = $notificationBuilder->build();
            $data                          = $dataBuilder->build();
            $response                      = FCM::sendTo($token, $option, $notification, $data);

            if(!$response) {
                return false;
            }

            if($type == 'artical'){
                $params = [
                    'receiver_id'  => $userId,
                    'sender_id'    => $senderId,
                    'article_id'   => (isset($custom['custom']) && isset($custom['custom']['article_id'])) ? $custom['custom']['article_id'] : '',
                    'title'        => $title,
                    'message'      => $body,
                    'type'         => $type,
                ];
                Notifcation::addNotificationHistory($params);
            }elseif($type  != 'message') {
                $params = [
                    'receiver_id'  => $userId,
                    'sender_id'    => $senderId,
                    'title'        => $title,
                    'message'      => $body,
                    'type'         => $type,
                ];

                Notifcation::addNotificationHistory($params);
            }
            return $response;
        }

        return true;
    }
}
