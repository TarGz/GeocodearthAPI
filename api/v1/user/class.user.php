<?php
class user{

	private $userId;

	public function __construct($userId){
		$this->userId = (int)$userId;
	}

	public function save($flux){

		if( $flux == '' ) return $this->displayResult(-1, 'ko');
		
		$flux = urldecode($flux);
		
		$sql = "INSERT INTO 
					T_GeoFlux
				(
					user_id,
					flux,
					date_flux
				)VALUES(
					".$this->userId.",
					'".mysql_real_escape_string($flux)."',
					NOW()
				)";

		if( mysql_query($sql) ){
			return $this->displayResult(mysql_insert_id(), 'ok');
		}
		return $this->displayResult(-1, 'ko');
	}

	public function update($flux, $id){
		$flux = urldecode($flux);
		if( $flux == '' )	return $this->displayResult(-1, 'ko');
		if( (int)$id == 0 ) return $this->displayResult(-1, 'ko');
		
		$sql = "UPDATE 
					T_GeoFlux
				SET
					flux = '".mysql_real_escape_string($flux)."',				
					date_flux = now()
				WHERE
					id=".(int)$id."	
				AND
					user_id=".$this->userId."
				";
		if( mysql_query($sql) ){
			return $this->displayResult($id, 'ok');
		}
		return $this->displayResult(-1, 'ko3');

	}

	public function delete($id){

		if( (int)$id == 0 ) return $this->displayResult(-1, 'ko');

		$sql = "DELETE FROM 
					T_GeoFlux
				WHERE
					id=".(int)$id."	
				AND
					user_id=".$this->userId."
				";
		mysql_query($sql);
		$sql = "SELECT flux FROM T_GeoFlux WHERE id=".(int)$id;
		$query = mysql_query($sql);
		if( mysql_num_rows($query) == 0 )
			return $this->displayResult($id, 'ok');

		return $this->displayResult($id, 'ko');
	}

	public function getFlux($id){
		$sql = "SELECT
					flux,id
				FROM
					T_GeoFlux
				WHERE
					user_id=".$this->userId."
				";
		$query = mysql_query($sql);
		$flux = '';
		while( $data = mysql_fetch_array($query) ){
			$flux.= $data['flux'];
			$flux = str_replace('##new##',$data['id'],$flux);
		}
		//
		return $this->baseXML($flux, true);
	}	
	
	private function displayResult($id, $message){
		return $this->baseXML("<result><id>".(int)$id."</id><message>".$message."</message></result>");
	}

	private function baseXML($content, $showSources=false){
		$show  = "<conf>";
		$show .= ( $showSources ) ? "<geoRssSources>".urldecode($content)."</geoRssSources>" : $content;
		$show .= "</conf>";
		return $show;
	}
}
?>