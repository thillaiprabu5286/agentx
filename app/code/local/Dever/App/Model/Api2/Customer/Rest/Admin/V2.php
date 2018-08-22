<?php

class Dever_App_Model_Api2_Customer_Rest_Admin_V2
    extends Dever_App_Model_Api2_Customer_Abstract
{
    public function _create($request)
    {
        $debug = true;
        // Validate if consumer key is set in request and if it exists
        $consumer = Mage::getModel('oauth/consumer');
        if (empty($request['api_key'])) {
            Mage::throwException('Consumer key is not specified');
        }
        $consumer->load($request['api_key'], 'key');
        if (!$consumer->getId()) {
            Mage::throwException('Consumer key is incorrect');
        }

        //$session = $this->_getSession();
        if (!empty($request['username']) && !empty($request['password'])) {
            try {
                if ($this->login($request['username'], $request['password'])) {
                    /** @var Mage_Customer_Model_Customer $customer */
                    $customer = Mage::getModel('customer/customer');
                    $customer->setWebsiteId(self::DEFAULT_WEBSITE)->loadByEmail($request['username']);

                    // Generate token for new created customer
                    /** @var Dever_Oauth_Model_Token $token */
                    $token = Mage::getModel('oauth/token');
                    $token->setConsumerId($consumer->getId());
                    $token->createFromCustomer($customer);
                    $token->save();

                    return array(
                        'token_id'          => $token->getEntityId(),
                        'customer_id'       => $customer->getId(),
                        'token'             => $token->getToken(),
                        'secret'            => $token->getSecret(),
                    );
                }
            } catch (Mage_Core_Exception $e) {
                switch ($e->getCode()) {
                    case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                        $message = $e->getMessage();
                        break;
                    default:
                        $message = $e->getMessage();
                }
                Mage::throwException($message, 'Customer data is invalid');
            } catch (Exception $e) {
                // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
            }
        } else {
            Mage::throwException('Request data is invalid');
        }
    }
}