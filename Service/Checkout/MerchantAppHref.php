<?php

namespace Pointspay\Pointspay\Service\Checkout;

use Exception;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\ResourceModel\Quote;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

class MerchantAppHref
{
    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $quoteFactory;

    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote
     */
    private $quoteResource;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface
     */
    private $orderCollectionFactory;

    /**
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param \Magento\Quote\Model\ResourceModel\Quote $quoteResource
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface $orderCollectionFactory
     */
    public function __construct(
        QuoteFactory $quoteFactory,
        Quote $quoteResource,
        CollectionFactoryInterface $orderCollectionFactory
    ) {
        $this->quoteFactory = $quoteFactory;
        $this->quoteResource = $quoteResource;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * @param $quoteId
     * @return mixed
     * @throws \Exception
     */
    public function get($quoteId)
    {
        $quoteModel = $this->quoteFactory->create();
        $this->quoteResource->load($quoteModel, $quoteId);
        if (!$quoteModel->getId()) {
            throw new Exception('Quote not found');
        }
        $collection = $this->orderCollectionFactory->create();
        $collection->addFieldToFilter('quote_id', $quoteModel->getId());
        $collection->addOrder('entity_id', 'DESC');
        $orderModel = $collection->getFirstItem();

        if (!$orderModel->getId()) {
            throw new Exception('Order not found');
        }
        if (!empty($orderModel->getPayment()->getAdditionalInformation()['href'])) {
            return $orderModel->getPayment()->getAdditionalInformation()['href'];
        } else {
            throw new Exception('Merchant App Href not found');
        }

    }
}
