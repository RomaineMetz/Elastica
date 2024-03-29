<?php

namespace Elastica\Exception\Connection;

use Elastica\Exception\ConnectionException;
use Elastica\Request;
use Elastica\Response;

/**
 * Connection exception.
 *
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
class HttpException extends ConnectionException
{
    /**
     * Error code / message.
     *
     * @var int Error code / message
     */
    protected $_error = 0;

    /**
     * Construct Exception.
     *
     * @param int $error
     */
    public function __construct($error, ?Request $request = null, ?Response $response = null)
    {
        $this->_error = $error;

        $message = $this->getErrorMessage($this->getError());
        parent::__construct($message, $request, $response);
    }

    /**
     * Returns the error message corresponding to the error code
     * cUrl error code reference can be found here {@link http://curl.haxx.se/libcurl/c/libcurl-errors.html}.
     *
     * @param int $error Error code
     *
     * @return string Error message
     */
    public function getErrorMessage(int $error): string
    {
        return match ($error) {
            \CURLE_UNSUPPORTED_PROTOCOL => 'Unsupported protocol',
            \CURLE_FAILED_INIT => 'Internal cUrl error?',
            \CURLE_URL_MALFORMAT => 'Malformed URL',
            \CURLE_COULDNT_RESOLVE_PROXY => "Couldn't resolve proxy",
            \CURLE_COULDNT_RESOLVE_HOST => "Couldn't resolve host",
            \CURLE_COULDNT_CONNECT => "Couldn't connect to host, Elasticsearch down?",
            \CURLE_OPERATION_TIMEOUTED => 'Operation timed out',
            default => 'Unknown error:'.$error,
        };
    }

    /**
     * Return Error code / message.
     *
     * @return int Error code / message
     */
    public function getError(): int
    {
        return $this->_error;
    }
}
