<?php
session_start();
require_once('../lib/Phirehose.php');
/**
 * Example of using Phirehose to display a live filtered stream using track words 
 */
class FilterTrackConsumer extends Phirehose{
  /**
   * Enqueue each status
   *
   * @param string $status
   */
	public function enqueueStatus($status) {
    /*
     * In this simple example, we will just display to STDOUT rather than enqueue.
     * NOTE: You should NOT be processing tweets at this point in a real application, instead they should be being
     *       enqueued and processed asyncronously from the collection process. 
     */
	echo $status;
	//if( isset($_SESSION['TEST']) && $_SESSION['TEST'] >= 40 ) die();
	//$_SESSION['TEST']++;
	/*
    $data = json_decode($status, true);
    if (is_array($data) && isset($data['user']['screen_name'])) {
      print $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
		die();
    }else{
	  print $status;
	}
	*/
  }
}

if( isset($_GET['pwd']) && $_GET['pwd']!= '' ){
	$login	= $_GET['login'];
	$pwd	= $_GET['pwd'];
}else{
	$login	= $_GET['login'];//VGFyR3oyOnB1bHBwdWxw
	$pwd	= '';
}
if( $login == '' ) die('<status><err>1</err><errMess>No login</errMess></status>');
// Start streaming
$sc = new FilterTrackConsumer($login, $pwd, Phirehose::METHOD_FILTER, Phirehose::FORMAT_XML);
//$sc->setCount(2);
$sc->setTrack(array('lapatate', 'achovovich', 'targz'));
$sc->consume();