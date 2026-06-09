<?php

$action = $_GET['action'] ?? '';

if ($action === "save_result") {

    $student = $_POST['name'] ?? 'UNKNOWN';
    $quiz    = $_POST['quiz'] ?? 'quiz_unknown';
    $total   = $_POST['total'] ?? 0;
    $passed  = $_POST['passed'] ?? 0;
    $percent = $_POST['percent'] ?? 0;

    $date = date("Y-m-d H:i:s");

    // sanitize quiz & student name
    $quiz = preg_replace("/[^a-zA-Z0-9_]/", "", $quiz);
    $student = preg_replace('/[^a-zA-Z0-9 _.-]/', '', $student);

    $file = "/var/www/quiz/result/result_" . $quiz . ".txt";

    if (!file_exists($file)) {
        $header =
        "Result - " . strtoupper($quiz) . "\n" .
        "===============================================================\n" .
        "Sr  Username        Date                Total Passed Percentage\n" .
        "---------------------------------------------------------------\n";

        file_put_contents($file, $header);
    }

    $sr = 1;

    if (file_exists($file)) {
       $lines = file($file, FILE_IGNORE_NEW_LINES);
       $sr = count($lines) - 3;
    }

    $line = sprintf(
        "%-3d %-15s %-19s %-6d %-6d %-10s\n",
        $sr,
        $student,
        $date,
        $total,
        $passed,
        $percent . "%"
    );

    file_put_contents($file, $line, FILE_APPEND);

    echo "OK";
    exit;
}

echo "Invalid action";
