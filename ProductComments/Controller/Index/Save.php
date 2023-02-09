<?php

namespace Task\ProductComments\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote;

class Save extends Action
{

    public function execute()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/savesdff.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        //get form params
        $Allcomment = $this->getRequest()->getParams();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        //created quote object and get current quote id
        $quote = $objectManager->create('Magento\Checkout\Model\Cart')->getQuote();
        $quoteId = $quote->getId();

        $items = $objectManager->create('Magento\Checkout\Model\Cart')->getQuote()->getItemsCollection();

        $i=1;
        //$items['sdf'=>'asd''sdf'=>'asd''sdf'=>'asd']
        foreach ($items as $key) {
            $key->setProductComments("Comment for product ".$key->getName()." : ".$Allcomment['comment'.$i]);
            $logger->info($key->getName());
            $key->save();
            $i++;
        }

        $logger->info($quoteId);


        $url = $this->resultRedirectFactory->create();
        $url->setUrl('/checkout#payment');
        $logger->info('redirected');
        return $url;
    }
}