<?php

/**
 * Exec command with time out and return result
 * @param p = path
 * @param t = timeout
 * @example /usr/bin/php ExecWaitTimeout.php -p ./test.php -t 5
 */

    $options = getopt("p:t:");

    if (! is_array($options) ) {
        print "There was a problem reading in the options.\n\n";
        exit(1);
    }

    $command = '/usr/bin/php ' . $options['p'];

    echo ExecWaitTimeout($command, $options['t']);

/**
 * Execute a command and kill it if the timeout limit fired to prevent long php execution
 *
 * @see http://stackoverflow.com/questions/2603912/php-set-timeout-for-script-with-system-call-set-time-limit-not-working
 *
 * @param string $cmd Command to exec (you should use 2>&1 at the end to pipe all output)
 * @param integer $timeout
 * @return string Returns command output
 */
function ExecWaitTimeout($cmd, $timeout=5) {
    $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );
    $pipes = array();

    $timeout += time();
    $process = proc_open($cmd, $descriptorspec, $pipes);
    if (!is_resource($process)) {
//        throw new Exception("proc_open failed on: " . $cmd);
    }

    $output = '';

    do {
        $timeleft = $timeout - time();
        $read = array($pipes[1]);
        stream_select($read, $write = NULL, $exeptions = NULL, $timeleft, NULL);

        if (!empty($read)) {
          $output .= fread($pipes[1], 8192);
        }
    } while (!feof($pipes[1]) && $timeleft > 0);

    if ($timeleft <= 0) {
        proc_terminate($process);
//        throw new Exception("command timeout on: " . $cmd);
    }

    return $output;
}