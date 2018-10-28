<?php

class Dever_Sales_Model_Observer
{
    public function triggerFcm($observer)
    {
        $order = $observer->getEvent()->getOrder();

        /** @var Dever_Sms_Helper_Fcm $helper */
        $helper = Mage::helper('dever_sms/fcm');

        $customerId = $order->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $fcmId = $customer->getFcmId();
        $status = uc_words($order->getStatus());

        switch ($order->getStatus()) {
            case 'pending':
                $message = "Dear {$customer->getName()}, Thanks for your Order. Your Order {$order->getIncrementId()} is submitted with AgentX team. 
                We will get back to you shortly.";
                break;
            case 'accepted':
            case 'complete':
            case 'canceled':
                $message = "Dear {$customer->getName()}, Your Order {$order->getIncrementId()} is currently in {$status} status.";
                break;
            default:
                //Do Nothing
        }

        if ($message) {
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
        }
    }
}