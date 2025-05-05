<?php
declare(strict_types=1);

namespace Ribog\Offers\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Promotions implements OptionSourceInterface
{
    /**
     * Options getter
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'both', 'label' => __('Catalog & Cart Price Rules')],
            ['value' => 'catalog', 'label' => __('Catalog Price Rule')],
            ['value' => 'cart', 'label' => __('Cart Price Rule')]
        ];
    }
}
