# Magento 2: Sitemap Excludes

Excludes selected products from [XML sitemaps][1] in [Magento 2][2].

## Intro

Magento already offers many SEO related features out of the box, like [generating XML sitemaps][3] for CMS pages, categories, products and product images.

For certain use cases, however, merchants might want to show specific products on category pages and in on-site search results, but not in search results provided by external search engines.    

## How to use

This extension works pretty much without further configuration, and allows merchants to exclude individual products from XML sitemaps on a store-level with a new product attribute. 

![Screenshot: How to exclude selected products from XML sitemaps in Magento 2.][4]

## How to install

Require the extension via [Composer][5].

```sh
$ composer require smaex/sitemap-excludes ^1.0
```

Enable the module via [Magento’s CLI][6].

```sh
$ magento module:enable Smaex_SitemapExcludes 
$ magento setup:upgrade
$ magento setup:di:compile
```

## We’re hiring!

We’re currently looking for interested ~~masochists~~ **PHP & Magento developers** to join our Magento Team **in Munich**. Just drop me a line via [j.scherbl@techdivision.com][7]

[1]: https://support.google.com/webmasters/answer/183668
[2]: https://github.com/magento/magento2
[3]: https://docs.magento.com/m2/ce/user_guide/marketing/sitemap-xml.html
[4]: https://user-images.githubusercontent.com/1640033/62414523-e80cc000-b61c-11e9-9b71-e8223ddaf123.png
[5]: https://getcomposer.org
[6]: https://devdocs.magento.com/guides/v2.3/install-gde/install/cli/install-cli-subcommands-enable.html 
[7]: mailto:j.scherbl@techdivision.com
