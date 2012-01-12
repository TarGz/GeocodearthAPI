<?php
abstract class proxy{
	
	private $cacheDir;
	private $cacheFiles = array();
	private $currentCacheFile;	
	private $timeOut;
	private $nextCacheFile;
	private $isnew;

	
	public function __construct($cacheDir, $timeout=43200, $cacheFileCount=5){
		$this->nextCacheFile = 1;
		$this->cacheDir = '/homez.50/terraz/www/_GEOCODEARTH_/api/v2/proxy/cache/'.$cacheDir;
		$this->timeOut  = $timeout;
		
		if(!file_exists($this->cacheDir)){
			$this->isnew = true;
			mkdir($this->cacheDir,0777);
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
	
	public abstract function customWriteCache();
		
	
	
	public function getProxyContent(){
		
		if( empty($this->currentCacheFile) || $this->currentCacheFile['value'] < (time()-$this->timeOut)  ){			
			$this->customWriteCache();						
			echo '<!--write '.$this->nextCacheFile.'.cache-->';
			
		}else{
			$this->customReadCache();
			echo '<!--read '.$this->currentCacheFile['key'].'.cache-->';
		}
	}
	
	protected function readCache(){
		echo '<!--read cache '.$this->currentCacheFile['key'].'-->';		
		$filename 	= $this->cacheDir.'/'.$this->currentCacheFile['key'].".cache";		
		$handle 	= fopen($filename, "r");	
		$contents 	= fread($handle, filesize($filename));		
		fclose($handle);
		echo $contents;
	}
	
	protected function writeCache($content){
		$filename 	= $this->cacheDir.'/'.$this->nextCacheFile.".cache";		
		$handle 	= fopen($filename, "w");	
		$contents 	= fwrite($handle, $content);
		fclose($handle);		
	}		
	
}

?>