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
                $helper->sendSms($order, $fcmId, $message);
                break;
            case 'accepted':
            case 'complete':
            case 'canceled':
                $message = "Dear {$customer->getName()}, Your Order {$order->getIncrementId()} is currently in {$status} status.";
                $helper->sendSms($order, $fcmId, $message);
                break;
            default:
                //Do Nothing
        }
    }
}