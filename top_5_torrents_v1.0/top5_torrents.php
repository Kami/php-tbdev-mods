<?php

require("include/bittorrent.php");

dbconn();

$rezultat = mysql_query("SELECT id, name, times_completed, seeders, leechers FROM torrents ORDER BY times_completed DESC LIMIT 5");

echo "<table border=\"0\" cellpadding=\"2\">";

$i = 0;

echo "<tr>";
echo "<td><b>Rank</b></td>";
echo "<td><b>Name</b></td>";
echo "<td><b>Snatches</b></td>";
echo "<td><b>Seeders</b></td>";
echo "<td><b>Leechers</b></td>";
echo "</tr>";

while ($vrstica = mysql_fetch_array($rezultat))
{
    $i++;
    echo "<tr>";
    echo "<td>$i</td>";
    echo "<td><a href=\"details.php?id=$vrstica[id]\">$vrstica[name]</a></td>";
    echo "<td>$vrstica[times_completed]</td>";
    echo "<td>$vrstica[seeders]</td>";
    echo "<td>$vrstica[leechers]</td>";
    echo "</tr>";
}

echo "</table>";

?> 