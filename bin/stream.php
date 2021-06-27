<?php

declare(strict_types=1);

namespace Novascript\IoStreamer;

include \dirname(__DIR__).'/vendor/autoload.php';

// \Novascript\IoStreamer\Backup::hello();
$opts = Utils::getOptions([
    'p' => 'profile:*',
    't' => ['test', false],
]);

var_dump($opts);
