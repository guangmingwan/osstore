date
cd /var/www/vhosts/ns5001601.ip-192-95-33.net/www.os-store.com/shell
php indexer.php --mode-manual catalog_product_attribute
php indexer.php --mode-manual catalog_product_price
php indexer.php --mode-manual catalog_url
php indexer.php --mode-manual catalog_product_flat
php indexer.php --mode-manual catalog_category_flat
php indexer.php --mode-manual catalog_category_product
php indexer.php --mode-manual catalogsearch_fulltext
php indexer.php --mode-manual cataloginventory_stock
php indexer.php --mode-manual tag_summary
php indexer.php reindexall
#php indexer.php --reindex catalog_product_attribute
#php indexer.php --reindex catalog_product_price
#php indexer.php --reindex catalog_product_flat
#php indexer.php --reindex catalog_category_flat
#php indexer.php --reindex catalog_category_product
#php indexer.php --reindex catalogsearch_fulltext
#php indexer.php --reindex cataloginventory_stock
#php indexer.php --reindex tag_summary

php indexer.php --mode-realtime catalog_product_attribute
php indexer.php --mode-realtime catalog_product_price
php indexer.php --mode-realtime catalog_url
php indexer.php --mode-realtime catalog_product_flat
php indexer.php --mode-realtime catalog_category_flat
php indexer.php --mode-realtime catalog_category_product
php indexer.php --mode-manual catalogsearch_fulltext
php indexer.php --mode-realtime cataloginventory_stock
php indexer.php --mode-realtime tag_summary

cd -
date
