<?php
/**
 * @author Thomas Wünsche <t.wuensche@ostec.de>
 */
namespace BZRK;

use BZRK\Message;
use \Ratchet\MessageComponentInterface;
use \Ratchet\ConnectionInterface;
use \Psr\Log\LoggerInterface;

class Board implements MessageComponentInterface
{
    protected $clients;
    protected $logger;
    
    private $messageListener = array();
    private $openListener = array();

    public function __construct(LoggerInterface $logger) 
    {
        $this->clients = new \SplObjectStorage();
        $this->logger  = $logger;
    }
    
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $this->logger->info('Connected closed');
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        $this->logger->error($e->getMessage());
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->logger->info('Connection opening');
        foreach($this->openListener as $listener){
            $listener->execute($conn);
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->logger->info('received: ' . $msg);
        $msg = Message::createInstance($msg);
        foreach($this->messageListener as $listener){
            $listener->execute($from, $msg);
        }
    }
    
    public function send(ConnectionInterface $to, Message $msg)
    {
        $this->logger->info('send: ' . $msg);
        $to->send($msg);
    }
    
    public function sendAll(Message $msg)
    {
        foreach($this->clients as $client){
            $this->send($client, $msg);
        }
    }
    
    public function addOnMessageListener(OnMessageListener $listener)
    {
        $this->messageListener[get_class($listener)] = $listener;
    }
    
    public function addOnOpenListener(OnOpenListener $listener)
    {
        $this->openListener[get_class($listener)] = $listener;
    }
}
