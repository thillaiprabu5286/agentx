<?php
/**
 * Created by PhpStorm.
 * User: Prabu
 * Date: 26/10/18
 * Time: 10:34 AM
 */
class Dever_Notification_Model_Observer
{
    public function logNotification($observer)
    {
        $debug = true;
        $data = $observer->getEvent()->getNotification();
        /** @var Dever_Notification_Model_Notification $model */
        $model = Mage::getModel('dever_notification/notification');
        $model->addData($data)
            ->save();
    }
}