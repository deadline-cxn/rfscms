<?php

function to($width,$ot)
{
    echo "<table border=0 width=$width $ot cellspacing=0 cellpadding=0>";
}

function tro($trclass)
{
    echo "<tr>";
}

function trc()
{
    echo "</tr>";
}

function tco($tdclass)
{
    echo "<td class=$tdclass>";
}

function tcc()
{
    echo "</td>";
}

function tcr($tdclass)
{
    tcc();
    tco($tdclass);
}

function tc()
{
    echo "</table>";
}


?>
