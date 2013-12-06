<?php

namespace BZRK;

use BZRK\Message;
use Ratchet\ConnectionInterface;

interface OnMessageListener
{
    public function execute(ConnectionInterface $to, Message $message);
}