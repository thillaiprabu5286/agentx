<?php

class Dever_Sales_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function triggerFcm($order)
    {
        //Mage::log("Start Fcm Trigger",null,"fcm.log");
        $status = $order->getStatus();
        /** @var Dever_Sms_Helper_Fcm $helper */
        $helper = Mage::helper('dever_sms/fcm');
        $customerId = $order->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $fcmId = $customer->getFcmId();
        //Mage::log("Process Fcm ID:" .$fcmId,null,"fcm.log");
        $message = "Dear {$customer->getName()}, Thanks for your Order. Your Order {$order->getIncrementId()} is submitted with AgentX team. 
                We will get back to you shortly.";
        //Mage::log("Process Status:" . $status,null,"fcm.log");
        //Mage::log("Process Message:" . $message,null,"fcm.log");
        $helper->sendSms($fcmId, $message);
        //Trigger Notification Event to log messages
        $notification = array(
            'fcm_id' => $fcmId,
            'customer_id' => $customerId,
            'name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
            'email' => $customer->getEmail(),
            'message' => $message,
            'created_date' => date('Y-m-d H:i:s', strtotime($order->getCreatedAt(). ' + 240 mins'))
        );
        Mage::dispatchEvent('log_notification_messages', array('notification' => $notification));
        //Mage::log("End Fcm Trigger",null,"fcm.log");
    }
}