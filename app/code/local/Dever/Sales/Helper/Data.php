<?php

class Dever_Sales_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function triggerFcm($order)
    {
        /** @var Dever_Sms_Helper_Fcm $helper */
        $helper = Mage::helper('dever_sms/fcm');

        $name = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
        $message = "Dear {$name}, Thanks for your Order. Your Order {$order->getIncrementId()} is submitted with AgentX team. 
                We will get back to you shortly.";

        //Multi Fcm logic starts here
        $notificationList = array();

        /** @var Dever_Customer_Model_Fcm $model */
        $model = Mage::getModel('dever_customer/fcm');
        $fcmIds = $model->filterByCustomer($order->getCustomerId());
        foreach ($fcmIds as $each)
        {
            $helper->sendSms($each, $message);
            //Trigger Notification Event to log messages
            $notificationList[] = array(
                'fcm_id' => $each,
                'customer_id' => $order->getCustomerId(),
                'name' => $name,
                'email' => $order->getCustomerEmail(),
                'message' => $message,
                'created_date' => date('Y-m-d H:i:s', strtotime($order->getCreatedAt(). ' + 240 mins'))
            );
        }

        Mage::dispatchEvent('log_notification_messages', array('notification' => $notificationList));
        //Multi Fcm logic ends here

    }
}