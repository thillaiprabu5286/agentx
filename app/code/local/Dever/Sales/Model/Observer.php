<?php

class Dever_Sales_Model_Observer
{
    public function triggerFcm($observer)
    {
        $order = $observer->getEvent()->getOrder();

        /** @var Dever_Sms_Helper_Fcm $helper */
        $helper = Mage::helper('dever_sms/fcm');

        switch ($order->getStatus()) {
            case 'pending':
                $helper->sendSms($order, 'templateid');
                break;
            case 'accepted':
                break;
            case 'complete':
                break;
            case 'canceled':
                break;
            default:
                //Do Nothing
        }
    }
}