<?php
require_once('../lib/Phirehose.php');
/**
 * Example of using Phirehose to display a live filtered stream using track words 
 */
class FilterTrackConsumer extends Phirehose
{
  /**
   * Enqueue each status
   *
   * @param string $status
   */
  public function enqueueStatus($status)
  {
    /*
     * In this simple example, we will just display to STDOUT rather than enqueue.
     * NOTE: You should NOT be processing tweets at this point in a real application, instead they should be being
     *       enqueued and processed asyncronously from the collection process. 
     */
	 echo $status;
	 /*
    $data = json_decode($status, true);
    if (is_array($data) && isset($data['user']['screen_name'])) {
      print $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
    }
	*/
  }
}

// Start streaming
$sc = new FilterTrackConsumer('achovovich', 'gtsn566n', Phirehose::METHOD_FILTER, Phirehose::FORMAT_XML);
$sc->setTrack(array('achovovich', 'targz', 'lapatate'));
$sc->consume();