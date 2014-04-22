<?php

class MageAsia_LBTags_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $replacement="-";
	
	 public function getRoute(){
		$route = Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_routename');
		#die($route);
		if (!$route){
			$route = "tags";
		}
		return $route;
	}
	public function getModuleName()
	{
		return Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_modulename');  
	}
	public function getURL(){
	
		return Mage::getURL('').self::getRoute()."/";	
		
	}
	public function makeTagsUrl($Identifier)
	{
		return self::getURL().$Identifier.".html";
	}
	public function makeTagsPageUrl($idf,$page)
	{
		if($page>1){
			return self::getURL().$idf."/".$page.".html";
		}else{
			return self::getURL().$idf;
		}
	}
	public function makeTagsViewPageUrl($idf,$page)
	{
		if($page>1){
			return self::getURL().$idf."/".$page.".html";
		}else{
			return self::getURL().$idf.".html";
		}
	}
	public function FixIdentifier ( $text )
	{
		
		#$text=strtolower(trim($text));
		#$text=str_replace(" ","-",$text);
		$text=$this->titleToLocation($text);
		return $text;
		
	}
	public function getIntegers(){
		return "0-9";	
	}
	
	public function getAlphabet(){
		$arritem=array();
		for($i=65; $i<=90; $i++) :
		
			$arritem[] = chr($i) ;
		
		endfor;	
		return $arritem;
	}
	
	public  function titleToLocation($title){
		
		$title = trim($title);
		 
		  $Rep = $this->GetReplacements();
		  if (!empty($Rep)) {
			foreach( $Rep as $from => $to) {
			  $title = str_replace( $from, '', $title);
			}
		  }
		$title = preg_replace( '/[\s]+/iU', $this->replacement, $title);
		$title = str_replace('\'', $this->replacement, $title);
		$title = str_replace('"', $this->replacement, $title);		  
		$title = str_replace('#', $this->replacement, $title);		 
		$title = str_replace('?', $this->replacement, $title);		  
		$title = str_replace('\\', $this->replacement, $title);
		 
		 
		  if (!empty($this->replacement))   
		  $title = preg_replace('/'.preg_quote($this->replacement).'{2,}/', $this->replacement, $title);
		  	
		  $title=strtolower($title);
		  return $title;
		
	}
	public  function GetReplacements()

  {
    //  initialize variable
    static $shReplacements = null;
    if (isset($shReplacements)) return $shReplacements;
    $shReplacements = array();
	$strlist='Š|S, Œ|O, Ž|Z, š|s, œ|oe, ž|z, Ÿ|Y, ¥|Y, µ|u, À|A, Á|A, Â|A, Ã|A, Ä|A, Å|A, Æ|A, Ç|C, È|E, É|E, Ê|E, Ë|E, Ì|I, Í|I, Î|I, Ï|I, Ð|D, Ñ|N, Ò|O, Ó|O, Ô|O, Õ|O, Ö|O, Ø|O, Ù|U, Ú|U, Û|U, Ü|U, Ý|Y, ß|s, à|a, á|a, â|a, ã|a, ä|a, å|a, æ|a, ç|c, è|e, é|e, ê|e, ë|e, ì|i, í|i, î|i, ï|i, ð|o, ñ|n, ò|o, ó|o, ô|o, õ|o, ö|o, ø|o, ù|u, ú|u, û|u, ü|u, ý|y, ÿ|y, ß|ss, ă|a, ş|s, ţ|t, ț|t, Ț|T, Ș|S, ș|s, Ş|S';
    $items = explode(',', $strlist);
    foreach ($items as $item) {
      if (!empty($item)) {  // V 1.2.4.q better protection. Returns null array if empty
        @list($src, $dst) = explode('|', trim($item));
        $shReplacements[trim($src)] = trim($dst);
      }
    }

    return $shReplacements;
  } 
  
	public function getViewWordMin()
	{
		return (int)Mage::getStoreConfig('magasialbtags/tagsetting/word_min');
	}
	public function getViewWordExcept()
	{
		return Mage::getStoreConfig('magasialbtags/tagsetting/word_except');
	}
	public function cleanWords($wordArray)
	{
		$exceptArray = explode(',',$this->getViewWordExcept());
		$minLength = $this->getViewWordMin();
		$tmpArray = array();
		foreach ($wordArray as $_word) {
			if (strlen($_word)>=$minLength &&!in_array($_word,$tmpArray)) {
				array_push($tmpArray,$_word);
			}
		}
		return $tmpArray;
	}
  
  public function getVersion(){
		return "v1.4";  
  }
  public function getUpdateDate(){
		return "2012/8/13";  
  }
  
  public function isEnablePlguin(){
		return Mage::getStoreConfig('magasialbtags/tagsetting/enable');;
	}	

}