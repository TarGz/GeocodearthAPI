<?php


include('proxy.class.php');

class FlickrGallerie  extends proxy 
{

	private $key = "140f4d96f8d0bb38866ceada5d6748b7";
	private $secret = "d62dcb8b063a076b";
	private $setId;
	private $count;
	
	private $photoset;
	private $photos;

	public function __construct($cacheDir, $timeout=43200, $cacheFileCount=5 , $setId=0 , $count=20){
		$this->setId = $setId;
		$this->count = $count;
		parent::__construct($cacheDir, $timeout, $cacheFileCount);
		
	}
	public function customWriteCache(){
		
		//$content = date("H:i:s");
		$content = self::load();
		//echo $content;
		self::writeCache($content);		
		//echo $content;
		self::readCache();
	}
	
	public function customReadCache(){

		//OverRide before reading	
		self::readCache();
		//OverRide after reading
		
	}
		
	public function load( )
	{	

		// Récupération de toutes les photos, et du photoset
		$this->getPhotos( $this->setId, $this->count );
		// Récupération des infos de geolocalisation, et on vire les photos qui n'en ont pas
		//$this->getPhotosGeo();
		// Récupération des infos supplémentaires
		$this->getPhotosInfos();
		// Récupération des images
		$this->getImages();
		
		return $this->generateGeoRSS().print_r($this->debugMsg);
	}
	
	private function getPhotos( $setid, $count )
	{
		$set = $this->executeRequest( "flickr.galleries.getPhotos", array( array( "name" => "gallery_id", "value" => $setid ), array( "name" => "per_page", "value" => $count ) ) );
		
		// Récupération du photoset
		$this->photoset = $set[ "rsp" ][ "photos" ];
		// Récupération de la liste de photos
		$tmp = $this->photoset[ "photo" ];
		
		//print_r($tmp);
		$this->debugMsg = $set[ "rsp" ];
		// Tri des photos par id
		$this->photos = array();
		$n = count( $tmp );
		for ( $i = 0; $i < $n; $i++ )
			$this->photos[ $tmp[ $i ][ "id" ] ] = $tmp[ $i ];
	}
	
		private function getImages()
    {
        foreach( $this->photos as $id=>$value )
        {
            $images = $this->executeRequest( "flickr.photos.getSizes", array( array( "name" => "photo_id", "value" => $id ) ) );

            $images = $images[ "rsp" ];
			
            if ( $images[ "stat" ] == "ok" )
            {
                $images = $images[ "sizes" ][ "size" ];
				$n = count( $images );
				
				$mediumExists = false;
				for( $i = 0; $i<$n; $i++ )
				{
					if( $images[ $i ][ "label" ] == "Medium" ) 
					{
						$mediumExists = true;
						break;
					}
				}
				
				if( $mediumExists )
				{
					$this->photos[ $id ][ "thumb" ] = $images[ 0 ];
					$this->photos[ $id ][ "image" ] = $images[ $i ];
				}
				else 
				{
					$this->photos[ $id ][ "thumb" ] = $images[ 0 ];
					$this->photos[ $id ][ "image" ] = $images[ 0 ];
				}
                
				
				//print_r( $this->photos[ $id ] );
				//echo $this->photos[ $id ][ "thumb" ]."<br/>".$this->photos[ $id ][ "image" ]."<br/>";
            }
        }
    }
	
	/*private function getPhotosGeo()
	{
		$newPhotos = array();
		foreach( $this->photos as $id=>$value )
		{
			$geo = $this->executeRequest( "flickr.photos.geo.getLocation", array( array( "name" => "photo_id", "value" => $id ) ) );
			$geo = $geo[ "rsp" ];
			
			if ( $geo[ "stat" ] == "ok" )
			{
				$a = array_merge( $this->photos[ $id ], $geo[ "photo" ][ "location" ] );
				$newPhotos[ $id ] = $a;
			}
		}
		
		$this->photos = $newPhotos;
	}*/
	
	private function getPhotosInfos()
	{
		$newPhotos = array();
		foreach( $this->photos as $id => $value )
		{
			$infos = $this->executeRequest( "flickr.photos.getInfo", array( array( "name" => "photo_id", "value" => $id ) ) );
			$infos = $infos[ "rsp" ];
			
			if ( $infos[ "stat" ] == "ok" )
			{
				$infos = $infos[ "photo" ];
				if ( !empty( $infos[ "location" ] ) )
				{
					$a = array_merge( $infos, $this->photos[ $id ] );
					$newPhotos[ $id ] = $a;
				}
			}
		}
		
		$this->photos = $newPhotos;
	}
	
	private function generateGeoRSS()
	{
		
		echo($debugMsg);
		
		$str1 = '<rss xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:media="http://search.yahoo.com/mrss">';
		//echo $str1;
		$s=$str1."<channel>\n";
	 	$s=$s."<title>Flickr Gallerie</title>\n";
	    $s=$s."<link>http://www.geocodearth.com</link>\n";
	    $s=$s."<description>V1</description>\n";
	    $s=$s."<language>en</language>\n";
	    $s=$s."<ttl>45</ttl>\n";
		
		foreach( $this->photos as $id => $value )
			$s .= $this->formatItem( $value );
		
		$s .= "</channel>\n";
		$s .= "</rss>\n";
		
		return $s;
	}
	
	private function formatItem( $photoInfos )
    {
        $s = "\n<item>\n";
        $s .= "<title>".$photoInfos[ "title" ]."</title>\n";
        $s .= "<description>".( empty( $photoInfos[ "description" ] ) ? "" : $photoInfos[ "description" ] )."</description>\n";
        $s .= "<geo:lat>".$photoInfos[ "location" ][ "latitude" ]."</geo:lat>\n";
        $s .= "<geo:long>".$photoInfos[ "location" ][ "longitude" ]."</geo:long>\n";
        $s .= "<georss:point>".$photoInfos[ "location" ][ "latitude" ]." ".$photoInfos[ "location" ][ "longitude" ]."</georss:point>\n";
        $s .= "<georss:featurename>".$photoInfos[ "location" ][ "locality" ][ "nodeValue" ].", ".$photoInfos[ "location" ][ "country" ][ "nodeValue" ]."</georss:featurename>\n";
        $s .= "<pubDate>".$photoInfos[ "dateuploaded" ]."</pubDate>\n";
        $s .= "<link>".$photoInfos[ "urls" ][ "url" ][ "nodeValue" ]."</link>\n";
        $s .= "<author><a href='".$photoInfos[ "urls" ][ "url" ][ "nodeValue" ]."'>".$photoInfos[ "owner" ][ "username" ]."</a></author>\n";
        $s .= "<guid>".$photoInfos[ "id" ]."</guid>\n";
        $s .= "<media:content url='".$photoInfos[ "image" ][ "source" ]."' type='image/jpeg'/>\n";
        $s .= "<media:title>".$photoInfos[ "title" ]."</media:title>\n";
        $s .= "<media:thumbnail url='".$photoInfos[ "thumb" ][ "source" ]."' height='".$photoInfos[ "image" ][ "height" ]."' width='".$photoInfos[ "image" ][ "width" ]."' />\n";
        $s .= "</item>\n";
        
        return $s;
    }
	
	private function executeRequest( $method, $params = array() ) // [ [ name, value ] ]
	{
		$url = "http://api.flickr.com/services/rest/?method=".$method."&api_key=".$this->key;
		if ( !empty( $params ) )
		{
			$n = count( $params );
			for ( $i = 0; $i < $n; $i++ )
				$url .= "&".$params[ $i ][ "name" ]."=".$params[ $i ][ "value" ];
		}
		
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, $url );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $c, CURLOPT_HEADER, false );
		$output = curl_exec( $c );
		curl_close( $c );
		
		return  SimpleXML::decode( $output );
	}
	
	public function __destroy()
	{
		
	}

}

?>