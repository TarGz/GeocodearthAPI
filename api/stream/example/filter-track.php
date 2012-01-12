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
    $data = json_decode($status, true);
    $file = fopen("track.txt", "a");
    

    if (is_array($data) && isset($data['user']['screen_name'])) {
      //print $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
      
      //fwrite($file, $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n");
      fwrite($file, $status . "\n");
      
      
      
    }
  }
}

// Start streaming
$sc = new FilterTrackConsumer('targz', 'egh5umpv28', Phirehose::METHOD_FILTER);
$sc->setTrack(array('test123'));
$sc->consume();




