<?php

declare(strict_types=1);

namespace Novascript\IoStreamer;

include \dirname(__DIR__).'/vendor/autoload.php';

$opts = Utils::getOptions([
    'p' => 'profile:*',
    't' => ['test', false],
]);

if (isset($opts['profile'])) {
    foreach ($opts['profile'] as $profileName) {
        (new Profile($profileName))->execute();
    }
} else {
    exit('Nothing to do (profile not defined)'."\n");
}
