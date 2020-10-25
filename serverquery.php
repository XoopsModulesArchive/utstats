<?php

function SendQuery($ip, $port, $query)
{
    $qp   = $port + 10;
    $data = '';
    if (($fs = fsockopen('udp://' . $ip, $qp)) === false) {
        return $data;
    } else {
        if (function_exists('stream_set_blocking')) {
            stream_set_blocking($fs, true);
        } elseif (function_exists('socket_set_blocking')) {
            stream_set_blocking($fs, true);
        } else {
            set_socket_blocking($fs, true);
        }
        stream_set_timeout($fs, 1, 0);
        if (fwrite($fs, $query) < 0) {
            fclose($fs);
            return $data;
        }
        do {
            $data .= fgetc($fs);
            if (function_exists('stream_get_meta_data')) {
                $status = stream_get_meta_data($fs);
            } else {
                $status = stream_get_meta_data($fs);
            }
        } while ($status['unread_bytes']);
        fclose($fs);
    }
    return $data;
}

function ParseQuery(&$data, &$param, &$val, &$num)
{
    $num = -1;
    if (($loc = strpos($data, "\\")) === false) {
        $data = $param = $val = '';
    } else {
        if ($loc == 0) {
            $param = substr($data, 1);
            if (($loc2 = strpos($param, "\\")) === false) {
                $data = '';
            } else {
                $val   = substr($param, $loc2 + 1);
                $param = strtolower(substr($param, 0, $loc2));
                if (!strcmp($param, 'final') || !strcmp($param, 'echo') || ($loc3 = strpos($val, "\\")) === false) {
                    $data = $param = $val = '';
                } else {
                    $data = substr($data, $loc + $loc2 + $loc3 + 2);
                    $val  = substr($val, 0, $loc3);
                    if (($us = strpos($param, '_')) !== false) {
                        $num   = (int)substr($param, $us + 1);
                        $param = substr($param, 0, $us);
                    }
                }
            }
        } else {
            $data = $param = $val = '';
        }
    }
    if ($param != '' && $val != '') {
        $ok = 1;
    } else {
        $ok = 0;
    }
    return $ok;
}

function GetStatus($ip, $port)
{
    global $server, $player, $spectator, $team, $spect;

    $ok   = 0;
    $data = SendQuery($ip, $port, "\\basic\\\\info\\\\rules\\\\gamestatus\\\\echo\\nothing");

    if (isset($server)) {
        while (array_pop($server) != null) {
            ;
        }
    }

    while (strlen($data)) {
        $ok = 1;
        if (ParseQuery($data, $param, $val, $num)) {
            if ($num >= 0) {
                $spectator[$num][$param] = $val;
            } else {
                switch ($param) {
                    case 'gametype':
                        switch (strtolower($val)) {
                            case 'xdeathmatch':
                            case 'logdeathmatch':
                                $val = 'Death Match';
                                break;
                            case 'xteamgame':
                            case 'logteamgame':
                                $val = 'Team Deathmatch';
                                break;
                            case 'xctfgame':
                            case 'logctfgame':
                                $val = 'Capture The Flag';
                                break;
                            case 'xbombingrun':
                            case 'logbombingrun':
                                $val = 'Bombing Run';
                                break;
                            case 'xdoubledom':
                            case 'logdoubledom':
                                $val = 'Double Domination';
                                break;
                        }
                        break;
                    case 'mapname':
                    case 'maptitle':
                    case 'adminname':
                    case 'adminemail':
                    case 'nextmap':
                        $val = htmlspecialchars($val, ENT_QUOTES | ENT_HTML5);
                        break;
                    case 'timelimit':
                    case 'goalscore':
                        if (!$val) {
                            $val = 'None';
                        }
                        break;
                    case 'balanceteams':
                    case 'gamestats':
                    case 'translocator':
                        if (!strcasecmp($val, 'true') || $val == '1') {
                            $val = 'Enabled';
                        } else {
                            $val = 'Disabled';
                        }
                        break;
                    case 'overtime':
                        if (!strcasecmp($val, 'true') || $val == '1') {
                            $val = 'True';
                        } else {
                            $val = 'False';
                        }
                        break;
                    case 'password':
                        if (!strcasecmp($val, 'true') || $val == '1') {
                            $val = 'Required';
                        } else {
                            $val = 'None';
                        }
                        break;
                    case 'elapsedtime':
                        $val = sprintf('%0.1f', $val / 60.0);
                        break;
                }
            }
            $server[$param] = $val;
        }
    }

    if (!isset($server['mutator'])) {
        $server['mutator'] = 'None';
    }
    if (!isset($server['friendlyfire'])) {
        $server['friendlyfire'] = 'n/a';
    }
    if (!isset($server['balanceteams'])) {
        $server['balanceteams'] = 'n/a';
    }

    $data = SendQuery($ip, $port, "\\players\\\\echo\\nothing");
    if (isset($player)) {
        while (array_pop($player) != null) {
            ;
        }
    }
    while (strlen($data)) {
        if (ParseQuery($data, $param, $val, $num)) {
            if ($num >= 0) {
                if ($param == 'player') {
                    $val = htmlspecialchars($val, ENT_QUOTES | ENT_HTML5);
                }
            }
            $player[$num][$param] = $val;
        }
    }

    $data = SendQuery($ip, $port, "\\teams\\\\echo\\nothing");
    if (isset($team)) {
        while (array_pop($team) != null) {
            ;
        }
    }
    while (strlen($data)) {
        if (ParseQuery($data, $param, $val, $num)) {
            if ($num >= 0) {
                $team[$num][$param] = $val;
            }
        }
    }

    $data = SendQuery($ip, $port, "\\spectators\\\\echo\\nothing");
    if (isset($spect)) {
        while (array_pop($spect) != null) {
            ;
        }
    }
    while (strlen($data)) {
        if (ParseQuery($data, $param, $val, $num)) {
            if ($num >= 0) {
                if ($param == 'spectator') {
                    $val = htmlspecialchars($val, ENT_QUOTES | ENT_HTML5);
                }
            }
            $spect[$num][$param] = $val;
        }
    }

    return $ok;
}

function DisplayStatus()
{
    global $server, $team;

    echo <<<EOF
<center>
<table CLASS="status" CELLSPACING="0" CELLPADDING="1" WIDTH="450">
  <tr>
    <td CLASS="statustitle" ALIGN="center" COLSPAN="5">
      <b>Current Status for {$server['hostname']}</b>
    </td>
  </tr>

EOF;

    if (isset($server['minplayers'])) {
        echo <<<EOF
  <tr>
    <td ALIGN="right">Map:</td>
    <td ALIGN="left">{$server['mapname']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Current Players:</td>
    <td ALIGN="left">{$server['numplayers']}</td>
  </tr>
  <tr>
    <td ALIGN="right">Game Type:</td>
    <td ALIGN="left">{$server['gametype']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Min Players:</td>
    <td ALIGN="left">{$server['minplayers']}</td>
  </tr>
  <tr>
    <td ALIGN="right">Mutators:</td>
    <td ALIGN="left">{$server['mutator']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Max Players:</td>
    <td ALIGN="left">{$server['maxplayers']}</td>
  </tr>
  <tr>
    <td ALIGN="right">Game Stats:</td>
    <td ALIGN="left">{$server['gamestats']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Score Limit:</td>
    <td ALIGN="left">{$server['goalscore']}</td>
  </tr>
  <tr>
    <td ALIGN="right">Translocator:</td>
    <td ALIGN="left">{$server['translocator']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Time Limit:</td>
    <td ALIGN="left">{$server['timelimit']}</td>
  </tr>
  <tr>
    <td ALIGN="right">Time In Game:</td>
    <td ALIGN="left">{$server['elapsedtime']} minutes</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Overtime:</td>
    <td ALIGN="left">{$server['overtime']}</td>
  </tr>
  <tr>
    <td ALIGN="right">Next Map:</td>
    <td ALIGN="left">{$server['nextmap']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Password:</td>
    <td ALIGN="left">{$server['password']}</td>
  </tr>

EOF;
    } else {
        echo <<<EOF
  <tr>
    <td ALIGN="right">Map:</td>
    <td ALIGN="left">{$server['mapname']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Current Players:</td>
    <td ALIGN="left">{$server['numplayers']}</td>
  </tr>
  <tr>
    <td ALIGN="right">Game Type:</td>
    <td ALIGN="left">{$server['gametype']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Max Players:</td>
    <td ALIGN="left">{$server['maxplayers']}</td>
  </tr>
  <tr>
    <td ALIGN="right">Mutators:</td>
    <td ALIGN="left">{$server['mutator']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Password:</td>
    <td ALIGN="left">{$server['password']}</td>
  </tr>

EOF;
    }

    if (isset($team[0])) {
        echo <<<EOF
  <tr>
    <td ALIGN="right">Friendly Fire:</td>
    <td ALIGN="left">{$server['friendlyfire']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">Balance Teams:</td>
    <td ALIGN="left">{$server['balanceteams']}</td>
  </tr>
  <tr>
    <td ALIGN="right">{$team[1]['team']} Team Size:</td>
    <td ALIGN="left">{$team[1]['size']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">{$team[0]['team']} Team Size:</td>
    <td ALIGN="left">{$team[0]['size']}</td>
  </tr>
  <tr>
    <td ALIGN="right">{$team[1]['team']} Team Score:</td>
    <td ALIGN="left">{$team[1]['score']}</td>
    <td>&nbsp;</td>
    <td ALIGN="right">{$team[0]['team']} Team Score:</td>
    <td ALIGN="left">{$team[0]['score']}</td>
  </tr>

EOF;
    }

    echo <<<EOF
</table>
</center>
<br>

EOF;
}

function DisplayPlayers()
{
    global $server, $player, $team;

    if (isset($player)) {
        $header = 0;
        if (isset($team[0])) {
            $type = 'Score';
        } else {
            $type = 'Frags';
        }

        foreach ($player as $plr) {
            if (!$header) {
                if (isset($server['minplayers'])) {
                    $ncol = 5;
                } else {
                    $ncol = 3;
                }
                if (isset($team[0])) {
                    $ncol++;
                }
                $cwidth = ($ncol * 50) + 150;
                if ($cwidth < 350) {
                    $cwidth = 350;
                }
                echo <<<EOF
<center>
<table CLASS="status" CELLSPACING="0" CELLPADDING="1" WIDTH="$cwidth">
  <tr>
    <td CLASS="statustitle" ALIGN="center" COLSPAN="$ncol">
      <b>Player List for {$server['hostname']}</b>
    </td>
  </tr>
  <tr>
    <td><b>Name</b></td>
    <td WIDTH="50"><b>$type</b></td>

EOF;
                if (isset($server['minplayers'])) {
                    echo "    <td WIDTH=\"50\"><b>Deaths</b></td>\n";
                }
                if (isset($team[0])) {
                    echo "    <td WIDTH=\"50\"><b>Team</b></td>\n";
                }
                if (isset($server['minplayers'])) {
                    echo "    <td WIDTH=\"50\"><b>Scored</b></td>\n";
                }

                echo "    <td WIDTH=\"50\"><b>Ping</b></td>
  </tr>\n";
                $header = 1;
            }

            echo <<<EOF
  <tr>
    <td>{$plr['player']}</td>
    <td>{$plr['frags']}</td>

EOF;
            if (isset($server['minplayers'])) {
                echo "    <td>{$plr['deaths']}</td>\n";
            }
            if (isset($team[0])) {
                echo "    <td>{$plr['team']}</td>\n";
            }
            if (isset($server['minplayers'])) {
                echo "    <td>{$plr['scored']}</td>\n";
            }

            echo "    <td>{$plr['ping']}</td>
  </tr>\n";
        }

        echo <<<EOF
</table>
</center>
<br>

EOF;
    }
}

function DisplaySpectators()
{
    global $server, $spect;

    if (isset($spect)) {
        $header = 0;
        foreach ($spect as $spc) {
            if (!$header) {
                echo <<<EOF
<center>
<table CLASS="status" CELLSPACING="0" CELLPADDING="1" WIDTH="300">
  <tr>
    <td CLASS="statustitle" ALIGN="center" COLSPAN="2">
      <b>Spectator List for {$server['hostname']}</b>
    </td>
  </tr>
  <tr>
    <td><b>Name</b></td>
    <td><b>Ping</b></td>
  </tr>

EOF;
                $header = 1;
            }

            echo <<<EOF
  <tr>
    <td>{$spc['spectator']}</td>
    <td>{$spc['specping']}</td>
  </tr>

EOF;
        }

        echo <<<EOF
</table>
</center>
<br>

EOF;
    }
}


