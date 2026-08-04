<?php

$quiz = strtolower($_GET['quiz'] ?? 'quiz3');

$file = "/var/www/quiz/result/$quiz/result_{$quiz}.txt";

$users = [];

if (file_exists($file)) {

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        // Only numeric result lines
        if (!preg_match('/^\s*\d+\s+/', $line))
            continue;

        $line = trim($line);

        // Extract:
        // SR NAME DATE TIME TOTAL PASSED PERCENT%
        preg_match(
            '/^\d+\s+(.+?)\s+(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})\s+(\d+)\s+(\d+)\s+(\d+)%$/',
            $line,
            $m
        );

        if (!$m)
            continue;

        $name       = trim($m[1]);
        $date       = $m[2];
        $total      = (int)$m[3];
        $passed     = (int)$m[4];
        $percentage = (int)$m[5];

        // Normalize name key
        $key = strtolower(trim(preg_replace('/\s+/', ' ', $name)));

        // 🚨 STRICT FILTER: remove invalid names
        if ($key === "" || $key === "null") {
            continue;
        }

        // Keep ONLY best attempt per user
        if (
            !isset($users[$key]) ||
            $percentage > $users[$key]['percentage']
        ) {
            $users[$key] = [
                'name' => $name,
                'date' => $date,
                'total' => $total,
                'passed' => $passed,
                'percentage' => $percentage
            ];
        }
    }
}

// Convert to indexed array
$users = array_values($users);

// Sorting logic:
// 1. Highest percentage
// 2. Highest passed
// 3. Name A-Z
usort($users, function ($a, $b) {

    if ($a['percentage'] == $b['percentage']) {

        if ($a['passed'] == $b['passed']) {
            return strcasecmp($a['name'], $b['name']);
        }

        return $b['passed'] <=> $a['passed'];
    }

    return $b['percentage'] <=> $a['percentage'];
});

?>

<!DOCTYPE html>
<html>
<head>
<title>Leaderboard</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f7fc;
    margin: 0;
    padding: 20px;
    text-align: center;
}

h2 {
    color: #0066cc;
}

table {
    width: 90%;
    margin: auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
}

th {
    background: #0066cc;
    color: white;
    padding: 12px;
    font-size: 16px;
}

td {
    padding: 10px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

td:nth-child(2) {
    text-align: left;
    padding-left: 15px;
    font-weight: 500;
}

tr:nth-child(even) {
    background: #f4f7fc;
}

tr:hover {
    background: #e6f0ff;
}
</style>
</head>

<body>

<h2>Leaderboard - <?php echo htmlspecialchars(strtoupper($quiz)); ?></h2>

<table>
    <thead>
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Date</th>
            <th>Total</th>
            <th>Passed</th>
            <th>Percentage</th>
        </tr>
    </thead>

    <tbody>

    <?php
    $rank = 1;

    if (count($users) === 0) {
        echo "<tr><td colspan='6'>No results found</td></tr>";
    } else {
        foreach ($users as $u) {
            echo "<tr>";
            echo "<td>{$rank}</td>";
            echo "<td>{$u['name']}</td>";
            echo "<td>{$u['date']}</td>";
            echo "<td>{$u['total']}</td>";
            echo "<td>{$u['passed']}</td>";
            echo "<td>{$u['percentage']}%</td>";
            echo "</tr>";
            $rank++;
        }
    }
    ?>

    </tbody>
</table>

<br>

<button onclick="window.location.href='/index.html'"
style="padding:10px 20px; background:#999; color:white; border:none; border-radius:8px; cursor:pointer;">
Back to Home
</button>

</body>
</html>
