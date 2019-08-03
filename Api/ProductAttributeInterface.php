<?php
namespace Smaex\SitemapExcludes\Api;

/**
 * Custom product attribute that excludes
 * selected products from XML sitemaps.
 *
 * @api
 */
interface ProductAttributeInterface
{
    const ATTRIBUTE_CODE = 'exclude_from_sitemap';
}
