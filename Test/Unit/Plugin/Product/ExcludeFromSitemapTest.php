<?php
namespace Smaex\SitemapExcludes\Test\Unit\Plugin\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Sitemap\Model\ResourceModel\Catalog\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Smaex\SitemapExcludes\Plugin\Product\ExcludeFromSitemap;

/**
 * @covers \Smaex\SitemapExcludes\Plugin\Product\ExcludeFromSitemap
 */
class ExcludeFromSitemapTest extends TestCase
{
    private const STORE_ID = 42;

    /**
     * @var MockObject|ExcludeFromSitemap
     */
    private $instance;

    /**
     * @var MockObject|CollectionFactory
     */
    private $mockCollectionFactory;

    /**
     * @var MockObject|Collection
     */
    private $mockCollection;

    /**
     * @var MockObject|Product
     */
    private $mockProduct;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mockCollectionFactory = $this->createMock(CollectionFactory::class);
        $this->mockCollection        = $this->createMock(Collection::class);
        $this->mockProduct           = $this->createMock(Product::class);

        $this->mockCollectionFactory->method('create')
            ->willReturn(
                $this->mockCollection
            );
        $this->mockCollection->method('addStoreFilter')
            ->willReturnSelf();
        $this->mockCollection->method('addAttributeToFilter')
            ->willReturnSelf();
        $this->mockCollection->method('load')
            ->willReturnSelf();

        $this->instance = new ExcludeFromSitemap(
            $this->mockCollectionFactory
        );
    }

    /**
     * @return void
     */
    public function testAfterGetCollection(): void
    {
        $excludedProductIds = [ 23, 42 ];
        $originalResult     = [ 5 => true, 23 => true, 42 => true, 69 => true ];
        $expectedResult     = [ 5 => true, 69 => true ];

        $this->mockCollection->method('getLoadedIds')
            ->willReturn(
                $excludedProductIds
            );

        $this->assertSame(
            $expectedResult,
            $this->instance->afterGetCollection(
                $this->mockProduct,
                $originalResult,
                self::STORE_ID
            )
        );
    }
}
