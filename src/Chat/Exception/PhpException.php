<?php

declare(strict_types=1);

namespace Chat\Exception;

/**
 * Class PhpException
 *
 * Base class for all PHP exceptions
 *
 * @package System\Exception
 */
class PhpException extends \Exception
{
    /**
     * @var string
     */
    private $errorFile;

    /**
     * @var int
     */
    private $errorLine;

    /**
     * @var array
     */
    private $errorContext;

    /**
     * Constructor
     *
     * @param int    $errorCode
     * @param string $errorDescription
     * @param string $errorFile
     * @param int    $errorLine
     * @param array  $errorContext
     */
    public function __construct(
        int $errorCode,
        string $errorDescription,
        string $errorFile,
        int $errorLine,
        array $errorContext = []
    ) {
        parent::__construct(
            sprintf(
                'PHP error [%s] [%s] occurred in %s at line %s',
                $errorCode,
                $errorDescription,
                $errorFile,
                $errorLine
            ),
            $errorCode
        );

        $this->errorContext = $errorContext;
        $this->errorFile    = $errorFile;
        $this->errorLine    = $errorLine;
    }

    /**
     * Returns error context
     *
     * @return array
     */
    public function getErrorContext() : array
    {
        return $this->errorContext;
    }

    /**
     * Returns error file name
     *
     * @return string
     */
    public function getErrorFileName() : string
    {
        return $this->errorFile;
    }

    /**
     * Returns error line number
     *
     * @return int
     */
    public function getErrorLine() : int
    {
        return $this->errorLine;
    }
}
