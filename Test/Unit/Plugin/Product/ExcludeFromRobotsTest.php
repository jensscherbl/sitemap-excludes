<?php
namespace Smaex\SitemapExcludes\Test\Unit\Plugin\Product;

use Magento\Catalog\Helper\Product\View;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Result\Page;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Smaex\SitemapExcludes\Plugin\Product\ExcludeFromRobots;

/**
 * @covers \Smaex\SitemapExcludes\Plugin\Product\ExcludeFromRobots
 */
class ExcludeFromRobotsTest extends TestCase
{
    private const STORE_ID = 42;

    /**
     * @var MockObject|ExcludeFromRobots
     */
    private $instance;

    /**
     * @var MockObject|StoreManagerInterface
     */
    private $mockStoreManager;

    /**
     * @var MockObject|StoreInterface
     */
    private $mockStore;

    /**
     * @var MockObject|CollectionFactory
     */
    private $mockCollectionFactory;

    /**
     * @var MockObject|Collection
     */
    private $mockCollection;

    /**
     * @var MockObject|View
     */
    private $mockView;

    /**
     * @var MockObject|Page
     */
    private $mockPage;

    /**
     * @var MockObject|Config
     */
    private $mockConfig;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mockStoreManager      = $this->createMock(StoreManagerInterface::class);
        $this->mockStore             = $this->createMock(StoreInterface::class);
        $this->mockCollectionFactory = $this->createMock(CollectionFactory::class);
        $this->mockCollection        = $this->createMock(Collection::class);
        $this->mockView              = $this->createMock(View::class);
        $this->mockPage              = $this->createMock(Page::class);
        $this->mockConfig            = $this->createMock(Config::class);

        $this->mockStoreManager->method('getStore')
            ->willReturn(
                $this->mockStore
            );
        $this->mockStore->method('getId')
            ->willReturn(
                self::STORE_ID
            );
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
        $this->mockPage->method('getConfig')
            ->willReturn(
                $this->mockConfig
            );

        $this->instance = new ExcludeFromRobots(
            $this->mockStoreManager,
            $this->mockCollectionFactory
        );
    }

    /**
     * @param int   $productId
     * @param array $excludedProductIds
     * @param bool  $shouldExcludeProduct
     *
     * @return void
     *
     * @throws NoSuchEntityException
     *
     * @dataProvider provideTestAfterPrepareAndRender
     */
    public function testAfterPrepareAndRender(int $productId, array $excludedProductIds, bool $shouldExcludeProduct): void
    {
        $assertion = $shouldExcludeProduct
            ? $this->atLeastOnce()
            : $this->never();

        $this->mockCollection->method('getLoadedIds')
            ->willReturn(
                $excludedProductIds
            );

        $this->mockConfig->expects($assertion)
            ->method('setRobots')
            ->with(
                ExcludeFromRobots::ROBOTS
            );
        $this->instance->afterPrepareAndRender(
            $this->mockView,
            $this->mockView,
            $this->mockPage,
            $productId
        );
    }

    /**
     * @return array
     */
    public function provideTestAfterPrepareAndRender(): array
    {
        return [
            /*
            Product should be excluded, excludes product.
            */
            [ 42, [ 5, 23, 42 ], true ],
            /*
            Product should not be excluded, does not exclude product.
            */
            [ 42, [ 5, 23 ], false ]
        ];
    }
}
