<?php

return [
    'cmscensus:scheduler' => [
        'class' => \AUBA\CmsCensus\Command\UrlSchedulerRunCommand::class,
        'schedulable' => true,
    ],
];
