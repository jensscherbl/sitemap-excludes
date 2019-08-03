<?php
namespace Smaex\SitemapExcludes\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Smaex\SitemapExcludes\Api\ProductAttributeInterface;
use Zend_Validate_Exception;

/**
 * Adds a custom product attribute that
 * excludes selected products from XML sitemaps.
 *
 * @codeCoverageIgnore
 */
class AddProductAttribute implements DataPatchInterface, PatchRevertableInterface
{
    const ATTRIBUTE_LABEL = 'Exclude from Sitemap';
    const ATTRIBUTE_GROUP = 'Search Engine Optimization';
    const ATTRIBUTE_TYPE  = 'int';
    const ATTRIBUTE_INPUT = 'boolean';

    /**
     * @var EavSetup
     */
    private $eavSetup;

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @param EavSetup $eavSetup
     */
    public function __construct(EavSetup $eavSetup)
    {
        $this->eavSetup = $eavSetup;
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply(): self
    {
        $this->eavSetup->addAttribute(
            Product::ENTITY,
            ProductAttributeInterface::ATTRIBUTE_CODE,
            [
                'label'      => self::ATTRIBUTE_LABEL,
                'group'      => self::ATTRIBUTE_GROUP,
                'type'       => self::ATTRIBUTE_TYPE,
                'input'      => self::ATTRIBUTE_INPUT,
                'source'     => Boolean::class,
                'global'     => ScopedAttributeInterface::SCOPE_STORE,
                'required'   => 0,
                'sort_order' => 5
            ]
        );
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function revert()
    {
        $this->eavSetup->removeAttribute(
            Product::ENTITY,
            ProductAttributeInterface::ATTRIBUTE_CODE
        );
    }
}
