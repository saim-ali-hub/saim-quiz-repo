<?php
$quiz = $_GET['quiz'] ?? 'quiz3';
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
    font-size: 14px;
    font-weight: 500;
    padding-left: 15px;
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

<h2>Leaderboard - <?php echo htmlspecialchars($quiz); ?></h2>

<table id="leaderboardTable" border="1">
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
    <tbody></tbody>
</table>

<script>
const QUIZ = "<?php echo strtolower($quiz); ?>";

async function loadLeaderboard() {

    const response = await fetch(
        `/quiz/result/${QUIZ}/result_${QUIZ}_sorted.csv`
    );

    const text = await response.text();

    const tbody =
        document.querySelector("#leaderboardTable tbody");

    tbody.innerHTML = "";

    text.trim().split("\n").forEach(line => {

        if (
            !line ||
            line.includes("Result") ||
            line.includes("====") ||
            line.includes("Rank|") ||
            line.trim() === ""
        ) return;

        const parts = line.split("|");

        if (parts.length < 6) return;

        const [
            rank,
            name,
            date,
            total,
            passed,
            percentage
        ] = line.split("|");

        tbody.innerHTML += `
            <tr>
                <td>${rank}</td>
                <td>${name}</td>
                <td>${date}</td>
                <td>${total}</td>
                <td>${passed}</td>
                <td>${percentage}%</td>
            </tr>
        `;
    });
}

loadLeaderboard();
</script>

<br>
<button onclick="window.location.href='/index.html'"
style="padding:10px 20px; background:#999; color:white; border:none; border-radius:8px; cursor:pointer;">
Back to Home
</button>

</body>
</html>
