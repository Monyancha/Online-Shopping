<?php

namespace CoreShop\Component\Core\Repository;

use CoreShop\Component\Core\Model\CountryInterface;
use CoreShop\Component\Store\Model\StoreInterface;
use CoreShop\Component\Address\Repository\CountryRepositoryInterface as BaseCountryRepositoryInterface;

interface CountryRepositoryInterface extends BaseCountryRepositoryInterface
{
    /**
     * @param StoreInterface $store
     * @return CountryInterface[]
     */
    public function findForStore(StoreInterface $store);
}