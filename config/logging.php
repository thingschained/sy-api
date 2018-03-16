<?php

return [
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['syslog'],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],
    ],
]
;