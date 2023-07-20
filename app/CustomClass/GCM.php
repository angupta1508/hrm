<?php
namespace App\CustomClass;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\t_log;
use App\User;
class GCM {
		
    //put your code here
    // constructor
    function __construct(){
        
    }
    
    /**
        * Sending Push Notification
    */
    public function send($type, $fields){
        // $data = $this->db->query("SELECT master_title from master_tbl where master_code = 'firebase_api_token'")->row_array();
        // echo 'sssssssss';
        // pr($data);die;
        // $fbc = 'AAAApjTN9g8:APA91bEFskCHhQhFSYWki6Ubc2FZnfj1AXWHRjUWjpDMsvsxbDv-_WNR1sUUmxnd7fQGaLZh7oEMqqlFOn_70jArCXZFNwleU5QPcOjpXVG-qKNfo_A2z53134LMJpTMsAlxK47Nvwkd';
        $fbc = 'AAAAxJwtt8I:APA91bHxoLGFfcAERqVMPQ0jaq6o3Cbu6BAPcwpiYe8kqCN-GSLQcg8suZocqAbZc4XV9e6BnPBoCK8dGzAhIqI3-7IiSQOGZyh_gK3BhQclt9Rjov7SrYjI3S9Xgazm7qtf0FGMhm9Z';
        $headers = array('Authorization:key='.$fbc ,
        'Content-Type: application/json'
        );
        
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        // print_r($result);
        // pr($result);
        // die; 
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
        
    }
    
    public function send_notification($registatoin_ids, $message, $type, $title, $ctype='', $token='', $channelName='', $user_uni_id='', $start_time='', $duration='', $astro_uni_id='') {
        if(empty($start_time)){
             $start_time=date('H:i:s');
        }
        
        if(count($registatoin_ids) > 2){
            $registatoin_idsArr     =   $registatoin_ids;
        }
        else{
            $registatoin_idsArr     =   $registatoin_ids;
        }
        
        $msg = array(
            'title'     	=> $title,
            'body'      	=> strip_tags($message),
            //'largeIcon' 	=> base_url("assets/img/websiteImage/vastro.png"),
            'sound'     	=> 'mySound',
            'type'			=> $ctype,
            'token'			=> $token,
            'channel'		=> $channelName,
            'user_uni_id'	=> $user_uni_id,
            'astro_uni_id'	=> $astro_uni_id,
            'start_tiame'	=> $start_time,
            'duration'		=> $duration,
            
            // 'media_type'	=> "image",
            // "action"		=> "3",
            // 'url'       	=>  '', 
        );

        
        
        $fields = [
        // 'to'  			=> implode(',',$registatoin_idArr),
        'registration_ids'  => $registatoin_idsArr,
        'data' 				=> $msg,
        // 'notification' 		=> $msg,
        'priority'          => 'high',
        // 'content_available' => true
        ];
        
        
        // pr($fields);die;
        return  $this->send($type, $fields);
    }
    
    
    public function send_topics($topics, $message, $type,$title) {
        // print_r($registatoin_ids); exit;
        $msg = array(
        'body'  => $message,
        'title' => $title,
        'largeIcon' => "<?=base_url()?>assets/img/websiteImage/vastro.png",
        'sound' => 'mySound'/*Default sound*/
        // 'sound' => 'mySound'/*Default sound*/
        );
        $fields = array(
        'to'    => $topics,
        'data'  => $msg,
        'priority'      => 'high',
        'content_available' => true
        );
        if($type=="ios"){
            $fields = array(
            'to'            => $topics,
            'data'  => $msg,
            'priority'      => 'high',
            'content_available' => true
            );
        } 
        return  $this->send($type, $fields);
        //  exit;
        // return $data;
    }
}