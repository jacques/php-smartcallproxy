<?php declare(strict_types=1);
/**
 * Client for SmartCall's Restful Proxy interface Tests.
 *
 * @author    Jacques Marneweck <jacques@powertrip.co.za>
 * @copyright 2016-2019 Jacques Marneweck.  All rights strictly reserved.
 * @license   MIT
 */
require_once __DIR__.'/../vendor/autoload.php';
\VCR\VCR::configure()
    ->enableRequestMatchers(['method', 'url', 'host'])
    ->setStorage('json');
\VCR\VCR::turnOn();
