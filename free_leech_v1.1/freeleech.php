<?php

ob_start("ob_gzhandler");
require_once("include/bittorrent.php");

dbconn();
loggedinorreturn();

$akcija = $_GET['akcija'];
$napaka = (int)$_GET['napaka'];
$odstraniid = (int)$_GET['odstraniid'];

stdhead("Free Leech");

begin_main_frame();

if (get_user_class() < UC_MODERATOR)
{
stdmsg("Error", "No access.");
end_main_frame();
stdfoot();
exit;
}

echo "<h1 align=center>Free Leech</h1>\n";

if ($napaka == 1)
{
echo "<br /><font color=\"red\">Torrent with this ID doesn't exist!</font><br />";
}

if ($napaka == 2)
{
echo "<br /><font color=\"red\">This torrent is now free leech!</font><br />";
}

if ($napaka == 3)
{
echo "<br /><font color=\"red\">This torrent isn't free leech anymore!</font><br />";
}

if ($napaka == 4)
{
echo "<br /><font color=\"red\">This torren't isn't freeleech!</font><br />";
}

echo "<br /><form name=\"freeleech\" method=\"post\" action=\"freeleech.php?akcija=poslji\">

Torrent ID:

<input name=\"id\" type=\"text\" value=\"\" />

(You need to enter id of the torrent you want to make free leech)

<br /><br />

<input type=\"submit\" value=\"Send\"></form>";

if ($akcija == "poslji")
{
$id = (int)$_POST['id'];
$rezultat = mysql_query("SELECT * FROM torrents WHERE id = '$id'");

if (mysql_num_rows($rezultat) < 1)
{
header("Location: freeleech.php?napaka=1");
die();
}
else
{
mysql_query("UPDATE torrents SET freeleech = '1' WHERE id = '$id'");
}

header("Location: freeleech.php?napaka=2");
}

echo "<br /><div align=\"center\"><h1>Free leech torrents</h1><br />";

$rezultat = mysql_query("SELECT * FROM torrents WHERE freeleech = '1'");

if (mysql_num_rows($rezultat) > 0)
{
echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" class=\"main\">
<tr>
<td class=rowhead><center>Id</center></td>
<td class=rowhead><center>Name</center></td>
<td class=rowhead><center>FreeLeech</center></td>
</tr>";

while ($vrstica = mysql_fetch_array($rezultat))
{
echo "
<tr>
<td><center>$vrstica[id]</center></td>
<td><center><a href=\"details.php?id=$vrstica[id]\" target=\"_blank\">".format_comment($vrstica[name])."</a></center></td>
<td><center><a href=\"freeleech.php?akcija=odstrani&odstraniid=$vrstica[id]\">Remove</a></center></td>
</tr>";
}
}
else
{
echo "No free leech torrents!";
}

echo "</table><br /><br />Copyrights © for free leech by Kami</div>";

if ($akcija == "odstrani")
{
$rezultat = mysql_query("SELECT * FROM torrents WHERE id = '$odstraniid' AND freeleech = '1'");

if (mysql_num_rows($rezultat) < 1)
{
header("Location: freeleech.php?napaka=4");
die();
}
else
{
mysql_query("UPDATE torrents SET freeleech = '0' WHERE id = '$odstraniid'");
}

header("Location: freeleech.php?napaka=3");
}

end_main_frame();
stdfoot();
die();
?>