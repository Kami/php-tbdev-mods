<?php

ob_start("ob_gzhandler");
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

$akcija = $_GET['akcija'];
$idtorrenta = $_GET['idtorrenta'];
$zaprosil = $_GET['zaprosil'];
$id = $_GET['id'];

stdhead("Free Leech requests");

begin_main_frame();

if (get_user_class() < UC_ADMINISTRATOR)
{
stdmsg("Error", "No access.");
stdfoot();
exit;
}

echo "<h1 align=center>Free Leech requests</h1>\n";

if ($akcija == "odobri")
{
$ime = mysql_result(mysql_query("SELECT name FROM torrents WHERE id = '$idtorrenta'"), 0);
$ime1 = $ime . " [FreeLeech]";
$datum = get_date_time();
$sporocilo = "Free leech request for torrent \[url=details.php?id=$idtorrenta\]$ime\[/url\] was approved!";

mysql_query("UPDATE torrents SET freeleech='yes', name='$ime1' WHERE id='$idtorrenta'");
mysql_query("UPDATE zaprosifreeleech SET status='approved' WHERE id='$id'");
mysql_query("INSERT INTO messages (sender, receiver, added, msg) VALUES ('0', '$zaprosil', '$datum', '$sporocilo')");
header("Location: freeleechprosnje.php");
}

if ($akcija == "zavrni")
{
$ime = mysql_result(mysql_query("SELECT name FROM torrents WHERE id = '$idtorrenta'"), 0);
$datum = get_date_time();
$sporocilo = "Free leech request for torrent \[url=details.php?id=$idtorrenta\]$ime\[/url\] was rejected!";

mysql_query("UPDATE zaprosifreeleech SET status='rejected' WHERE id='$id'");
mysql_query("INSERT INTO messages (sender, receiver, added, msg) VALUES ('0', '$zaprosil', '$datum', '$sporocilo')");
header("Location: freeleechprosnje.php");
}

if ($akcija == "zavrni")
{
}

if ($akcija == "")
{
$rezultat = mysql_query("SELECT * FROM zaprosifreeleech ORDER BY id ASC");

if (mysql_num_rows($rezultat) > 0)
{
echo "<div align=\"center\"><table border=\"0\" cellpadding=\"2px\">
<tr>
<td class=rowhead><center>Name</center></td>
<td class=rowhead><center>Requested</center></td>
<td class=rowhead><center>Status</center></td>
<td class=rowhead><center>Approve</center></td>
<td class=rowhead><center>Reject</center></td>
</tr>";

while ($vrstica = mysql_fetch_array($rezultat))
{
$uporabnik = mysql_result(mysql_query("SELECT username FROM users WHERE id = '$vrstica[zaprosil]'"), 0);
$torrent = format_comment(mysql_result(mysql_query("SELECT name FROM torrents WHERE id = '$vrstica[idtorrenta]'"), 0));
$freeleech = mysql_result(mysql_query("SELECT freeleech FROM torrents WHERE id = '$vrstica[idtorrenta]'"), 0);

if ($vrstica[status] == "approved")
{
$barva = "#00CC00";
}
elseif ($vrstica[status] == "rejected")
{
$barva = "#FF0000";
}
else
{
$barva = "#CCCCCC";
}

echo "<tr>
<td bgcolor=\"$barva\"><center><a href=\"details.php?id=$vrstica[idtorrenta]\" target=\"_blank\">$torrent</a></center></td>
<td bgcolor=\"$barva\"><center>$uporabnik</center></td>
<td bgcolor=\"$barva\"><center>$vrstica[status]</center></td>";
if ($vrstica[status] == "undecided")
{
echo "<td bgcolor=\"$barva\"><center><a href=\"freeleechprosnje.php?akcija=odobri&idtorrenta=$vrstica[idtorrenta]&zaprosil=$vrstica[zaprosil]&id=$vrstica[id]\">approve</a></center></td>
<td bgcolor=\"$barva\"><center><a href=\"freeleechprosnje.php?akcija=zavrni&idtorrenta=$vrstica[idtorrenta]&zaprosil=$vrstica[zaprosil]&id=$vrstica[id]\">reject</a></center></td></tr>";
}

else
{
echo "<td bgcolor=\"$barva\"><center>/</center></td>
<td bgcolor=\"$barva\"><center>/</center></td></tr>";
}
}

echo "</table></div>";
}

else
{
echo "<div align=\"center\">No requests for free leech torrents</div>";
}
}

stdfoot();
die();
?>