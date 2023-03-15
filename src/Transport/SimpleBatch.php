<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use SimPod\PhpSnmp\Exception\NoRequestsProvided;

trait SimpleBatch
{
    /** @inheritDoc */
    public function batch(array $requests): array
    {
        if ($requests === []) {
            throw NoRequestsProvided::new();
        }

        $result = [];
        foreach ($requests as $key => $request) {
            switch ($request->type) {
                case Request::Get:
                    $result[$key] = $this->get($request->oids);

                    break;
                case Request::GetNext:
                    $result[$key] = $this->getNext($request->oids);

                    break;
                case Request::Walk:
                    $result[$key] = $this->walk($request->oids[0], (int) $request->maxRepetitions);
            }
        }

        return $result;
    }
}
