<?php

ob_start("ob_gzhandler");
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

$akcija = $_GET['akcija'];
$napaka = $_GET['napaka'];
$odstraniid = $_GET['odstraniid'];

stdhead("Free Leech");

begin_main_frame();

if (get_user_class() < UC_ADMINISTRATOR)
{
stdmsg("Napaka", "Nimate dostopa.)"); // You don't have access
stdfoot();
exit;
}

echo "<h1 align=center>Free Leech</h1>\n";

if ($napaka == 1)
{
echo "<br /><font color=\"red\">Torrent z tem ID-jem ne obstaja!</font><br />"; // Torrent with this ID doesn't exist
}



if ($napaka == 2)
{
echo "<br /><font color=\"red\">Torrent je uspešno postal free leech!</font><br />"; // This torrent is now free leech
}

if ($napaka == 3)
{
echo "<br /><font color=\"red\">Ta torrent veè sedaj ni free leech!</font><br />"; // This torrent isn't free leech anymore
}

echo "<br /><form name=\"\" method=\"post\" action=\"freeleech.php?akcija=poslji\">

Id torrenta:

<input name=\"idtorrenta\" type=\"text\" value=\"\" />

(vnesite ID torrenta za kategera hocete nastavit da je free leech, ce ne vete ki najdete ID niste vredni svojega statusa!

<br /><br />

<input type=\"submit\" value=\"Poslji\"></form>"; // You need to enter id of the torrent you want to make free leech



if ($akcija == "poslji")
{
$idtorrenta = $_POST['idtorrenta'];
$rezultat = mysql_query("SELECT * from torrents WHERE id='$idtorrenta'");
$ime = mysql_query("SELECT name from torrents WHERE id='$idtorrenta' LIMIT 1");

if (mysql_num_rows($rezultat) < 1)
{
header("Location: freeleech.php?napaka=1");
die();
}



while ($vrstica = mysql_fetch_array($ime))
{
$ime1 = $vrstica[name] . " [FreeLeech]";
mysql_query("UPDATE torrents SET freeleech='yes', name='$ime1' WHERE id='$idtorrenta'");
}
header("Location: freeleech.php?napaka=2");

}

echo "<br /><div align=\"center\"><h1>Free leech torrenti</h1><br />";

$rezultat = mysql_query("SELECT * from torrents WHERE freeleech='yes'");

echo "<table width=\"500\" border=\"0\">
<tr>
<td class=rowhead><center>Id</center></td>
<td class=rowhead><center>Ime</center></td>
<td class=rowhead><center>FreeLeech</center></td>
</tr>"; // Ime = Name



while ($vrstica = mysql_fetch_array($rezultat))
{
echo "
<tr>
<td><center>$vrstica[id]</center></td>
<td><center><a href=\"http://www.slo-scene.net/details.php?id=$vrstica[id]\" target=\"_blank\">".format_comment($vrstica[name])."</a></center></td>
<td><center><a href=\"freeleech.php?akcija=odstrani&odstraniid=$vrstica[id]\">Odstrani</a></center></td>
</tr>"; // Odstrani = Remove
}
echo "</table><br /><br />Copyrights © for free leech by Kami</div>";

if ($akcija == "odstrani")
{
$rezultat = mysql_query("SELECT * from torrents WHERE id='$odstraniid'");
mysql_query("UPDATE torrents SET freeleech='no' WHERE id='$odstraniid'");

while ($vrstica = mysql_fetch_array($rezultat))
{
$ime = str_replace(" [FreeLeech]", " ", $vrstica[name]);
mysql_query("UPDATE torrents SET name='$ime' WHERE id='$odstraniid'");
}

header("Location: freeleech.php?napaka=3");
}
stdfoot();
die();
?>