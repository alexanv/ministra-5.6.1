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
if (@$_GET['del']) {
    \Ministra\Lib\Admin::checkAccess(\Ministra\Lib\AdminAccess::ACCESS_DELETE);
    $moderator = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->from('moderators')->where(['id' => (int) @$_GET['id']])->get()->first();
    if (!empty($moderator)) {
        \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->use_caching(['moderators.mac=' . $moderator['mac']])->delete('moderators', ['id' => (int) @$_GET['id']]);
    }
    \header('Location: add_moderator_mac.php');
    exit;
}
if (isset($_GET['status']) && @$_GET['id']) {
    \Ministra\Lib\Admin::checkAccess(\Ministra\Lib\AdminAccess::ACCESS_CONTEXT_ACTION);
    $moderator = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->from('moderators')->where(['id' => (int) @$_GET['id']])->get()->first();
    if (!empty($moderator)) {
        \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->use_caching(['moderators.mac=' . $moderator['mac']])->update('moderators', ['status' => (int) @$_GET['status']], ['id' => (int) @$_GET['id']]);
    }
    \header('Location: add_moderator_mac.php');
    exit;
}
if (!$error) {
    if (@$_GET['save']) {
        if (@$_POST['mac']) {
            \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->use_caching()->insert('moderators', ['name' => @$_POST['name'], 'mac' => @$_POST['mac'], 'disable_vclub_ad' => @$_POST['disable_vclub_ad']]);
            \header('Location: add_moderator_mac.php');
        } else {
            $error = \_('Error: all fields are required');
        }
    }
    if (@$_GET['update']) {
        if (@$_POST['mac']) {
            \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->use_caching(['moderators.mac=' . $_POST['mac']])->update('moderators', ['name' => $_POST['name'], 'mac' => $_POST['mac'], 'disable_vclub_ad' => @$_POST['disable_vclub_ad']], ['id' => (int) @$_GET['id']]);
            \header('Location: add_moderator_mac.php');
        } else {
            $error = \_('Error: all fields are required');
        }
    }
}
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
echo \_('Moderators MAC addresses');
?>
    </title>
</head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" valign="middle" width="100%" bgcolor="#88BBFF">
            <font size="5px" color="White"><b>&nbsp;<?php 
echo \_('Moderators MAC addresses');
?>&nbsp;</b></font>
        </td>
    </tr>
    <tr>
        <td width="100%" align="left" valign="bottom">
            <a href="add_video.php"><< <?php 
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
$moderators = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->from('moderators')->get()->all();
echo "<center><table class='list' cellpadding='3' cellspacing='0'>";
echo '<tr>';
echo "<td class='list'><b>#</b></td>";
echo "<td class='list'><b>" . \_('Name') . '</b></td>';
echo "<td class='list'><b>MAC</b></td>";
echo "<td class='list'>&nbsp;</td>";
echo '</tr>';
$i = 1;
foreach ($moderators as $arr) {
    echo '<tr>';
    echo "<td class='list'>" . $i . '</td>';
    echo "<td class='list'>" . $arr['name'] . '</td>';
    echo "<td class='list'>" . $arr['mac'] . '</td>';
    echo "<td class='list'>";
    if ($arr['status']) {
        echo "<a href='?status=0&id=" . $arr['id'] . "'><font color='Green'>on</font></a>&nbsp;&nbsp;";
    } else {
        echo "<a href='?status=1&id=" . $arr['id'] . "'><font color='Red'>off</font></a>&nbsp;&nbsp;";
    }
    echo "<a href='?edit=1&id=" . $arr['id'] . "#form'>edit</a>&nbsp;&nbsp;";
    echo "<a href='?del=1&id=" . $arr['id'] . "'>del</a></td>";
    echo '</tr>';
    ++$i;
}
echo '</table></center>';
if (@$_GET['edit']) {
    $arr = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->from('moderators')->where(['id' => (int) @$_GET['id']])->get()->first();
    if (!empty($arr)) {
        $mac = $arr['mac'];
        $name = $arr['name'];
        $disable_vclub_ad = $arr['disable_vclub_ad'];
    }
}
?>
            <script>
            function save() {
              form_ = document.getElementById('form_');

              //name = document.getElementById('name').value
              //cmd = document.getElementById('cmd').value
              id = document.getElementById('id').value;

              //action = 'add_web.php?name='+name+'&cmd='+cmd+'&id='+id
              action = 'add_moderator_mac.php?id=' + id;

              if (document.getElementById('action').value == 'edit') {
                action += '&update=1';
              } else {
                action += '&save=1';
              }

              form_.setAttribute('action', action);
              form_.setAttribute('method', 'POST');
              form_.submit();
            }
            </script>
            <br>
            <table align="center" class='list'>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        <form id="form_" method="POST">
                            <table align="center">
                                <tr>
                                    <td align="right">
                                        <?php 
echo \_('Name');
?>:
                                    </td>
                                    <td>
                                        <input type="text" name="name" id="name" value="<?php 
echo @$name;
?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        MAC:
                                    </td>
                                    <td>
                                        <input type="text" name="mac" id="mac" value="<?php 
echo @$mac;
?>">
                                        <input type="hidden" id="id" value="<?php 
echo @$_GET['id'];
?>">
                                        <input type="hidden" id="action" value="<?php 
if (@$_GET['edit']) {
    echo 'edit';
}
?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <?php 
echo \_('Disable Video club ad');
?>:
                                    </td>
                                    <td>
                                        <input type="checkbox" name="disable_vclub_ad"
                                               value="1" <?php 
echo empty($disable_vclub_ad) ? '' : 'checked';
?>/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <input type="button"
                                               value="<?php 
echo \htmlspecialchars(\_('Save'), \ENT_QUOTES);
?>"
                                               onclick="save()">&nbsp;<input type="button"
                                                                             value="<?php 
echo \htmlspecialchars(\_('New'), \ENT_QUOTES);
?>"
                                                                             onclick="document.location='add_moderator_mac.php'">
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <a name="form"></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

