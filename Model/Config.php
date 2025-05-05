<?php
declare(strict_types=1);

namespace Ribog\Offers\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const ENABLE = 'offers/general/enable';

    public const PROMOTIONS = 'offers/general/promotion';

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Config path
     *
     * @param string $path
     * @param string $scopeCode
     * @param string $scopeType
     * @return mixed
     */
    public function getConfig($path, $scopeCode, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue($path, $scopeType, $scopeCode);
    }

    /**
     * Is Enable Check
     *
     * @param string $storeCode
     * @return mixed
     */
    public function isEnable($storeCode = null)
    {
        return $this->getConfig(self::ENABLE, $storeCode);
    }

    /**
     * Get Promotions Type
     *
     * @param string $storeCode
     * @return mixed
     */
    public function getPromotions($storeCode = null)
    {
        return $this->getConfig(self::PROMOTIONS, $storeCode);
    }
}
