<?php
/**
 * Created by PhpStorm.
 * User: prabu
 * Date: 05/10/16
 * Time: 3:17 PM
 */
class Dever_Sms_Helper_Fcm extends Mage_Core_Helper_Abstract
{
    protected $_url;
    protected $_apiKey;
    protected $_senderId;

    public function __construct()
    {
        $this->_apiKey = 'AIzaSyAt1f4jEW0VXl18XLd1wrzeBVMgwUitXDQ';

        return $this;
    }

    public function sendSms($order, $fcmId, $message)
    {
        // prep the bundle
        //$message = "Message here";
        $msg = array (
            'body' 	=> $message,
            'title'		=> 'Agentx Order Update',
            'vibrate'	=> 1,
            'sound'		=> 1,
            'largeIcon'	=> 'large_icon',
            'smallIcon'	=> 'small_icon'
        );
        $fields = array (
            'to' 	=> $fcmId,
            'notification'  => $msg
        );

        $headers = array(
            'Authorization: key=' . $this->_apiKey,
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
        curl_close( $ch );
    }

}