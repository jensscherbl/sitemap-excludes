<?php
namespace Smaex\SitemapExcludes\Plugin\Product;

use Magento\Catalog\Helper\Product\View;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Store\Model\StoreManagerInterface;
use Smaex\SitemapExcludes\Api\ProductAttributeInterface;

/**
 * Excludes selected products from robots.
 */
class ExcludeFromRobots
{
    const ROBOTS = 'NOINDEX,NOFOLLOW';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory     $collectionFactory
     */
    public function __construct(StoreManagerInterface $storeManager, CollectionFactory $collectionFactory)
    {
        $this->storeManager      = $storeManager;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param View $subject
     * @param View $result
     * @param Page $resultPage
     * @param int  $productId
     *
     * @return View
     *
     * @throws NoSuchEntityException
     */
    public function afterPrepareAndRender(View $subject, View $result, Page $resultPage, int $productId): View
    {
        if ($this->shouldExcludeProduct($productId)) {
            $resultPage->getConfig()->setRobots(self::ROBOTS);
        }
        return $result;
    }

    /**
     * @param int $productId
     *
     * @return bool
     *
     * @throws NoSuchEntityException
     */
    private function shouldExcludeProduct(int $productId): bool
    {
        $excludedProductIds = $this->getExcludedProductIds();

        return in_array($productId, $excludedProductIds);
    }

    /**
     * @return array
     *
     * @throws NoSuchEntityException
     */
    private function getExcludedProductIds(): array
    {
        $storeId = $this->storeManager->getStore()->getId();

        return $this->collectionFactory->create()
            ->addStoreFilter($storeId)
            ->addAttributeToFilter(ProductAttributeInterface::ATTRIBUTE_CODE, 1)
            ->load()
            ->getLoadedIds();
    }
}
