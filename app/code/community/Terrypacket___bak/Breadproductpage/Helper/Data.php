<?php


class Terrypacket_Breadproductpage_Helper_Data extends Mage_Catalog_Helper_Data
{
	  public function getBreadcrumbPath()
    {
		
		if ($this->getProduct() && !$this->getCategory()) {
	       $_categoryIds = $this->getProduct()->getCategoryIds();
		   $max = 0;
		   for($i=0;$i<count($_categoryIds);$i++){
				$curr = $_categoryIds[$i];
				if($curr>$max){
					$max = $curr;
				}
				 
		   }
		 //  echo $max."###";exit;
			if($max!=0 && isset($_categoryIds[$max])){
			   $_categoryId = $_categoryIds[$max];
				  $_category = Mage::getModel('catalog/category')->load($_categoryId);
				  Mage::register('current_category', $_category);
			   
			}
		}
if($this->getProduct()){
 $_categoryIds = $this->getProduct()->getCategoryIds();
	 $_categoryId0 = $_categoryIds[0];
	 $_categoryId1 = $_categoryIds[1];
	 $_categoryId2 = isset($_categoryIds[2]) ? $_categoryIds[2] : 0;
	 $_categoryId3 = isset($_categoryIds[3]) ? $_categoryIds[3] : 0;
	 if($_categoryId1>$_categoryId0){
		$_categoryId = $_categoryId1;
	 }else{
		$_categoryId = $_categoryId0;
		}

	if($_categoryId<$_categoryId2){
		$_categoryId = $_categoryId2;
	}
	if($_categoryId<$_categoryId3){
		$_categoryId = $_categoryId3;
	}
	 $_category = Mage::getModel('catalog/category')->load($_categoryId);
}

 


        if (!$this->_categoryPath) {
            $path = array();
//			if($category == $_category){
//				$category = $_category;
//			}else{
			$category =$this->getCategory();
//			}


            if ($category) {
                $pathInStore = $category->getPathInStore();
                $pathIds = array_reverse(explode(',', $pathInStore));

                $categories = $category->getParentCategories();

                // add category path breadcrumb
                foreach ($pathIds as $categoryId) {
                    if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                        $path['category'.$categoryId] = array(
                            'label' => $categories[$categoryId]->getName(),
                            'link' => $this->_isCategoryLink($categoryId) ? $categories[$categoryId]->getUrl() : ''
                        );
                    }
                }
            }

            if ($this->getProduct()) {
                $path['product'] = array('label'=>$this->getProduct()->getName());
            }

            $this->_categoryPath = $path;
        }
        return $this->_categoryPath;
    }
}
