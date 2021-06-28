<?php

namespace Novascript\IoStreamer;

class Report
{
    /**
     * Dumps variable to STD_ERR if available.
     *
     * @param mixed $message Variable to be printed
     */
    public static function show($message, $showTime = true): void
    {
        if (!\is_scalar($message)) {
            $message = \var_export($message, true);
        }
        $message = ($showTime ? '['.\date('Y-m-d H:i:s').'] ' : '').$message;
        static::stderr($message);
    }

    protected static function stderr(string $str): void
    {
        if (\defined('STDERR')) {
            fwrite(\STDERR, $str."\n");
        }
    }
}
