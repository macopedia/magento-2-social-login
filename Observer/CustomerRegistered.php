<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_SocialLogin
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\SocialLogin\Observer;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageplaza\SocialLogin\Model\Social;
use Mageplaza\SocialLogin\Model\SocialFactory;

/**
 * Class CustomerRegistered
 * @package Mageplaza\SocialLogin\Observer
 */
class CustomerRegistered implements ObserverInterface
{
    /**
     * @var SocialFactory
     */
    protected $socialFactory;
    /**
     * @var Session
     */
    protected $session;

    /**
     * CustomerRegistered constructor.
     * @param SocialFactory $socialFactory
     * @param Session $session
     */
    public function __construct(
        SocialFactory $socialFactory,
        Session $session
    ) {
        $this->socialFactory = $socialFactory;
        $this->session       = $session;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->session->getUserProfile() &&
            $this->session->getUserProfileType() &&
            $observer->hasData('customer')) {
            /** @var CustomerInterface $customer */
            $customer = $observer->getData('customer');

            /** @var Social $social */
            $social          = $this->socialFactory->create();
            $userProfile     = $this->session->getUserProfile();
            $userProfileType = $this->session->getUserProfileType();
            $social->setSocialId($userProfile->identifier);
            $social->setType($userProfileType);
            $social->setCustomerId($customer->getId());

            $social->save();
        }
    }
}
