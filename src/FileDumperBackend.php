<?php
/**
 *
 *
 *
 *
 */

namespace Cotya\TideGauge;

use Tideways\Profiler\Backend;

class FileDumperBackend implements Backend
{
    
    public function __construct()
    {
    }

    public function socketStore(array $trace)
    {
        $this->store($trace);
    }

    public function udpStore(array $trace)
    {
        $this->store($trace);
    }
    
    protected function store(array $trace)
    {
        $directory = __DIR__.'/../local/';
        //file_put_contents($directory.'/trace_'.getmypid().'.json', json_encode($trace), FILE_APPEND);
        file_put_contents($directory.'/trace_'.getmypid().'.json', json_encode($trace, JSON_PRETTY_PRINT), FILE_APPEND);
        //file_put_contents($directory.'/trace_'.getmypid().'.json', var_export($trace, true), FILE_APPEND);
    }
}
