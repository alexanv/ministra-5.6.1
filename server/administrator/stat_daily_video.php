<?php

\session_start();
\ob_start();
require __DIR__ . '/common.php';
use Ministra\Lib\Admin;
use Ministra\Lib\AdminAccess;
use Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89;
$error = '';
\Ministra\Lib\Admin::checkAuth();
\Ministra\Lib\Admin::checkAccess(\Ministra\Lib\AdminAccess::ACCESS_VIEW);
echo '<pre>';
echo '</pre>';
$search = @$_GET['search'];
$letter = @$_GET['letter'];
?>

    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style type="text/css">

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-weight: bold;
            }

            td {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                text-decoration: none;
                color: #000000;
            }

            .list {
                border-width: 1px;
                border-style: solid;
                border-color: #E5E5E5;
            }

            a {
                color: #0000FF;
                font-weight: bold;
                text-decoration: none;
            }

            a:link, a:visited {
                color: #5588FF;
                font-weight: bold;
            }

            a:hover {
                color: #0000FF;
                font-weight: bold;
                text-decoration: underline;
            }
        </style>
        <title><?php 
echo \_('Daily video views statistics');
?></title>
    </head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" valign="middle" width="100%" bgcolor="#88BBFF">
            <font size="5px" color="White"><b>&nbsp;<?php 
echo \_('Daily video views statistics');
?>&nbsp;</b></font>
        </td>
    </tr>
    <tr>
        <td width="100%" align="left" valign="bottom">
            <a href="stat_video.php"><< <?php 
echo \_('Back');
?></a>
        </td>
    </tr>
    <tr>
        <td align="center">
            <font color="Red">
                <strong>
                    <?php 
echo $error;
?>
                </strong>
            </font>
            <br>
            <br>
        </td>
    </tr>
    <tr>
    <td>
<?php 
$page = @$_REQUEST['page'] + 0;
$MAX_PAGE_ITEMS = 30;
$total_items = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->count()->from('daily_played_video')->get()->counter();
$page_offset = $page * $MAX_PAGE_ITEMS;
$total_pages = (int) ($total_items / $MAX_PAGE_ITEMS + 0.999999);
$query = "select * from daily_played_video order by date desc LIMIT {$page_offset}, {$MAX_PAGE_ITEMS}";
$played_video = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->query($query);
?>
    <table border="0" align="center" width="620">
        <tr>
            <td>
            </td>
        </tr>
    </table>
<?php 
echo "<center><table class='list' cellpadding='3' cellspacing='0'>\n";
echo '<tr>';
echo "<td class='list'><b>id</b></td>\n";
echo "<td class='list'><b>" . \_('Day') . "</b></td>\n";
echo "<td class='list'><b>" . \_('Views') . "</b></td>\n";
echo "</tr>\n";
while ($arr = $played_video->next()) {
    echo '<tr>';
    echo "<td class='list'>" . $arr['id'] . "</td>\n";
    echo "<td class='list'>" . $arr['date'] . "</td>\n";
    echo "<td class='list'>" . $arr['count'] . "</td>\n";
    echo "</tr>\n";
}
echo "</table>\n";
echo "<table width='700' align='center' border=0>\n";
echo "<tr>\n";
echo "<td width='100%' align='center'>\n";
echo \Ministra\OldAdmin\page_bar($MAX_PAGE_ITEMS, $page, $total_pages);
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
