<?php
namespace Smaex\SitemapExcludes\Plugin\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Sitemap\Model\ResourceModel\Catalog\Product;
use Smaex\SitemapExcludes\Api\ProductAttributeInterface;

/**
 * Excludes selected products from XML sitemaps.
 *
 * @codeCoverageIgnore
 */
class ExcludeFromSitemap
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param Product $subject
     * @param array   $result
     * @param int     $storeId
     *
     * @return array
     */
    public function afterGetCollection(Product $subject, array $result, int $storeId): array
    {
        return array_diff_key(
            $result,
            array_flip(
                $this->getExcludedProductIds($storeId)
            )
        );
    }

    /**
     * @param int $storeId
     *
     * @return array
     */
    private function getExcludedProductIds(int $storeId): array
    {
        return $this->collectionFactory->create()
            ->addStoreFilter($storeId)
            ->addAttributeToFilter(ProductAttributeInterface::ATTRIBUTE_CODE, 1)
            ->load()
            ->getLoadedIds();
    }
}
