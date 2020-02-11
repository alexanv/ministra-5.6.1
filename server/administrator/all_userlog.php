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
$search = @$_GET['search'];
$letter = @$_GET['letter'];
$date = @$_GET['date'];
$id = @$_GET['id'];
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
    <title>
        <?php 
echo \_('All users log');
?>
    </title>
</head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="620">
    <tr>
        <td align="center" valign="middle" width="100%" bgcolor="#88BBFF">
            <font size="5px" color="White"><b>&nbsp;<?php 
echo \_('All users log');
?>&nbsp;</b></font>
        </td>
    </tr>
    <tr>
        <td width="100%" align="left" valign="bottom">
            <a href="users.php"><< <?php 
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
</table>

<?php 
$where = '';
if (!\Ministra\OldAdmin\isset_date()) {
    $date = \date('Y-m-d');
} else {
    $date = $_GET['yy'] . '-' . $_GET['mm'] . '-' . $_GET['dd'];
}
$time_from = $date . ' 00:00:00';
$time_to = $date . ' 24:00:00';
$where .= ' order by time desc';
$page = @$_REQUEST['page'] + 0;
$MAX_PAGE_ITEMS = 30;
$total_items = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->query("select * from user_log {$where}")->count();
$page_offset = $page * $MAX_PAGE_ITEMS;
$total_pages = (int) ($total_items / $MAX_PAGE_ITEMS + 0.999999);
$query = "select * from user_log {$where} LIMIT {$page_offset}, {$MAX_PAGE_ITEMS}";
$user_log = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->query($query)->all();
?>
<script>
function load_log() {
  yy = document.getElementById('yy').options[document.getElementById('yy').selectedIndex].value;
  mm = document.getElementById('mm').options[document.getElementById('mm').selectedIndex].value;
  dd = document.getElementById('dd').options[document.getElementById('dd').selectedIndex].value;
  if (dd < 10) {
    dd = '0' + dd;
  }
  if (mm < 10) {
    mm = '0' + mm;
  }
  action = 'userlog.php?yy=' + yy + '&mm=' + mm + '&dd=' + dd + '&id='<?php 
if (@$id) {
    echo $id;
}
?>;
  document.location = action;
}
</script>
<table border="0" align="center" width="620">
    <tr>
        <td align="center">
        <td>
    </tr>
</table>
<?php 
echo "<center><table class='list' cellpadding='3' cellspacing='0' width='620'>\n";
echo '<tr>';
echo "<td class='list'><b>" . \_('Time') . "</b></td>\n";
echo "<td class='list'><b>MAC</b></td>\n";
echo "<td class='list'><b>" . \_('Stb action') . "</b></td>\n";
echo "<td class='list'><b>" . \_('Parameter') . "</b></td>\n";
echo "</tr>\n";
foreach ($user_log as $arr) {
    echo '<tr>';
    echo "<td class='list' nowrap>" . $arr['time'] . "</td>\n";
    echo "<td class='list' nowrap>" . $arr['mac'] . "</td>\n";
    echo "<td class='list'>" . $arr['action'] . "</td>\n";
    echo "<td class='list'>" . \Ministra\OldAdmin\parse_param_video_log($arr['action'], $arr['param']) . "</td>\n";
    echo "</tr>\n";
}
echo "</table>\n";
echo "<table width='600' align='center' border=0>\n";
echo "<tr>\n";
echo "<td width='100%' align='center'>\n";
echo \Ministra\OldAdmin\page_bar($MAX_PAGE_ITEMS, $page, $total_pages);
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
