# Vasella

Vasella is a responsive WordPress theme for an artisan vase shop. WooCommerce provides the shop, product pages, cart, checkout, and customer accounts. Advanced Custom Fields lets editors manage the homepage and footer from WordPress.

[View the storefront demo](https://benallal-malek.github.io/website-portfolio/projects/vasella/)

## Installation

1. Download the [theme ZIP](https://github.com/Benallal-Malek/vasella-woocommerce-theme/archive/refs/heads/main.zip). In WordPress, open **Appearance → Themes → Add New → Upload Theme**, upload the ZIP, and activate Vasella.
2. Install and activate **WooCommerce** and **Advanced Custom Fields**. The theme's field groups work with the free version of ACF.
3. Set **Settings → General → Site Language** to English to display WordPress and WooCommerce interface text in English.
4. Open **Appearance → Vasella → Prepare homepage**. Select **Edit homepage** if a static homepage is already assigned.
5. Configure WooCommerce store pages, shipping, and payment methods. Set the store currency to RUB to match the demo prices.
6. Select **Import 6 demo products as drafts** in **Appearance → Vasella**. Review the drafts in **Products**, then publish the products you want to display. The importer adds collections and images and skips existing product SKUs.

## Edit the storefront

| Content | Location |
| --- | --- |
| Homepage headings, images, copy, and button links | **Pages → Home → Vasella · Homepage and footer** |
| Collection images and links | The **Collections** tab. Empty links use the matching WooCommerce category when available. |
| Featured products and display order | **Bestsellers → Homepage products**. An empty field shows WooCommerce featured products or the latest products. |
| Product names, prices, images, stock, and categories | **Products → Edit** |
| Product badges and badge colors | **Products → Edit → Vasella · Product display** |
| Menus and logo | **Appearance → Menus** and **Customize → Site Identity** |
| Contact details and newsletter | The **Footer** tab. Enter your store's contact details and your newsletter plugin's form shortcode. |

The supplied English defaults include six sample products and five collections. The homepage uses the bundled images until you replace them. A wide hero image with open space on the left leaves room for the heading. The built-in collection slugs are **keramika**, **skulpturnye**, **minimalizm**, **fakturnye**, and **tsvetnoe-steklo**. Set links in ACF to point to other categories.

The theme keeps favorites in the visitor's browser. Demo products, prices, and contact details are sample content. The WordPress and PHP minimums are listed in **style.css**.
