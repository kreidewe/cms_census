<?php

declare(strict_types=1);

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()->in(__DIR__ . '/Classes');
$config->getFinder()->in(__DIR__ . '/Configuration');
$config->getFinder()->in(__DIR__ . '/Resources');


return $config;
