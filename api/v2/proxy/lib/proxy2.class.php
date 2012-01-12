<?php
abstract class proxy2{
	
	private $cacheDir;
	private $cacheFiles = array();
	private $currentCacheFile;	
	private $timeOut;
	private $nextCacheFile;
	private $isnew;

	
	public function __construct($cacheDir, $timeout=43200, $cacheFileCount=5){
		$this->nextCacheFile = 1;
		//$this->cacheDir = '/homez.50/terraz/www/_GEOCODEARTH_/api/v2/proxy/cache/'.$cacheDir;
		$this->cacheDir = '/usr/local/www/geocodearth/v2/proxy/cache/'.$cacheDir;
		$this->timeOut  = $timeout;
		
		if(!file_exists($this->cacheDir)){
			$this->isnew = true;
			mkdir($this->cacheDir,0750);
			chmod($this->cacheDir,0770);
		}else{	
			$this->isnew = false;
		}
		
		for( $i=1;$i<=$cacheFileCount;$i++ ){
			if(is_file($this->cacheDir.'/'.$i.'.cache') )
				$this->cacheFiles[$i] = filemtime($this->cacheDir.'/'.$i.'.cache');
		}
		
		//Trie pour le plus recent
		asort($this->cacheFiles);
		$this->cacheFiles = array_reverse($this->cacheFiles, true);
		
		foreach( $this->cacheFiles as $key=>$value ){
			$this->currentCacheFile['key'] = $key;
			$this->currentCacheFile['value'] = $value;
			$this->nextCacheFile = ( $key < $cacheFileCount)?($key+1):1;
			break;
		} 		
		
		
	}
	
	public abstract function customReadCache();
	
	public abstract function customWriteCache($isNew);
		
	
	
	public function getProxyContent(){
		
		if( empty($this->currentCacheFile)   ){			
			$this->customWriteCache(1);						
			//echo '<!--write '.$this->nextCacheFile.'.cache-->';
		}else if(  $this->currentCacheFile['value'] < (time()-$this->timeOut)  ){
			$this->customWriteCache(0);		
		}else{
			$this->customReadCache();
			//echo '<!--read '.$this->currentCacheFile['key'].'.cache-->';
		}
	}
	
	protected function readCache(){
		//echo '<!--read cache '.$this->currentCacheFile['key'].'-->';		
		$filename 	= $this->cacheDir.'/'.$this->currentCacheFile['key'].".cache";		
		$handle 	= fopen($filename, "r");	
		$contents 	= fread($handle, filesize($filename));		
		fclose($handle);
		$contents =  str_replace("##READWRITE##", "READ:".$this->currentCacheFile['key'], $contents);
		echo $contents;
	}
	
	protected function writeCache($content){
		$filename 	= $this->cacheDir.'/'.$this->nextCacheFile.".cache";		
		$handle 	= fopen($filename, "w");	
		$contents 	= fwrite($handle, $content);
		chmod($filename,0660);
		fclose($handle);
		//$content =  str_replace("##READWRITE##", "WRITE:".$this->nextCacheFile, $content);
		//echo $content;		
	}		
	
	protected function echoContent($content){
		$content =  str_replace("##READWRITE##", "FIRST", $content);
		echo $content;
	}
	
	
}

?>