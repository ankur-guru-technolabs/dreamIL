<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orders;
use App\Models\User;
use App\Traits\PushNotification;
use PHPMailer\PHPMailer;
use App\Models\Advertisements;
use DB;

class CheckAdvertisementsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckAdvertisementsStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $advertisements = Advertisements::where('status','Approved')->get();
        if($advertisements) {
            foreach ($advertisements as $key => $advertisement) {
                $startDate    = isset($advertisement->start_date) ? $advertisement->start_date : '';
                $endDate      = isset($advertisement->end_date) ? $advertisement->end_date : '';
                if(!empty($endDate)) {
                    $currentDate  = date("d-m-Y");
                    $currentDate  = strtotime($currentDate);
                    $endDate      = str_replace('/', '-', $endDate);
                    $endDate      = strtotime($endDate);
                    if($currentDate > $endDate)
                    {
                        $advertisements = Advertisements::find($advertisement->id);

                        if($advertisements) {
                            $advertisements->status = 'Expired';
                            $advertisements->save();
                        }
                    }
                }
            }
        }
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
    public function sendMail($view = '', $data = [], $to = '', $from = '')
    {
        /*if(empty($view) || empty($to)) {
            return false;
        }

        $subject = isset($data['subject']) ? $data['subject'] : '';
        $from    = !empty($from) ? $from : env('APP_EMAIL');
        $status  = Mail::send($view, $data, function($message) use ($to, $from, $subject) {
            $message->to($to, '')->subject($subject);
            $message->from($from,'Zodiap');
        });
      
        return true;*/
        $subject = isset($data['subject']) ? $data['subject'] : '';
        $from    = !empty($from) ? $from : env('APP_EMAIL');
        $mail             = new PHPMailer\PHPMailer(); // create a n
        $mail->SMTPDebug  = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth   = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host       = "smtp.gmail.com";
        $mail->Port       = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SetFrom($from, 'Zodiap.org');
        $mail->Subject  = $subject;
        $mail->Body     = view($view, $data);;
        $mail->AddAddress($to);
        if ($mail->Send()) {
            return true;
        } else {
           return false;
        }
    }
}
