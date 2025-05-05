<?php
declare(strict_types=1);

namespace Ribog\Offers\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Ribog\Offers\Model\Config;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @param Context $context
     * @param Config $config
     */
    public function __construct(
        Context $context,
        Config $config
    ) {
        parent::__construct($context);
        $this->config = $config;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $isEnable = $this->config->isEnable();

        if (!$isEnable) {
            throw new NotFoundException(__('Page not found'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
