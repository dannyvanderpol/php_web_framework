<?php namespace framework;

class ModelLogger
{
    private $logFilename;
    private $buffer = [];

    public function __construct($loggerName)
    {
        $this->logFilename = LOG_FOLDER . "$loggerName.log";
    }

    public function __destruct()
    {
       $this->flush();
    }

    public function writeMessage($message)
    {
        $lines = splitLines($message);
        $trace = debug_backtrace();
        $this->writeLines($trace, $lines);
    }

    public function writeDataArray($dataArray)
    {
        // Use var_dump because it can handle recursion
        ob_start();
        var_dump($dataArray);
        $dataString = ob_get_clean();
        $lines = splitLines(formatVarDump($dataString));
        $trace = debug_backtrace();
        $this->writeLines($trace, $lines);
    }

    private function writeLines($trace, $arrayWithLines)
    {
        $callerName = "";
        if (isset($trace[1]["function"]))
        {
            $callerName = $trace[1]["function"];
        }
        $date = new \DateTime();
        $timeStamp = $date->format(LOG_TIME_FORMAT);
        foreach($arrayWithLines as $line)
        {
            if ($line != "")
            {
                $this->buffer[] = "$timeStamp - $callerName - $line";
            }
        }
        if (count($this->buffer) >= LOG_FLUSH_THRESHOLD)
        {
            $this->flush();
        }
    }

    private function flush()
    {
        if (count($this->buffer) > 0)
        {
            $lines = [];
            if (file_exists($this->logFilename))
            {
                $lines = array_filter(splitLines(file_get_contents($this->logFilename)));
            }
            $lines = array_merge($lines, $this->buffer);
            $lines = array_slice($lines, -MAX_LOG_LINES);
            file_put_contents($this->logFilename, implode("\n", $lines) . "\n");
            $this->buffer = [];
        }
    }
}
