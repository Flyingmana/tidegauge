<?php
/**
 *
 *
 *
 *
 */

require_once __DIR__ . '/../vendor/autoload.php';

$console = new \Symfony\Component\Console\Application();

//$console->add(new Cotya\TideGauge\Console\Analyze());

$flatten_spans = function (&$spans) {
    $result = [];
    foreach ($spans as $span) {
        if (!isset($result[$span['a']['title']])) {
            $result[$span['a']['title']] = [
                $span['a']['title'],
                // @TODO I assume multiple values mean a recursive call, we calculate this wrong for now
                max($span['e']) - min($span['b']),
                count($span['b']),
            ];
        } else {
            $result[$span['a']['title']][1] += max($span['e']) - min($span['b']);
            $result[$span['a']['title']][2] += count($span['b']);
        }
    }
    return $result;
};

$console
    ->register('analyze')
    ->setDefinition(array(
    ))
    ->setDescription('Displays the files in the given directory')
    ->setCode(function (
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) use ($flatten_spans) {
        $file = __DIR__ . '/../local/trace_17186.json';
        $file = __DIR__ . '/../local/trace_17717.json';
        $trace = json_decode(file_get_contents($file), true);
        $appInfo = array_shift($trace['spans']);

        $output->writeln('App run info:');
        $table = new \Symfony\Component\Console\Helper\Table($output);
        $table
            ->setRows(array(
                array('id', $trace['id']),
                array('TransactionName', $trace['tx']),
                array('PHP Version:', $appInfo['a']['php']),
                array('title:', $appInfo['a']['title']),
            ))
        ;
        $table->render();
        $output->writeln('App Summary:');
        $table = new \Symfony\Component\Console\Helper\Table($output);
        $tableStyleRightAligned = new \Symfony\Component\Console\Helper\TableStyle();
        $tableStyleRightAligned->setPadType(STR_PAD_LEFT);
        $table->setColumnStyle(1, $tableStyleRightAligned);
        $table
            ->setRows(array(
                array('compile count:', $appInfo['a']['cct']??''),
                array('compile Wall Time:', $appInfo['a']['cwt']??''),
                array('compile CPU Time:', $appInfo['a']['cpu']),
                array('Garbage Collection Runs:', $appInfo['a']['gc']??''),
                array('Garbage Collected:', $appInfo['a']['gcc']??''),
            ))
        ;
        $table->render();

        $table = new \Symfony\Component\Console\Helper\Table($output);
        $table
            ->setHeaders(array('title', 'time', 'call count'))
            ->setRows($flatten_spans($trace['spans']))
        ;
        $table->render();
        
        $output->writeln('End of command');
    })
;

$console->run();
