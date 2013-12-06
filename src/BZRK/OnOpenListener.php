<?php

namespace BZRK;

use Ratchet\ConnectionInterface;

interface OnOpenListener
{
    public function execute(ConnectionInterface $to);
}