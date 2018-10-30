<?php

class Dever_Sales_Model_Observer
{
    public function triggerFcm($observer)
    {
        $debug = true;
        $order = $observer->getEvent()->getOrder();
        $status = $order->getStatus();
        $originalData = $order->getOrigData();
        $previousStatus = $originalData['status'];
        if ($status == $previousStatus) {
            return;
        }
        //Mage::log("Start Fcm Trigger",null,"fcm.log");
        /** @var Dever_Sms_Helper_Fcm $helper */
        $helper = Mage::helper('dever_sms/fcm');
        $customerId = $order->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $fcmId = $customer->getFcmId();
        //Mage::log("Process Fcm ID:" .$fcmId,null,"fcm.log");
        //Mage::log("Old Status:" . $previousStatus,null,"fcm.log");
        $message = "Dear {$customer->getName()}, Your Order {$order->getIncrementId()} is currently in {$status} status.";
        //Mage::log("New Status:" . $status,null,"fcm.log");
        $helper->sendSms($fcmId, $message);
        //Trigger Notification Event to log messages
        $notification = array(
            'fcm_id' => $fcmId,
            'customer_id' => $customerId,
            'name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
            'email' => $customer->getEmail(),
            'message' => $message,
            'created_date' => date('Y-m-d H:i:s')
        );
        Mage::dispatchEvent('log_notification_messages', array('notification' => $notification));
        //Mage::log("End Fcm Trigger",null,"fcm.log");
    }
}