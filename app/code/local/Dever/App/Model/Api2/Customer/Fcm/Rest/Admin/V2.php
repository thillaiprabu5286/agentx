<?php

class Dever_App_Model_Api2_Customer_Fcm_Rest_Admin_V2
    extends Dever_App_Model_Api2_Customer_Fcm_Abstract
{
    protected function _delete()
    {
        $fcmId = $this->getRequest()->getParam('fcmid');
        if (empty($fcmId)) {
            Mage::throwException('Fcm ID is not specified');
        }

        $customerId = $this->getRequest()->getParam('customerid');
        if (empty($customerId)) {
            Mage::throwException('Customer ID is not specified');
        }

        try {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            //Get Fcm id list
            $fcmList = $customer->getFcmId();
            $fcmListArr = explode(',', $fcmList);
            if (($key = array_search($fcmId, $fcmListArr)) !== false) {
                unset($fcmListArr[$key]);
            }
            //Implode arr back to comma seperated
            $implodedFcm = implode(',',$fcmListArr);
            $customer->setFcmId($implodedFcm);
            if ($customer->save()) {
                $this->_successMessage(
                    'Fcm removed from customer',
                    Mage_Api2_Model_Server::HTTP_OK
                );
            }
        } catch (Exception $e) {
            throw new Mage_Api2_Exception(
                $e->getMessage(),
                Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR
            );
        }
    }
}