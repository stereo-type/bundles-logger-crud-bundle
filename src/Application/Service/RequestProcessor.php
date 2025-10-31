<?php

declare(strict_types=1);

namespace Slcorp\LoggerCrudBundle\Application\Service;

use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestProcessor
 * @package AppBundle\Util
 */
class RequestProcessor
{
    /**
     * RequestProcessor constructor.
     * @param RequestStack $request
     */
    public function __construct(public readonly RequestStack $request)
    {
    }

    /**
     * @param LogRecord $record
     * @return LogRecord
     */
    public function processRecord(LogRecord $record): LogRecord
    {
        $extra = [];
        $req = $this->request->getCurrentRequest();
        if ($req) {
            $extra['client_ip'] = $req->getClientIp();
            $extra['client_port'] = $req->getPort();
            $extra['uri'] = $req->getUri();
            $extra['query_string'] = $req->getQueryString();
            $extra['method'] = $req->getMethod();
            $extra['request'] = $req->request->all();
        } else {
            $extra['error'] = 'Error get extra';
        }

        $record->extra = $extra;

        return $record;
    }
}
