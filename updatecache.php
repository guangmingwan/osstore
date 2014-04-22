    <?php
    $magePath = '/disk2/var/www/www.os-store.com/app/Mage.php';
    require_once $magePath;
    //Varien_Profiler::enable();
    //Mage::setIsDeveloperMode(true);
    //ini_set('display_errors', 1);
    umask(0);
     
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
     
    echo '<h3>Cleaning overall Cache</h3>';
     
    flush();
    // clean overall cache
    Mage::app()->cleanCache();
    echo 'done';
     
    flush();
    echo '<h3>Cleaning Catalog Rewrites</h3>';
     
    flush();
    // clear 'refresh_catalog_rewrites':
    //Mage::getSingleton('catalog/url')->refreshRewrites();
    Mage::getModel('catalog/url')->refreshRewrites();
    echo 'Catalog Rewrites was refreshed succesfuly<br>';
     
    flush();
    echo '<h3>Cleaning Image Cache</h3>';
     
    flush();
    //  clear 'clear_images_cache':
    Mage::getModel('catalog/product_image')->clearCache();
    echo 'Image cache was cleared succesfuly<br>';
     
    flush();
    echo '<h3>Cleaning Layered Navigation</h3>';
     
    flush();
    //  clear 'refresh_layered_navigation':
    Mage::getSingleton('catalogindex/indexer')->plainReindex();
    echo 'Layered Navigation Indices was refreshed succesfuly<br>';
     
    flush();

    echo '<h3>Clearing out Search Index</h3>';
     
    flush();
    Mage::getModel('catalogsearch/fulltext')->rebuildIndex();
    echo 'Search index was rebuilt succesfully<br>';
     
    flush();
    echo '<h3>Rebuilding Flat Category</h3>';
    Mage::getResourceSingleton('catalog/category_flat')->rebuild();
    echo 'flat category was rebuilt successfully<br>';
     
    flush();
    echo '<h3>Rebuilding Flat Products</h3>';
    Mage::getResourceSingleton('catalog/product_flat_indexer')->rebuild();
    echo 'flat product was rebuilt successfully<br>';
     
    flush();


