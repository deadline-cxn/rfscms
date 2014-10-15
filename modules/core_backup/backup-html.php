<?
$fhtml="/backup/".$dt.".html.7z";
system("7z a $fhtml /var/www -mx9 -xr!webmin -xr!library -xr!files -xr!_errorpages -xr!_cgi-bin -xr!.ssh");
?>
