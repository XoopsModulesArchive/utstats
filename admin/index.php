<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
include 'admin_header.php';

include '../cache/config.php';
include '../include/functions.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

$myts = MyTextSanitizer::getInstance();
$eh = new ErrorHandler();
$mytree = new XoopsTree($xoopsDB->prefix('myalbum_cat'), 'cid', 'pid');

function mylinks()
{
    global $xoopsDB;

    xoops_cp_header();

    OpenTable();

    $result3 = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('myalbum_photos') . ' where status=0');

    [$totalnewlinks] = $xoopsDB->fetchRow($result3);

    if ($totalnewlinks > 0) {
        $totalnewlinks = "<font color=\"#ff0000\"><b>$totalnewlinks</b></font>";
    }

    echo ' - <a href=index.php?op=myLinksConfigAdmin>' . _ALBM_GENERALSET . '</a>';

    echo '<br><br>';

    echo ' - <a href=index.php?op=linksConfigMenu>' . _ALBM_ADDMODDELETE . '</a>';

    echo '<br><br>';

    echo ' - <a href=index.php?op=listNewLinks>' . _ALBM_LINKSWAITING . " ($totalnewlinks)</a>";

    echo '<br><br>';

    echo ' - <a href=batch.php>' . _ALBM_BATCHUPLOAD . '</a>';

    echo '<br><br>';

    echo ' - <a href=redothumbs.php>' . _ALBM_REDOTHUMBS2 . '</a>';

    echo '<br><br>';

    echo ' - <a href=upgrade2xoops2.php>' . _ALBM_IMPORTCOMMENTS . '</a>';

    $result = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('myalbum_photos') . ' where status>0');

    [$numrows] = $xoopsDB->fetchRow($result);

    echo '<br><br><div align="center">';

    printf(_ALBM_THEREAREADMIN, $numrows);

    echo '</div>';

    CloseTable();

    xoops_cp_footer();
}

function listNewLinks()
{
    global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree;

    $mytree = new XoopsTree($xoopsDB->prefix('myalbum_cat'), 'cid', 'pid');

    $result = $xoopsDB->query('select lid, cid, title, submitter from ' . $xoopsDB->prefix('myalbum_photos') . ' where status=0 order by date DESC');

    $numrows = $xoopsDB->getRowsNum($result);

    xoops_cp_header();

    echo '<h4>' . _ALBM_LINKSWAITING . "&nbsp;($numrows)</h4><br>";

    if ($numrows > 0) {
        while (list($lid, $cid, $title, $submitterid) = $xoopsDB->fetchRow($result)) {
            OpenTable();

            $result2 = $xoopsDB->query('select description from ' . $xoopsDB->prefix('myalbum_text') . " where lid=$lid");

            [$description] = $xoopsDB->fetchRow($result2);

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

            $submitter = XoopsUser::getUnameFromId($submitterid);

            $cat = $mytree->getNicePathFromId($cid, 'title', '../viewcat.php?');

            print "<b>Title:</b> <a href='photo.php?lid=$lid'>$title</a><br>
			<b>Description:</b> $description<br>
			<b>Category:</b> $cat<br>
			<b>Submitter:</b> $submitter";

            CloseTable();

            //		echo myTextForm("index.php?op=delNewLink&lid=$lid",_ALBM_DELETE);
        }
    } else {
        echo '' . _ALBM_NOSUBMITTED . '';
    }

    xoops_cp_footer();
}

function linksConfigMenu()
{
    global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree;

    // Add a New Main Category

    xoops_cp_header();

    OpenTable();

    echo "<form method=post action=index.php>\n";

    echo '<h4>' . _ALBM_ADDMAIN . '</h4><br>' . _ALBM_TITLEC . '<input type=text name=title size=30 maxlength=50><br>';

    echo '' . _ALBM_IMGURL . '<br><input type="text" name="imgurl" size="100" maxlength="150" value="http://"><br><br>';

    echo "<input type=hidden name=cid value=0>\n";

    echo '<input type=hidden name=op value=addCat>';

    echo '<input type=submit value=' . _ALBM_ADD . '><br></form>';

    CloseTable();

    echo '<br>';

    // Add a New Sub-Category

    $result = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('myalbum_cat') . '');

    [$numrows] = $xoopsDB->fetchRow($result);

    if ($numrows > 0) {
        OpenTable();

        echo '<form method=post action=index.php>';

        echo '<h4>' . _ALBM_ADDSUB . '</h4><br>' . _ALBM_TITLEC . '<input type=text name=title size=30 maxlength=50>&nbsp;' . _ALBM_IN . '&nbsp;';

        $mytree->makeMySelBox('title', 'title');

        #		echo "<br>"._ALBM_IMGURL."<br><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\">\n";

        echo '<input type=hidden name=op value=addCat><br><br>';

        echo '<input type=submit value=' . _ALBM_ADD . '><br></form>';

        CloseTable();

        echo '<br>';

        // Modify Category

        OpenTable();

        echo '
    		</center><form method=post action=index.php>
    		<h4>' . _ALBM_MODCAT . '</h4><br>';

        echo _ALBM_CATEGORYC;

        $mytree->makeMySelBox('title', 'title');

        echo "<br><br>\n";

        echo "<input type=hidden name=op value=modCat>\n";

        echo '<input type=submit value=' . _ALBM_MODIFY . ">\n";

        echo '</form>';

        CloseTable();

        echo '<br>';
    }

    // Modify Link

    $result2 = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('myalbum_photos') . '');

    [$numrows2] = $xoopsDB->fetchRow($result2);

    if ($numrows2 > 0) {
        OpenTable();

        echo "<form method=get action=\"photo.php\">\n";

        echo '<h4>' . _ALBM_MODLINK . "</h4><br>\n";

        echo _ALBM_LINKID . "<input type=text name=lid size=12 maxlength=11>\n";

        echo "<input type=hidden name=fct value=mylinks>\n";

        echo "<input type=hidden name=op value=modLink><br><br>\n";

        echo '<input type=submit value=' . _ALBM_MODIFY . "></form>\n";

        CloseTable();
    }

    xoops_cp_footer();
}

function modLink()
{
    global $xoopsDB, $_GET, $myts, $eh, $mytree, $xoopsConfig;

    $lid = $_GET['lid'];

    xoops_cp_header();

    OpenTable();

    $result = $xoopsDB->query('select cid, title, url, email, logourl from ' . $xoopsDB->prefix('mylinks_links') . " where lid=$lid") or $eh::show('0013');

    echo '<h4>' . _ALBM_MODLINK . '</h4><br>';

    [$cid, $title, $url, $email, $logourl] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $url = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5);

    //   	$url = urldecode($url);

    $email = htmlspecialchars($email, ENT_QUOTES | ENT_HTML5);

    $logourl = htmlspecialchars($logourl, ENT_QUOTES | ENT_HTML5);

    //  	$logourl = urldecode($logourl);

    $result2 = $xoopsDB->query('select description from ' . $xoopsDB->prefix('mylinks_text') . " where lid=$lid");

    [$description] = $xoopsDB->fetchRow($result2);

    $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

    echo '<table>';

    echo '<form method=post action=index.php>';

    echo '<tr><td>' . _ALBM_LINKID . "</td><td><b>$lid</b></td></tr>";

    echo '<tr><td>' . _ALBM_SITETITLE . "</td><td><input type=text name=title value=\"$title\" size=50 maxlength=100></input></td></tr>\n";

    echo '<tr><td>' . _ALBM_SITEURL . "</td><td><input type=text name=url value=\"$url\" size=50 maxlength=100></input></td></tr>\n";

    echo '<tr><td>' . _ALBM_EMAILC . "</td><td><input type=text name=email value=\"$email\" size=50 maxlength=60></input></td></tr>\n";

    echo '<tr><td valign="top">' . _ALBM_DESCRIPTIONC . "</td><td><textarea name=description cols=60 rows=5>$description</textarea></td></tr>";

    echo '<tr><td>' . _ALBM_CATEGORYC . '</td><td>';

    $mytree->makeMySelBox('title', 'title', $cid);

    echo "</td></tr>\n";

    echo '<tr><td>' . _ALBM_SHOTIMAGE . "</td><td><input type=text name=logourl value=\"$logourl\" size=\"50\" maxlength=\"60\"></input></td></tr>\n";

    $shotdir = '<b>' . XOOPS_URL . '/modules/mylinks/images/shots/</b>';

    echo '<tr><td></td><td>';

    printf(_ALBM_SHOTMUST, $shotdir);

    echo "</td></tr>\n";

    echo '</table>';

    echo "<br><BR><input type=hidden name=lid value=$lid></input>\n";

    echo '<input type=hidden name=op value=modLinkS><input type=submit value=' . _ALBM_MODIFY . '>';

    // echo "&nbsp;<input type=button value="._ALBM_DELETE." onclick=\"javascript:location='index.php?op=delLink&lid=".$lid."'\">";

    //echo "&nbsp;<input type=button value="._ALBM_CANCEL." onclick=\"javascript:history.go(-1)\">";

    echo "</form>\n";

    echo "<table><tr><td>\n";

    echo myTextForm('index.php?op=delLink&lid=' . $lid, _ALBM_DELETE);

    echo "</td><td>\n";

    echo myTextForm('index.php?op=linksConfigMenu', _ALBM_CANCEL);

    echo "</td></tr></table>\n";

    echo '<hr>';

    $result5 = $xoopsDB->query('SELECT count(*) FROM ' . $xoopsDB->prefix('mylinks_votedata') . " WHERE lid = $lid");

    [$totalvotes] = $xoopsDB->fetchRow($result5);

    echo "<table valign=top width=100%>\n";

    echo '<tr><td colspan=7><b>';

    printf(_ALBM_TOTALVOTES, $totalvotes);

    echo "</b><br><br></td></tr>\n";

    // Show Registered Users Votes

    $result5 = $xoopsDB->query('SELECT ratingid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ' . $xoopsDB->prefix('mylinks_votedata') . " WHERE lid = $lid AND ratinguser >0 ORDER BY ratingtimestamp DESC");

    $votes = $xoopsDB->getRowsNum($result5);

    echo '<tr><td colspan=7><br><br><b>';

    printf(_ALBM_USERTOTALVOTES, $votes);

    echo "</b><br><br></td></tr>\n";

    echo '<tr><td><b>' . _ALBM_USER . '  </b></td><td><b>' . _ALBM_IP . '  </b></td><td><b>' . _ALBM_RATING . '  </b></td><td><b>' . _ALBM_USERAVG . '  </b></td><td><b>' . _ALBM_TOTALRATE . '  </b></td><td><b>' . _ALBM_DATE . '  </b></td><td align="center"><b>' . _ALBM_DELETE . "</b></td></tr>\n";

    if (0 == $votes) {
        echo '<tr><td align="center" colspan="7">' . _ALBM_NOREGVOTES . "<br></td></tr>\n";
    }

    $x = 0;

    $colorswitch = 'dddddd';

    while (list($ratingid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp) = $xoopsDB->fetchRow($result5)) {
        //	$ratingtimestamp = formatTimestamp($ratingtimestamp);

        //Individual user information

        $result2 = $xoopsDB->query('SELECT rating FROM ' . $xoopsDB->prefix('mylinks_votedata') . " WHERE ratinguser = '$ratinguser'");

        $uservotes = $xoopsDB->getRowsNum($result2);

        $useravgrating = 0;

        while (list($rating2) = $xoopsDB->fetchRow($result2)) {
            $useravgrating += $rating2;
        }

        $useravgrating /= $uservotes;

        $useravgrating = number_format($useravgrating, 1);

        $ratingusername = XoopsUser::getUnameFromId($ratinguser);

        echo '<tr><td bgcolor="'
             . $colorswitch
             . '">'
             . $ratingusername
             . "</td><td bgcolor=\"$colorswitch\">"
             . $ratinghostname
             . "</td><td bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">"
             . $useravgrating
             . "</td><td bgcolor=\"$colorswitch\">"
             . $uservotes
             . "</td><td bgcolor=\"$colorswitch\">"
             . $ratingtimestamp
             . "</td><td bgcolor=\"$colorswitch\" align=\"center\"><b><a href=index.php?op=delVote&lid=$lid&rid=$ratingid>X</a></b></td></tr>\n";

        $x++;

        if ('dddddd' == $colorswitch) {
            $colorswitch = 'ffffff';
        } else {
            $colorswitch = 'dddddd';
        }
    }

    // Show Unregistered Users Votes

    $result5 = $xoopsDB->query('SELECT ratingid, rating, ratinghostname, ratingtimestamp FROM ' . $xoopsDB->prefix('mylinks_votedata') . " WHERE lid = $lid AND ratinguser = 0 ORDER BY ratingtimestamp DESC");

    $votes = $xoopsDB->getRowsNum($result5);

    echo '<tr><td colspan=7><b><br><br>';

    printf(_ALBM_ANONTOTALVOTES, $votes);

    echo "</b><br><br></td></tr>\n";

    echo '<tr><td colspan=2><b>' . _ALBM_IP . '  </b></td><td colspan=3><b>' . _ALBM_RATING . '  </b></td><td><b>' . _ALBM_DATE . '  </b></b></td><td align="center"><b>' . _ALBM_DELETE . '</b></td><br></tr>';

    if (0 == $votes) {
        echo '<tr><td colspan="7" align="center">' . _ALBM_NOUNREGVOTES . '<br></td></tr>';
    }

    $x = 0;

    $colorswitch = 'dddddd';

    while (list($ratingid, $rating, $ratinghostname, $ratingtimestamp) = $xoopsDB->fetchRow($result5)) {
        $formatted_date = formatTimestamp($ratingtimestamp);

        echo "<td colspan=\"2\" bgcolor=\"$colorswitch\">$ratinghostname</td><td colspan=\"3\" bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" aling=\"center\"><b><a href=index.php?op=delVote&lid=$lid&rid=$ratingid>X</a></b></td></tr>";

        $x++;

        if ('dddddd' == $colorswitch) {
            $colorswitch = 'ffffff';
        } else {
            $colorswitch = 'dddddd';
        }
    }

    echo "<tr><td colspan=\"6\">&nbsp;<br></td></tr>\n";

    echo "</table>\n";

    CloseTable();

    xoops_cp_footer();
}

function delVote()
{
    global $xoopsDB, $_GET, $eh;

    $rid = $_GET['rid'];

    $lid = $_GET['lid'];

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_votedata') . " where ratingid=$rid";

    $xoopsDB->query($query) or $eh::show('0013');

    updaterating($lid);

    redirect_header('index.php', 1, _ALBM_VOTEDELETED);

    exit();
}

function listBrokenLinks()
{
    global $xoopsDB, $eh;

    $result = $xoopsDB->query('select * from ' . $xoopsDB->prefix('mylinks_broken') . ' group by lid order by reportid DESC');

    $totalbrokenlinks = $xoopsDB->getRowsNum($result);

    xoops_cp_header();

    OpenTable();

    echo '<h4>' . _ALBM_BROKENREPORTS . " ($totalbrokenlinks)</h4><br>";

    if (0 == $totalbrokenlinks) {
        echo _ALBM_NOBROKEN;
    } else {
        echo '<center>
    ' . _ALBM_IGNOREDESC . '<br>
    ' . _ALBM_DELETEDESC . '</center><br><br><br>';

        $colorswitch = 'dddddd';

        echo '<table align="center" width="90%">';

        echo '
        	<tr>
          	<td><b>Link Name</b></td>
          	<td><b>' . _ALBM_REPORTER . '</b></td>
          	<td><b>' . _ALBM_LINKSUBMITTER . '</b></td>
          	<td><b>' . _ALBM_IGNORE . '</b></td>
          	<td><b>' . _ALBM_DELETE . '</b></td>
        	</tr>';

        while (list($reportid, $lid, $sender, $ip) = $xoopsDB->fetchRow($result)) {
            $result2 = $xoopsDB->query('select title, url, submitter from ' . $xoopsDB->prefix('mylinks_links') . " where lid=$lid");

            if (0 != $sender) {
                $result3 = $xoopsDB->query('select uname, email from ' . $xoopsDB->prefix('users') . " where uid=$sender");

                [$uname, $email] = $xoopsDB->fetchRow($result3);
            }

            [$title, $url, $ownerid] = $xoopsDB->fetchRow($result2);

            //			$url=urldecode($url);

            $result4 = $xoopsDB->query('select uname, email from ' . $xoopsDB->prefix('users') . " where uid='$ownerid'");

            [$owner, $owneremail] = $xoopsDB->fetchRow($result4);

            echo "<tr><td bgcolor=$colorswitch><a href=$url>$title</a></td>";

            if ('' == $email) {
                echo '<td bgcolor="' . $colorswitch . '">' . $sender . ' (' . $ip . ')';
            } else {
                echo '<td bgcolor="' . $colorswitch . '"><a href="mailto:' . $email . '">' . $uname . '</a> (' . $ip . ')';
            }

            echo '</td>';

            if ('' == $owneremail) {
                echo '<td bgcolor="' . $colorswitch . '">' . $owner . '';
            } else {
                echo '<td bgcolor="' . $colorswitch . '"><a href="mailto:' . $owneremail . '">' . $owner . '</a>';
            }

            echo "</td><td bgcolor='$colorswitch' align='center'>\n";

            echo myTextForm("index.php?op=ignoreBrokenLinks&lid=$lid", 'X');

            echo '</td>';

            echo "<td align='center' bgcolor='$colorswitch'>\n";

            echo myTextForm("index.php?op=delBrokenLinks&lid=$lid", 'X');

            echo "</td></tr>\n";

            if ('#dddddd' == $colorswitch) {
                $colorswitch = '#ffffff';
            } else {
                $colorswitch = '#dddddd';
            }
        }

        echo '</table>';
    }

    CloseTable();

    xoops_cp_footer();
}

function delBrokenLinks()
{
    global $xoopsDB, $_GET, $eh;

    $lid = $_GET['lid'];

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_broken') . " where lid=$lid";

    $xoopsDB->query($query) or $eh::show('0013');

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_links') . " where lid=$lid";

    $xoopsDB->query($query) or $eh::show('0013');

    redirect_header('index.php', 1, _ALBM_LINKDELETED);

    exit();
}

function ignoreBrokenLinks()
{
    global $xoopsDB, $_GET, $eh;

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_broken') . ' where lid=' . $_GET['lid'] . '';

    $xoopsDB->query($query) or $eh::show('0013');

    redirect_header('index.php', 1, _ALBM_BROKENDELETED);

    exit();
}

function listModReq()
{
    global $xoopsDB, $myts, $eh, $mytree, $mylinks_shotwidth, $mylinks_useshots;

    $result = $xoopsDB->query('select * from ' . $xoopsDB->prefix('mylinks_mod') . ' order by requestid');

    $totalmodrequests = $xoopsDB->getRowsNum($result);

    xoops_cp_header();

    OpenTable();

    echo '<h4>' . _ALBM_USERMODREQ . " ($totalmodrequests)</h4><br>";

    if ($totalmodrequests > 0) {
        echo '<table width=95%><tr><td>';

        while (list($requestid, $lid, $cid, $title, $url, $email, $logourl, $description, $submitterid) = $xoopsDB->fetchRow($result)) {
            $result2 = $xoopsDB->query('select cid, title, url, email, logourl, submitter from ' . $xoopsDB->prefix('mylinks_links') . " where lid=$lid");

            [$origcid, $origtitle, $origurl, $origemail, $origlogourl, $ownerid] = $xoopsDB->fetchRow($result2);

            $result2 = $xoopsDB->query('select description from ' . $xoopsDB->prefix('mylinks_text') . " where lid=$lid");

            [$origdescription] = $xoopsDB->fetchRow($result2);

            $result7 = $xoopsDB->query('select uname, email from ' . $xoopsDB->prefix('users') . " where uid='$submitterid'");

            $result8 = $xoopsDB->query('select uname, email from ' . $xoopsDB->prefix('users') . " where uid='$ownerid'");

            $cidtitle = $mytree->getPathFromId($cid, 'title');

            $origcidtitle = $mytree->getPathFromId($origcid, 'title');

            [$submitter, $submitteremail] = $xoopsDB->fetchRow($result7);

            [$owner, $owneremail] = $xoopsDB->fetchRow($result8);

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $url = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5);

            //			$url = urldecode($url);

            $email = htmlspecialchars($email, ENT_QUOTES | ENT_HTML5);

            // use original image file to prevent users from changing screen shots file

            $origlogourl = htmlspecialchars($origlogourl, ENT_QUOTES | ENT_HTML5);

            $logourl = $origlogourl;

            //			$logourl = urldecode($logourl);

            $description = $myts->displayTarea($description);

            $origurl = htmlspecialchars($origurl, ENT_QUOTES | ENT_HTML5);

            //			$origurl = urldecode($origurl);

            $origemail = htmlspecialchars($origemail, ENT_QUOTES | ENT_HTML5);

            //			$origlogourl = urldecode($origlogourl);

            $origdescription = $myts->displayTarea($origdescription);

            if ('' == $owner) {
                $owner = 'administration';
            }

            echo '<table border=1 bordercolor=black cellpadding=5 cellspacing=0 align=center width=450><tr><td>
    	   		<table width=100% bgcolor=dddddd>
    	     		<tr>
    	       		<td valign=top width=45%><b>' . _ALBM_ORIGINAL . '</b></td>
	       		<td rowspan=14 valign=top align=left><small><br>' . _ALBM_DESCRIPTIONC . "<br>$origdescription</small></td>
    	     		</tr>
    	     		<tr><td valign=top width=45%><small>" . _ALBM_SITETITLE . "$origtitle</small></td></tr>
    	     		<tr><td valign=top width=45%><small>" . _ALBM_SITEURL . '' . $origurl . '</small></td></tr>
	     		<tr><td valign=top width=45%><small>' . _ALBM_CATEGORYC . "$origcidtitle</small></td></tr>
	     		<tr><td valign=top width=45%><small>" . _ALBM_EMAILC . "$origemail</small></td></tr>
	     		<tr><td valign=top width=45%><small>" . _ALBM_SHOTIMAGE . '</small>';

            if ($mylinks_useshots && !empty($origlogourl)) {
                echo '<img src="' . XOOPS_URL . '/modules/mylinks/images/shots/' . $origlogourl . '" width="' . $mylinks_shotwidth . '">';
            } else {
                echo '&nbsp;';
            }

            echo '</td></tr>
    	   		</table></td></tr><tr><td>
    	   		<table width=100%>
    	     		<tr>
    	       		<td valign=top width=45%><b>' . _ALBM_PROPOSED . '</b></td>
    	       		<td rowspan=14 valign=top align=left><small><br>' . _ALBM_DESCRIPTIONC . "<br>$description</small></td>
    	     		</tr>
    	     		<tr><td valign=top width=45%><small>" . _ALBM_SITETITLE . "$title</small></td></tr>
    	     		<tr><td valign=top width=45%><small>" . _ALBM_SITEURL . '' . $url . '</small></td></tr>
	     		<tr><td valign=top width=45%><small>' . _ALBM_CATEGORYC . "$cidtitle</small></td></tr>
			<tr><td valign=top width=45%><small>" . _ALBM_EMAILC . "$email</small></td></tr>
	     		<tr><td valign=top width=45%><small>" . _ALBM_SHOTIMAGE . '</small>';

            if ($mylinks_useshots && !empty($logourl)) {
                echo '<img src="' . XOOPS_URL . '/modules/mylinks/images/shots/' . $logourl . '" width="' . $mylinks_shotwidth . '" alt="/">';
            } else {
                echo '&nbsp;';
            }

            echo '</td></tr>
    	   		</table></td></tr></table>
    			<table align=center width=450>
    	  		<tr>';

            if ('' == $submitteremail) {
                echo '<td align=left><small>' . _ALBM_SUBMITTER . "$submitter</small></td>";
            } else {
                echo '<td align=left><small>' . _ALBM_SUBMITTER . '<a href=mailto:' . $submitteremail . '>' . $submitter . '</a></small></td>';
            }

            if ('' == $owneremail) {
                echo '<td align=center><small>' . _ALBM_OWNER . '' . $owner . '</small></td>';
            } else {
                echo '<td align=center><small>' . _ALBM_OWNER . '<a href=mailto:' . $owneremail . '>' . $owner . '</a></small></td>';
            }

            echo "<td align=right><small>\n";

            echo "<table><tr><td>\n";

            echo myTextForm("index.php?op=changeModReq&requestid=$requestid", _ALBM_APPROVE);

            echo "</td><td>\n";

            echo myTextForm("index.php?op=ignoreModReq&requestid=$requestid", _ALBM_IGNORE);

            echo "</td></tr></table>\n";

            echo "</small></td></tr>\n";

            echo '</table><br><br>';
        }

        echo '</td></tr></table>';
    } else {
        echo _ALBM_NOMODREQ;
    }

    CloseTable();

    xoops_cp_footer();
}

function changeModReq()
{
    global $xoopsDB, $_GET, $eh, $myts;

    $requestid = $_GET['requestid'];

    $query = 'select lid, cid, title, url, email, logourl, description from ' . $xoopsDB->prefix('mylinks_mod') . ' where requestid=' . $requestid . '';

    $result = $xoopsDB->query($query);

    while (list($lid, $cid, $title, $url, $email, $logourl, $description) = $xoopsDB->fetchRow($result)) {
        if (get_magic_quotes_runtime()) {
            $title = stripslashes($title);

            $url = stripslashes($url);

            $email = stripslashes($email);

            $logourl = stripslashes($logourl);

            $description = stripslashes($description);
        }

        $title = addslashes($title);

        $url = addslashes($url);

        $email = addslashes($email);

        $logourl = addslashes($logourl);

        $description = addslashes($description);

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('mylinks_links') . " SET cid='$cid',title='$title',url='$url',email='$email',logourl='$logourl', status=2, date=" . time() . " WHERE lid='$lid'") or $eh::show('0013');

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('mylinks_text') . " SET description='$description' WHERE lid=" . $lid . '') or $eh::show('0013');

        $xoopsDB->query('delete from ' . $xoopsDB->prefix('mylinks_mod') . " where requestid='$requestid'") or $eh::show('0013');
    }

    redirect_header('index.php', 1, _ALBM_DBUPDATED);

    exit();
}

function ignoreModReq()
{
    global $xoopsDB, $_GET, $eh;

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_mod') . ' where requestid=' . $_GET['requestid'] . '';

    $xoopsDB->query($query) or $eh::show('0013');

    redirect_header('index.php', 1, _ALBM_MODREQDELETED);

    exit();
}

function modLinkS()
{
    global $xoopsDB, $_POST, $myts, $eh;

    $cid = $_POST['cid'];

    if (($_POST['url']) || ('' != $_POST['url'])) {
        //		$url = $myts->formatURL($_POST["url"]);

        //		$url = urlencode($url);

        $url = $myts->addSlashes($_POST['url']);
    }

    $logourl = $myts->addSlashes($_POST['logourl']);

    $title = $myts->addSlashes($_POST['title']);

    $email = $myts->addSlashes($_POST['email']);

    $description = $myts->addSlashes($_POST['description']);

    $xoopsDB->query('update ' . $xoopsDB->prefix('mylinks_links') . " set cid='$cid', title='$title', url='$url', email='$email', logourl='$logourl', status=2, date=" . time() . ' where lid=' . $_POST['lid'] . '') or $eh::show('0013');

    $xoopsDB->query('update ' . $xoopsDB->prefix('mylinks_text') . " set description='$description' where lid=" . $_POST['lid'] . '') or $eh::show('0013');

    redirect_header('index.php', 1, _ALBM_DBUPDATED);

    exit();
}

function delLink()
{
    global $xoopsDB, $_GET, $eh;

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_links') . ' where lid=' . $_GET['lid'] . '';

    $xoopsDB->query($query) or $eh::show('0013');

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_text') . ' where lid=' . $_GET['lid'] . '';

    $xoopsDB->query($query) or $eh::show('0013');

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_votedata') . ' where lid=' . $_GET['lid'] . '';

    $xoopsDB->query($query) or $eh::show('0013');

    redirect_header('index.php', 1, _ALBM_LINKDELETED);

    exit();
}

function modCat()
{
    global $xoopsDB, $_POST, $myts, $eh, $mytree;

    $cid = $_POST['cid'];

    xoops_cp_header();

    OpenTable();

    echo '<h4>' . _ALBM_MODCAT . '</h4><br>';

    $result = $xoopsDB->query('select pid, title, imgurl from ' . $xoopsDB->prefix('myalbum_cat') . " where cid=$cid");

    [$pid, $title, $imgurl] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $imgurl = htmlspecialchars($imgurl, ENT_QUOTES | ENT_HTML5);

    echo '<form action=index.php method=post>' . _ALBM_TITLEC . "<input type=text name=title value=\"$title\" size=51 maxlength=50><br><br>" . _ALBM_IMGURLMAIN . "<br><input type=text name=imgurl value=\"$imgurl\" size=100 maxlength=150><br><br>";

    echo _ALBM_PARENT . '&nbsp;';

    $mytree->makeMySelBox('title', 'title', $pid, 1, pid);

    //	<input type=hidden name=pid value=\"$pid\">

    echo '<br><input type="hidden" name="cid" value="' . $cid . '">
	<input type="hidden" name="op" value="modCatS"><br>
	<input type="submit" value="' . _ALBM_SAVE . '">
	<input type="button" value="' . _ALBM_DELETE . "\" onClick=\"location='index.php?pid=$pid&cid=$cid&op=delCat'\">";

    echo '&nbsp;<input type="button" value="' . _ALBM_CANCEL . '" onclick="javascript:history.go(-1)">';

    echo '</form>';

    CloseTable();

    xoops_cp_footer();
}

function modCatS()
{
    global $xoopsDB, $_POST, $myts, $eh;

    $cid = $_POST['cid'];

    $pid = $_POST['pid'];

    $title = $myts->addSlashes($_POST['title']);

    if (($_POST['imgurl']) || ('' != $_POST['imgurl'])) {
        $imgurl = $myts->addSlashes($_POST['imgurl']);
    }

    $xoopsDB->query('update ' . $xoopsDB->prefix('myalbum_cat') . " set pid=$pid, title='$title', imgurl='$imgurl' where cid=$cid") or $eh::show('0013');

    redirect_header('index.php', 1, _ALBM_DBUPDATED);
}

function delCat()
{
    global $xoopsDB, $_GET, $eh, $mytree;

    $cid = $_GET['cid'];

    if ($_GET['ok']) {
        $ok = $_GET['ok'];
    }

    if (1 == $ok) {
        //get all subcategories under the specified category

        $arr = $mytree->getAllChildId($cid);

        for ($i = 0, $iMax = count($arr); $i < $iMax; $i++) {
            //get all links in each subcategory

            $result = $xoopsDB->query('select lid,ext from ' . $xoopsDB->prefix('myalbum_photos') . ' where cid=' . $arr[$i] . '') or $eh::show('0013');

            //now for each link, delete the text data and vote ata associated with the link

            while (list($lid, $ext) = $xoopsDB->fetchRow($result)) {
                $delete = $lid;

                $q = 'DELETE FROM ' . $xoopsDB->prefix('myalbum_photos') . " WHERE lid = $delete";

                $xoopsDB->query($q) or $eh::show('0013');

                $q = 'DELETE FROM ' . $xoopsDB->prefix('myalbum_text') . " WHERE lid = $delete";

                $xoopsDB->query($q) or $eh::show('0013');

                $q = 'DELETE FROM ' . $xoopsDB->prefix('myalbum_votedata') . " WHERE lid = $delete";

                $xoopsDB->query($q) or $eh::show('0013');

                // delete comments for this photo

                $com = new XoopsComments($xoopsDB->prefix('myalbum_comments'));

                $criteria = ["item_id=$delete", 'pid=0'];

                $commentsarray = $com->getAllComments($criteria);

                foreach ($commentsarray as $comment) {
                    $comment->delete();
                }

                if (is_numeric($delete)) { // last security check
                    unlink(XOOPS_ROOT_PATH . "/modules/myalbum/photos/$delete.$ext");

                    unlink(XOOPS_ROOT_PATH . "/modules/myalbum/photos/thumbs/$delete.$ext");
                }
            }

            //all links for each subcategory is deleted, now delete the subcategory data

            $xoopsDB->query('delete from ' . $xoopsDB->prefix('myalbum_cat') . ' where cid=' . $arr[$i] . '') or $eh::show('0013');
        }

        //all subcategory and associated data are deleted, now delete category data and its associated data

        $result = $xoopsDB->query('select lid, ext from ' . $xoopsDB->prefix('myalbum_photos') . ' where cid=' . $cid . '') or $eh::show('0013');

        while (list($lid, $ext) = $xoopsDB->fetchRow($result)) {
            $delete = $lid;

            $q = 'DELETE FROM ' . $xoopsDB->prefix('myalbum_photos') . " WHERE lid = $delete";

            $xoopsDB->query($q) or $eh::show('0013');

            $q = 'DELETE FROM ' . $xoopsDB->prefix('myalbum_text') . " WHERE lid = $delete";

            $xoopsDB->query($q) or $eh::show('0013');

            $q = 'DELETE FROM ' . $xoopsDB->prefix('myalbum_votedata') . " WHERE lid = $delete";

            $xoopsDB->query($q) or $eh::show('0013');

            // delete comments for this photo

            $com = new XoopsComments($xoopsDB->prefix('myalbum_comments'));

            $criteria = ["item_id=$delete", 'pid=0'];

            $commentsarray = $com->getAllComments($criteria);

            foreach ($commentsarray as $comment) {
                $comment->delete();
            }

            if (is_numeric($delete)) { // last security check
                unlink(XOOPS_ROOT_PATH . "/modules/myalbum/photos/$delete.$ext");

                unlink(XOOPS_ROOT_PATH . "/modules/myalbum/photos/thumbs/$delete.$ext");
            }
        }

        $xoopsDB->query('delete from ' . $xoopsDB->prefix('myalbum_cat') . " where cid=$cid") or $eh::show('0013');

        redirect_header('index.php', 1, _ALBM_CATDELETED);

        exit();
    }  

    xoops_cp_header();

    OpenTable();

    echo '<center>';

    echo '<h4><font color="#ff0000">';

    echo _ALBM_WARNING . '</font></h4><br>';

    echo "<table><tr><td>\n";

    echo myTextForm("index.php?op=delCat&cid=$cid&ok=1", _ALBM_YES);

    echo "</td><td>\n";

    echo myTextForm('index.php', _ALBM_NO);

    echo "</td></tr></table>\n";

    CloseTable();

    xoops_cp_footer();
}

function delNewLink()
{
    global $xoopsDB, $_GET, $eh;

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_links') . ' where lid=' . $_GET['lid'] . '';

    $xoopsDB->query($query) or $eh::show('0013');

    $query = 'delete from ' . $xoopsDB->prefix('mylinks_text') . ' where lid=' . $_GET['lid'] . '';

    $xoopsDB->query($query) or $eh::show('0013');

    redirect_header('index.php', 1, _ALBM_LINKDELETED);
}

function addCat()
{
    global $xoopsDB, $_POST, $myts, $eh;

    $pid = $_POST['cid'];

    $title = $_POST['title'];

    if (($_POST['imgurl']) || ('' != $_POST['imgurl'])) {
        //		$imgurl = $myts->formatURL($_POST["imgurl"]);

        //		$imgurl = urlencode($imgurl);

        $imgurl = $myts->addSlashes($_POST['imgurl']);
    }

    $title = $myts->addSlashes($title);

    $newid = $xoopsDB->genId($xoopsDB->prefix('myalbum_cat') . '_cid_seq');

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('myalbum_cat') . " (cid, pid, title, imgurl) VALUES ($newid, $pid, '$title', '$imgurl')") or $eh::show('0013');

    redirect_header('index.php?op=linksConfigMenu', 1, _ALBM_NEWCATADDED);
}

function addLink()
{
    global $xoopsConfig, $xoopsDB, $myts, $xoopsUser, $eh, $_POST;

    if (($_POST['url']) || ('' != $_POST['url'])) {
        //	$url=$myts->formatURL($_POST["url"]);

        //		$url = urlencode($url);

        $url = $myts->addSlashes($_POST['url']);
    }

    $logourl = $myts->addSlashes($_POST['logourl']);

    $title = $myts->addSlashes($_POST['title']);

    $email = $myts->addSlashes($_POST['email']);

    $description = $myts->addSlashes($_POST['description']);

    $submitter = $xoopsUser->uid();

    $result = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('mylinks_links') . " where url='$url'");

    [$numrows] = $xoopsDB->fetchRow($result);

    $errormsg = '';

    $error = 0;

    if ($numrows > 0) {
        $errormsg .= '<h4><font color="#ff0000">';

        $errormsg .= _ALBM_ERROREXIST . '</font></h4><br>';

        $error = 1;
    }

    // Check if Title exist

    if ('' == $title) {
        $errormsg .= '<h4><font color="#ff0000">';

        $errormsg .= _ALBM_ERRORTITLE . '</font></h4><br>';

        $error = 1;
    }

    // Check if Description exist

    if ('' == $description) {
        $errormsg .= '<h4><font color="#ff0000">';

        $errormsg .= _ALBM_ERRORDESC . '</font></h4><br>';

        $error = 1;
    }

    if (1 == $error) {
        xoops_cp_header();

        echo $errormsg;

        xoops_cp_footer();

        exit();
    }

    if (!empty($_POST['cid'])) {
        $cid = $_POST['cid'];
    } else {
        $cid = 0;
    }

    $newid = $xoopsDB->genId($xoopsDB->prefix('mylinks_links') . '_lid_seq');

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('mylinks_links') . " (lid, cid, title, url, email, logourl, submitter, status, date, hits, rating, votes, comments) VALUES ($newid, $cid, '$title', '$url', '$email', '$logourl', $submitter, 1, " . time() . ', 0, 0, 0, 0)') or $eh::show('0013');

    if (0 == $newid) {
        $newid = $xoopsDB->getInsertId();
    }

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('mylinks_text') . " (lid, description) VALUES ($newid, '$description')") or $eh::show('0013');

    redirect_header('index.php', 1, _ALBM_NEWLINKADDED);
}

function approve()
{
    global $xoopsConfig, $xoopsDB, $_POST, $myts, $eh;

    $lid = $_POST['lid'];

    $title = $_POST['title'];

    $cid = $_POST['cid'];

    if (empty($cid)) {
        $cid = 0;
    }

    $email = $_POST['email'];

    $description = $_POST['description'];

    if (($_POST['url']) || ('' != $_POST['url'])) {
        //		$url=$myts->formatURL($_POST["url"]);

        //		$url = urlencode($url);

        $url = $myts->addSlashes($_POST['url']);
    }

    $logourl = $myts->addSlashes($_POST['logourl']);

    $title = $myts->addSlashes($title);

    $email = $myts->addSlashes($email);

    $description = $myts->addSlashes($description);

    $query = 'update ' . $xoopsDB->prefix('mylinks_links') . " set cid='$cid', title='$title', url='$url', email='$email', logourl='$logourl', status=1, date=" . time() . ' where lid=' . $lid . '';

    $xoopsDB->query($query) or $eh::show('0013');

    $query = 'update ' . $xoopsDB->prefix('mylinks_text') . " set description='$description' where lid=" . $lid . '';

    $xoopsDB->query($query) or $eh::show('0013');

    $result = $xoopsDB->query('select submitter from ' . $xoopsDB->prefix('mylinks_links') . " where lid=$lid");

    [$submitterid] = $xoopsDB->fetchRow($result);

    $submitter = XoopsUser::getUnameFromId($submitterid);

    $subject = sprintf(_ALBM_YOURLINK, $xoopsConfig['sitename']);

    $message = sprintf(_ALBM_HELLO, $submitter);

    $message .= "\n\n" . _ALBM_WEAPPROVED . "\n\n";

    $yourlinkurl = XOOPS_URL . '/modules/mylinks/';

    $message .= sprintf(_ALBM_YOUCANBROWSE, $yourlinkurl);

    $message .= "\n\n" . _ALBM_THANKSSUBMIT . "\n\n" . $xoopsConfig['sitename'] . "\n" . XOOPS_URL . "\n" . $xoopsConfig['adminmail'] . '';

    $xoopsMailer = getMailer();

    $xoopsMailer->useMail();

    $xoopsMailer->setToEmails($email);

    $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

    $xoopsMailer->setFromName($xoopsConfig['sitename']);

    $xoopsMailer->setSubject($subject);

    $xoopsMailer->setBody($message);

    $xoopsMailer->send();

    redirect_header('index.php', 1, _ALBM_NEWLINKADDED);
}

function myLinksConfigAdmin()
{
    global $xoopsConfig;

    global $mylinks_perpage, $mylinks_popular, $mylinks_newlinks, $mylinks_sresults, $mylinks_useshots, $mylinks_shotwidth;

    global $myalbum_width, $myalbum_heigth, $myalbum_fsize, $myalbum_managed;

    xoops_cp_header();

    OpenTable();

    echo '<h4>' . _ALBM_GENERALSET . '</h4><br>';

    echo '<form action="index.php" method="post">';

    echo '
    <table width=100% border=0><tr><td nowrap>
    ' . _ALBM_LINKSPERPAGE . "</td><td width=100%>
        <select name=xmylinks_perpage>
        <option value=$mylinks_perpage selected>$mylinks_perpage</option>
        <option value=10>10</option>
        <option value=15>15</option>
        <option value=20>20</option>
        <option value=25>25</option>
        <option value=30>30</option>
        <option value=50>50</option>
    </select>
    </td></tr><tr><td nowrap>
    " . _ALBM_HITSPOP . "</td><td>
        <select name=xmylinks_popular>
        <option value=$mylinks_popular selected>$mylinks_popular</option>
        <option value=10>10</option>
        <option value=20>20</option>
        <option value=50>50</option>
        <option value=100>100</option>
        <option value=500>500</option>
        <option value=1000>1000</option>
    </select>
    </td></tr><tr><td nowrap>
    " . _ALBM_LINKSNEW . "</td><td>
        <select name=xmylinks_newlinks>
        <option value=$mylinks_newlinks selected>$mylinks_newlinks</option>
        <option value=10>10</option>
        <option value=15>15</option>
        <option value=20>20</option>
        <option value=25>25</option>
        <option value=30>30</option>
        <option value=50>50</option>
    </select>";

    echo '</td></tr>';

    echo '<tr><td nowrap>' . _ALBM_USESHOTS . '</td><td>';

    if (1 == $mylinks_useshots) {
        echo '<INPUT TYPE="RADIO" NAME="xmylinks_useshots" VALUE="1" CHECKED>&nbsp;' . _ALBM_YES . '&nbsp;</INPUT>';

        echo '<INPUT TYPE="RADIO" NAME="xmylinks_useshots" VALUE="0" >&nbsp;' . _ALBM_NO . '&nbsp;</INPUT>';
    } else {
        echo '<INPUT TYPE="RADIO" NAME="xmylinks_useshots" VALUE="1">&nbsp;' . _ALBM_YES . '&nbsp;</INPUT>';

        echo '<INPUT TYPE="RADIO" NAME="xmylinks_useshots" VALUE="0" CHECKED>&nbsp;' . _ALBM_NO . '&nbsp;</INPUT>';
    }

    echo '</td></tr>';

    echo '<tr><td nowrap>' . _ALBM_IMGWIDTH . '</td><td>';

    if ('' != $mylinks_shotwidth) {
        echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmylinks_shotwidth\" VALUE=\"$mylinks_shotwidth\"></INPUT>";
    } else {
        echo '<INPUT TYPE="text" size="10" NAME="xmylinks_shotwidth" VALUE="140"></INPUT>';
    }

    echo '</td></tr>';

    echo '<tr><td nowrap>' . _ALBM_MAXWIDTH . '</td><td>';

    if ('' != $myalbum_width) {
        echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_width\" VALUE=\"$myalbum_width\"></INPUT>";
    } else {
        echo '<INPUT TYPE="text" size="10" NAME="xmyalbum_width" VALUE="800"></INPUT>';
    }

    echo '</td></tr>';

    echo '<tr><td nowrap>' . _ALBM_MAXHEIGTH . '</td><td>';

    if ('' != $myalbum_heigth) {
        echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_heigth\" VALUE=\"$myalbum_heigth\"></INPUT>";
    } else {
        echo '<INPUT TYPE="text" size="10" NAME="xmyalbum_heigth" VALUE="600"></INPUT>';
    }

    echo '</td></tr>';

    echo '<tr><td nowrap>' . _ALBM_MAXSIZE . '</td><td>';

    if ('' != $myalbum_fsize) {
        echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_fsize\" VALUE=\"$myalbum_fsize\"></INPUT>";
    } else {
        echo '<INPUT TYPE="text" size="10" NAME="xmyalbum_fsize" VALUE="100000"></INPUT>';
    }

    echo '</td></tr>';

    echo '<tr><td nowrap>' . _ALBM_MANAGED . '</td><td>';

    if (1 == $myalbum_managed) {
        echo '<INPUT TYPE="RADIO" NAME="xmyalbum_managed" VALUE="1" CHECKED>&nbsp;' . _ALBM_YES . '&nbsp;</INPUT>';

        echo '<INPUT TYPE="RADIO" NAME="xmyalbum_managed" VALUE="0" >&nbsp;' . _ALBM_NO . '&nbsp;</INPUT>';
    } else {
        echo '<INPUT TYPE="RADIO" NAME="xmyalbum_managed" VALUE="1">&nbsp;' . _ALBM_YES . '&nbsp;</INPUT>';

        echo '<INPUT TYPE="RADIO" NAME="xmyalbum_managed" VALUE="0" CHECKED>&nbsp;' . _ALBM_NO . '&nbsp;</INPUT>';
    }

    echo '</td></tr>';

    echo '<tr><td>&nbsp;</td></tr>';

    echo '</table>';

    echo '<input type="hidden" name="op" value="myLinksConfigChange">';

    echo '<input type="submit" value="' . _ALBM_SAVE . '">';

    echo '&nbsp;<input type="button" value="' . _ALBM_CANCEL . '" onclick="javascript:history.go(-1)">';

    echo '</form>';

    CloseTable();

    xoops_cp_footer();
}

function myLinksConfigChange()
{
    global $xoopsConfig, $_POST;

    $xmylinks_popular = $_POST['xmylinks_popular'];

    $xmylinks_newlinks = $_POST['xmylinks_newlinks'];

    $xmylinks_perpage = $_POST['xmylinks_perpage'];

    $xmylinks_useshots = $_POST['xmylinks_useshots'];

    $xmylinks_shotwidth = $_POST['xmylinks_shotwidth'];

    $xmyalbum_width = $_POST['xmyalbum_width'];

    $xmyalbum_heigth = $_POST['xmyalbum_heigth'];

    $xmyalbum_fsize = $_POST['xmyalbum_fsize'];

    $xmyalbum_managed = $_POST['xmyalbum_managed'];

    $filename = XOOPS_ROOT_PATH . '/modules/myalbum/cache/config.php';

    $file = fopen($filename, 'wb');

    $content = '';

    $content .= "<?PHP\n";

    $content .= "\n";

    $content .= "###############################################################################\n";

    $content .= "# my Album v1.0                                                                #\n";

    $content .= "#                                                                              #\n";

    $content .= "# \$mylinks_popular:	The number of hits required for a link to be a popular site. Default = 20      #\n";

    $content .= "# \$mylinks_newlinks:	The number of links that appear on the front page as latest listings. Default = 10  #\n";

    $content .= "# \$mylinks_perpage:    	The number of links that appear for each page. Default = 10 #\n";

    $content .= "# \$mylinks_useshots:    	Use screenshots? Default = 1 (Yes) #\n";

    $content .= "# \$mylinks_shotwidth:    	Screenshot Image Width (Default = 140) #\n";

    $content .= "###############################################################################\n";

    $content .= "\n";

    $content .= "\$mylinks_popular = $xmylinks_popular;\n";

    $content .= "\$mylinks_newlinks = $xmylinks_newlinks;\n";

    $content .= "\$mylinks_perpage = $xmylinks_perpage;\n";

    $content .= "\$mylinks_useshots = $xmylinks_useshots;\n";

    $content .= "\$mylinks_shotwidth = $xmylinks_shotwidth;\n";

    $content .= "\$myalbum_width = $xmyalbum_width;\n";

    $content .= "\$myalbum_heigth = $xmyalbum_heigth;\n";

    $content .= "\$myalbum_fsize = $xmyalbum_fsize;\n";

    $content .= "\$myalbum_managed = $xmyalbum_managed;\n";

    $content .= "\n";

    $content .= "?>\n";

    fwrite($file, $content);

    fclose($file);

    redirect_header('index.php', 1, _ALBM_CONFUPDATED);
}

switch ($op) {
    default:
        mylinks();
        break;
    case 'delNewLink':
        delNewLink();
        break;
    case 'approve':
        approve();
        break;
    case 'addCat':
        addCat();
        break;
    case 'addLink':
        addLink();
        break;
    case 'listBrokenLinks':
        listBrokenLinks();
        break;
    case 'delBrokenLinks':
        delBrokenLinks();
        break;
    case 'ignoreBrokenLinks':
        ignoreBrokenLinks();
        break;
    case 'listModReq':
        listModReq();
        break;
    case 'changeModReq':
        changeModReq();
        break;
    case 'ignoreModReq':
        ignoreModReq();
        break;
    case 'delCat':
        delCat();
        break;
    case 'modCat':
        modCat();
        break;
    case 'modCatS':
        modCatS();
        break;
    case 'modLink':
        modLink();
        break;
    case 'modLinkS':
        modLinkS();
        break;
    case 'delLink':
        delLink();
        break;
    case 'delVote':
        delVote();
        break;
    case 'delComment':
        delComment($bid, $rid);
        break;
    case 'myLinksConfigAdmin':
        myLinksConfigAdmin();
        break;
    case 'myLinksConfigChange':
        if (xoopsfwrite()) {
            myLinksConfigChange();
        }
        break;
    case 'linksConfigMenu':
        linksConfigMenu();
        break;
    case 'listNewLinks':
        listNewLinks();
        break;
}
