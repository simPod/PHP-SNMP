<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport\Cli;

use SimPod\PhpSnmp\Exception\SnmpCliError;
use Symfony\Component\Process\Process;

class SnmpBulkWalk
{
    /** @var string */
    private $version;

    /** @var string */
    private $community;

    /** @var string */
    private $host;

    /** @var int */
    private $timeout;

    /** @var int */
    private $retries;

    /** @var int */
    private $commandTimeout;

    public function __construct(
        string $community,
        string $host,
        int $timeout,
        int $retries,
        int $commandTimeout,
        string $version
    ) {
        $this->community      = $community;
        $this->host           = $host;
        $this->timeout        = $timeout;
        $this->retries        = $retries;
        $this->commandTimeout = $commandTimeout;
        $this->version        = $version;
    }

    public function execute(string $oid) : string
    {
        $process = new Process(
            [
                'snmpbulkwalk',
                '-ObentU',
                '-t',
                $this->timeout,
                '-r',
                $this->retries,
                '-v',
                $this->version,
                '-c',
                $this->community,
                $this->host,
                $oid,
            ]
        );
        $process->setTimeout($this->commandTimeout);
        $process->run();

        if (! $process->isSuccessful()) {
            throw SnmpCliError::generic($oid, $process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
