<?php namespace framework;

class ModelLogger
{
    private $logFilename;


    public function __construct($loggerName)
    {
        $this->logFilename = FRAMEWORK_LOG_FOLDER . "$loggerName.log";
    }

    public function writeMessage($message)
    {
        $lines = splitLines($message);
        $trace = debug_backtrace();
        $this->writeLines($trace, $lines);
    }

    public function writeDataArray($dataArray)
    {
        // Use var dump because it can handle recursion
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
        $lines = array();
        if (file_exists($this->logFilename))
        {
            $lines = array_filter(splitLines(file_get_contents($this->logFilename)));
        }
        foreach($arrayWithLines as $line)
        {
            if ($line != "")
            {
                $lines[] = "$timeStamp - $callerName - $line";
            }
        }
        if (count($lines) > MAX_LOG_LINES)
        {
            $lines = array_slice($lines, -MAX_LOG_LINES);
        }
        file_put_contents($this->logFilename, implode("\n", $lines));
    }
}
