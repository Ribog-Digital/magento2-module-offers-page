<?php
declare(strict_types=1);

namespace Ribog\Offers\Block\Index;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Ribog\Offers\Model\Config;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory;
use Magento\SalesRule\Model\Rule;

class Index extends Template
{
    /**
     * @var \Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected \Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory $catalogRuleCollectionFactory;

    /**
     * @var GroupRepositoryInterface
     */
    protected GroupRepositoryInterface $groupRepository;

    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var CollectionFactory
     */
    private $ruleCollectionFactory;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * Constructor
     *
     * @param Context $context
     * @param CollectionFactory $ruleCollectionFactory
     * @param \Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory $catalogRuleCollectionFactory
     * @param TimezoneInterface $timezone
     * @param GroupRepositoryInterface $groupRepository
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $ruleCollectionFactory,
        \Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory $catalogRuleCollectionFactory,
        TimezoneInterface $timezone,
        GroupRepositoryInterface $groupRepository,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->timezone = $timezone;
        $this->catalogRuleCollectionFactory = $catalogRuleCollectionFactory;
        $this->groupRepository = $groupRepository;
        $this->config = $config;
    }

    /**
     * Get catalog and cart rule collection
     *
     * @return array
     */
    public function getRules()
    {
        $salesRule = [];
        $catalogRules = [];
        if ($this->config->getPromotions() == 'cart' || $this->config->getPromotions() == 'both') {
            $salesRuleCollection = $this->ruleCollectionFactory->create();
            $salesRuleCollection->addFieldToFilter('is_active', 1);
            $salesRuleCollection->addFieldToFilter('visible_on_offer_page', 1);
            foreach ($salesRuleCollection->getItems() as $rule) {
                $rule->setData('rule_type', 'sales_rule');
                $salesRule[] = $rule;
            }
        }
        if ($this->config->getPromotions() == 'catalog' || $this->config->getPromotions() == 'both') {
            $catalogRuleCollection = $this->catalogRuleCollectionFactory->create();
            $catalogRuleCollection->addFieldToFilter('is_active', 1);
            $catalogRuleCollection->addFieldToFilter('visible_on_offer_page', 1);
            foreach ($catalogRuleCollection->getItems() as $rule) {
                $rule->setData('rule_type', 'catalog_rule');
                $catalogRules[] = $rule;
            }
        }
        return array_merge($salesRule, $catalogRules);
    }

    /**
     * Check is rule active today
     *
     * @param string $from
     * @param string $to
     * @return bool
     * @throws \DateMalformedStringException
     */
    public function isActiveToday($from, $to)
    {
        $now = new \DateTime($this->timezone->date()->format('Y-m-d'));
        $fromDate = !empty($from) ? new \DateTime($from) : $now;

        if (!empty($to)) {
            $toDate = new \DateTime($to);
            return $fromDate <= $now && $now <= $toDate;
        }

        return $fromDate <= $now;
    }

    /**
     * Get specific coupon type
     *
     * @return int
     */
    public function isSpecificCouponType()
    {
        return Rule::COUPON_TYPE_SPECIFIC;
    }

    /**
     * Retrieve customer groups name
     *
     * @param array $groups
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerGroups($groups)
    {
        $customerGroupsName = [];
        foreach ($groups as $groupId) {
            $customerGroup = $this->groupRepository->getById($groupId);
            if ($customerGroup) {
                $customerGroupsName[] = $customerGroup->getCode();
            }
        }
        return $customerGroupsName;
    }
}
