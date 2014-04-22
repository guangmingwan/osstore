<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Paction
*/
class Amasty_Paction_Model_Observer
{
    public function handleAmProductGridMassaction($observer) 
    {
        $grid = $observer->getGrid();

        $types = array('', 'addcategory', 'removecategory', '', 'modifyprice', 'addprice', 'modifyspecial', 'addspecial', 'modifycost', '', 
            'relate', 'upsell', 'crosssell', '', 
            'copyoptions', 'copyattr', 'copyimg', '', 'changeattributeset', '', 'delete');
        foreach ($types as $i => $type){
            if ($type){
                $command = Amasty_Paction_Model_Command_Abstract::factory($type);
                $command->addAction($grid);
            }
            else { // separator
                $grid->getMassactionBlock()->addItem('ampaction_separator' . $i, array(
                    'label'=> '---------------------',
                    'url'  => '' 
                ));                
            }
        }
        
        return $this;
    }
}