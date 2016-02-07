<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/FileDumperBackend.php';

\Tideways\Profiler::setBackend(new \Cotya\TideGauge\FileDumperBackend());

\Tideways\Profiler::start([
    'api_key' => 'random_api_key',
    'sample_rate' => 100,
]);
\Tideways\Profiler::setTransactionName("cli:" . basename($_SERVER['argv'][0]));

\Tideways\Profiler::watch('TestClass::FooBar');
\Tideways\Profiler::watch('Composer\Autoload\ClassLoader::loadClass');

class TestClass {
    public function __construct() {
        
    }
    
    public function FooBar() {
        
    }
}

function createRandomString()
{
    $test = new TestClass();
    $test->FooBar();
    return "42";
}

//ld('start test');

$strings = [];
for( $i=0; $i < 3; $i++ ) {
    $strings[] = createRandomString();
}


\Tideways\Profiler::stop();
