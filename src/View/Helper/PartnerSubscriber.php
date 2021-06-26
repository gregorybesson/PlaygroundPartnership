<?php

namespace PlaygroundPartnership\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class PartnerSubscriber extends AbstractHelper
{
    protected $partnerService;

    /**
     * @param  int|string $identifier
     * @return string
     */
    public function __invoke($partner, $user)
    {
        if ($user == false) {
            return $this->getPartnerService()->findSubscribers($partner);
        } else {
            return $this->getPartnerService()->isSubscriber($partner, $user);
        }
    }

    /**
     * @param \PlaygroundPartnership\Service\Partner $partnerService
     */
    public function setPartnerService(\PlaygroundPartnership\Service\Partner $partnerService)
    {
        $this->partnerService = $partnerService;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartnerService()
    {
        return $this->partnerService;
    }
}
