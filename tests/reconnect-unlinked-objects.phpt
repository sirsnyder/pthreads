--TEST--
Test pthread object recovery
--DESCRIPTION--
This test verifies the pthread object recovery of a joined thread
--FILE--
<?php
$share = new Threaded();
$linked = new Threaded();
class Test extends Thread
{
    private $share;
    private $linked;
    public function __construct(Threaded $share, Threaded $linked)
    {
        $this->share    = $share;
        $this->linked   = $linked;
    }
    public function run()
    {
        $this->share['anykey'] = $this->linked;
        $this->linked[] = 'set by thread';
    }
}
$thread = new Test($share, $linked);
$thread->start();
$thread->join();
var_dump($share);
?>
--EXPECTF--
object(Threaded)#1 (1) {
  ["anykey"]=>
  object(Threaded)#2 (1) {
    [0]=>
    string(13) "set by thread"
  }
}