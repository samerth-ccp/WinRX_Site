<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

use DB;
use App;

class MailSenders extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = array()){

        $this->data = $data; 
    }

    public function sendEmail($data = array()){

        // MSR
        $siteConfigData = DB::table('site_configs')->get()->toArray();
        $config_data = array_column($siteConfigData,'config_value','config_key');
        $getTemplate = DB::table('email_template')->where('template_key',$data['mail_template'])->get()->first();

        $dontSendMail = $xSendMsg = 0;

		$locale = App::getLocale();
        $mailType = $data['mail_template'];
        $getBrowser = getBrowser();
        
        $recipient_info = [
            "sender"    => [
                "name"      =>  $config_data['site_name'],
                "email"     =>  $config_data['site_email']
            ],
            "receiver"  => [
                "name"      =>  "",
                "email"     =>  ""
            ],
            "subject"   => "",
            "msr"   => [
                "{site_name}"   => $config_data['site_name'],
                "{logo_url}"    => asset('assets/storage/logo/').'/'.$config_data['site_icon'],
                "{site_logo}"   => '<img alt="'.$config_data['site_name'].'" src="'.asset('assets/storage/logo/').'/'.$config_data['site_icon'].'" style="border:none;max-width:150px;" />',
                "{site_link}"   => config('app.url'),
                "{site_title}"  => $config_data['site_name'],
                "{date}"        => date("l d F"),
                "{year}"        => date("Y"),
                "{link_about}"  => '',
                "{link_contact}"=> '',
                "{link_login}"  => '',
                "{site_email}"  => $config_data['site_email'],
                "{browser}"  => $getBrowser['name'],
                "{os}"  => getOS(),
                "{ip}"  => request()->getClientIp()
            ],
            "message" => ""
        ];

        switch ($mailType) {

            case 'registration_email':
                $recipient_info["receiver"]['name']         = $data['user_name'];
                $recipient_info["receiver"]['email']        = trim($data['user_email']);
                
                $recipient_info["msr"]['{user_name}']           = $data['user_name'];
                $recipient_info["msr"]['{confirm_link}']        = $data['confirm_link'];
                
                $recipient_info["message"]  = str_ireplace(array_keys($recipient_info["msr"]),array_values($recipient_info["msr"]),$getTemplate->template_content);
            break;

            case 'forgot_password':
                $recipient_info["receiver"]['name']         = $data['user_name'];
                $recipient_info["receiver"]['email']        = trim($data['user_email']);
                
                $recipient_info["msr"]['{reset_link}']        = $data['reset_link'];
                
                $recipient_info["message"]  = str_ireplace(array_keys($recipient_info["msr"]),array_values($recipient_info["msr"]),$getTemplate->template_content);
            break;

            case 'contact_us_email':
                $recipient_info["receiver"]['name']         = $data['user_name'];
                $recipient_info["receiver"]['email']        = trim($data['user_email']);
                $recipient_info["msr"]['{user_name}']      = $data['user_name'];

                $recipient_info["msr"]['{name}']           = $data['name'];
                $recipient_info["msr"]['{email}']          = $data['email'];
                $recipient_info["msr"]['{phone_number}']   = $data['number'];
                $recipient_info["msr"]['{message}']        = $data['message'];
                
                $recipient_info["message"]  = str_ireplace(array_keys($recipient_info["msr"]),array_values($recipient_info["msr"]),$getTemplate->template_content);
            break;

            case 'change_email_notification':
                $recipient_info["receiver"]['name']         = $data['user_name'];
                $recipient_info["receiver"]['email']        = trim($data['user_email']);
                $recipient_info["msr"]['{user_name}']      = $data['user_name'];
                $recipient_info["msr"]['{changed_email}']      = $data['changed_email'];

                $recipient_info["message"]  = str_ireplace(array_keys($recipient_info["msr"]),array_values($recipient_info["msr"]),$getTemplate->template_content);
            break;

            case 'change_email_verification':
                $recipient_info["receiver"]['name']         = $data['user_name'];
                $recipient_info["receiver"]['email']        = trim($data['user_email']);
                
                
                $recipient_info["msr"]['{user_name}']           = $data['user_name'];
                $recipient_info["msr"]['{confirm_link}']        = $data['confirm_link'];
                
                $recipient_info["message"]  = str_ireplace(array_keys($recipient_info["msr"]),array_values($recipient_info["msr"]),$getTemplate->template_content);
            break;

            case 'admin_change_email_notification':
                $recipient_info["receiver"]['name']         = $data['user_name'];
                $recipient_info["receiver"]['email']        = trim($data['user_email']);
                
                $recipient_info["msr"]['{user_name}']       = $data['user_name'];
                $recipient_info["msr"]['{changed_email}']   = $data['changed_email'];
                
                $recipient_info["message"]  = str_ireplace(array_keys($recipient_info["msr"]),array_values($recipient_info["msr"]),$getTemplate->template_content);
            break;

            case 'admin_change_password_notification':
                $recipient_info["receiver"]['name']         = $data['user_name'];
                $recipient_info["receiver"]['email']        = trim($data['user_email']);
                
                $recipient_info["msr"]['{user_name}']       = $data['user_name'];

                $recipient_info["message"]  = str_ireplace(array_keys($recipient_info["msr"]),array_values($recipient_info["msr"]),$getTemplate->template_content);
            break;
        }
		
        if(empty($dontSendMail)){ //echo 'in mail'; die;
            
            $form = $recipient_info['sender']['name'];
            $to = $recipient_info["receiver"]['email'];

            $subject = (!empty($data['subject']))?$data['subject']:$getTemplate->template_subject;
            $htmlData = '<html lang="en"> '.$recipient_info['message'].' </html>';

            $mail = $this->html($htmlData)
                    ->from(env('MAIL_USERNAME'),$form)
                    ->to($to)
                    ->subject($subject)
                    ->with($this->data);

            //$mail->from  = array(array('name'=>$form,'address'=>env('MAIL_USERNAME')));
            //$mail->to =  array(array('name'=>"",'address'=>$to));
            
            try {
                $isSend = Mail::send($mail);
                return true;
            } catch (\Exception $e) {
                return array('error'=>true,'message'=>$e->getMessage());
            }

        }
        else{ 
            return true;
        }
    }

    public function build(){

    }

}
