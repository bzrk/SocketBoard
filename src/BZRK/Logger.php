<?php
/**
 * @author Thomas Wünsche <t.wuensche@ostec.de>
 */
namespace BZRK;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    public function log($level, $message, array $context = array())
    {
        $message = str_replace(chr(10), chr(10) . '   ', $message);
        
        printf(
            '[%s]-[%s] %s' . chr(10), 
            date('Y-m-d H:i:s'),
            $level, 
            $message
        );
    }
}
