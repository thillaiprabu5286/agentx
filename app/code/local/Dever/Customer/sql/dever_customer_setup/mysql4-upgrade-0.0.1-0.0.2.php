<?php

$this->startSetup();

$this->addAttribute('customer', 'retailstores', array(
    'label'        => 'Retailstores',
    'visible'      => true,
    'required'     => false,
    'type'         => 'int',
    'input'        => 'select',
    'source'        => 'dever_customer/source_retailstore',
));
