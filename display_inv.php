<?php
/**
 * Created by PhpStorm.
 * Author: popstarfreas (https://dark-gaming.com/profile/popstarfreas)
 * Date: 26/12/14
 * Time: 20:48
 */
if(!defined('index')) exit;

// Include array of the IDs for items
include_once 'items_array.php';

$body = "";
$inventory = array_merge($response['items']['inventory'], $response['items']['equipment'], $response['items']['dyes'], $response['items']['piggy'], $response['items']['safe'], $response['items']['forge']);


$i = 0;
foreach ($inventory as $item) {
    if ($i > 208) {//88 dyes // 128 piggy // 168 safe// 208 forge
	break;
    }
    if ($item['netID'] !== 0) {
        $itemFile = str_replace(' ', '_', $itemIDs[$item['netID']] . '.png');
        $img_tag = '<img title="' . $itemPrefixes[$item['prefix']] . " " . $itemIDs[$item['netID']] . '"src="items_images/' . $itemFile . '"/>';
    }
    else {
        $item_name2 = "";
    }

    if ($i == 0) {
        $body .= '<p id="title">Inventory</p>' . "\n\n";
        $body .= '<table id="inv_table"><tr>';
    }

    // For each slot in inventory with total 50 slots making up 10 columns and 5 rows
    // This is shorter than the previous 'if' used so I decided to use it instead of $i == 10 || $i == 20 etc.
    // The < 50 represents the max slots in inventory
    if ($i % 10 === 0 && $i < 50) {
        $body .= '</tr><tr>';
    }
    if (($i + 1) % 10 === 0 && $i > 89) {
        $body .= '</tr><tr>';
    }

    // Check to see if we're now onto Coins or Ammo slots
    // Check for first Coin slot
    if ($i === 50) {
        $body .= '</tr></table>' . "\n\n" . '<div id="Coins_table">';
        $body .= '<span id="Coins">Coins</span><table><tr>';
    }

    // Check for first Ammo slot
    if ($i === 54) {
        $body .= '</table></div><div id="Ammo_table">';
        $body .= '<span id="Ammo">Ammo</span><table><tr>';
    }

    // Check to see if we're inside Coins or Ammo tables
    if ($i > 50 && $i < 58) {
        $body .= '</tr><tr>';
    }

    // Check to see if we're on the last "table"
    if($i === 59) {
        $body .= '</table></div><div class="stuff_table stuff_tableFix">';
        $body .= '<span id="Armor">Equip</span><table><tr>';
    }

    if($i === 69 || $i === 79) {
        $body .= '</tr></table></div>'."\n\n".'<div class="stuff_table'.'">';
        $body .= '<span id="Dye">'.($i === 69 ? 'Social' : 'Dye').'</span><table><tr>';
    }

    if($i === 89) {
        $body .= '</table></div><div id="Piggy_Bank_Table">';
        $body .= '<span id="Piggy">Piggy Bank</span><table><tr>';
    }
    if($i === 129) {
        $body .= '</table></div><div id="Safe_Table">';
        $body .= '<span id="Safe">Safe</span><table><tr>';
    }
    if($i === 169) {
        $body .= '</table></div><div id="DefendersForge_Table">';
        $body .= '<span id="Defenders_Forge">Defender\'s Forge</span><table><tr>';
    }
    
    // The last table has only 1 row per table so we close the current row
    // and open a new one here.
    if($i > 59 && $i < 89) {
        $body .= '</tr><tr>';
    }


    if ($item['netID'] == 0) {
        if ($i === 58) {
        } else {
            $body .= '<td><div class="item"><div class="item2"><span class="empty"></span></div>';
            if ($i < 10) {
                $body .= '<div class="num2">' . ($i < 9 ? $i + 1 : 0) . '</div>';
            }
            $body .= '</div></td>';
        }
    } else {
        // Output data with item
        $body .= '<td><div class="item"></div><div class="item2"><div class="img">' . $img_tag . '</div>';
        if ($i < 10) {
            $body .= '<div class="num2">' . ($i < 9 ? $i + 1 : 0) . '</div>';
        }
        $body .= '</div>';
        if (($i < 59 || $i > 88) && $item['stack'] > 1) {
            $body .= '<span class="num">' . $item['stack'] . '</span>';
        }
        $body .= "</td>\n";
    }
    $i++;
}
$body .= '</tr></table></div>';

$body .= '<div id="Buffs">Buffs</div><div id="Buff_table">';

// Display buffs
$buffs = explode(', ', $player['info']['buffs']);
$i = 0;
foreach($buffs as $buff) {
    if($buff > 0) {
        $body .= '<div class="buffImg"><img src="items_images/Buff_'.$buff.'.png" /></div>';
        $i++;
    }
}
$body .= '</div>';

// Display Player Position
if ($config['display_position'])
$body .= '<div id="Position">Position: <em>'.$player['info']['position'].'</em></div>';

// Display Player Group
if ($config['display_group'])
    $body .= '<div id="Group">Group: <em>' . $player['info']['group'] . '</em></div>';

// Display IP
if ($config['display_ip'])
    $body .= '<div id="UserIP">IP: <em>' . $player['info']['ip'] . '</em></div>';

$body .= '<a id="return" href="?">Go back</a>';
file_put_contents('body', $body);
// HTML
?>

<!DOCTYPE html>
<html>
<head profile="http://www.w3.org/2005/10/profile">
    <link rel="icon"
          type="image/png"
          href="favicon.png">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic' rel='stylesheet' type='text/css'/>
    <link rel="stylesheet" href="styles.css" type="text/css"/>
    <title>T-Inv</title>
</head>
<body style="background: url('<?php echo $defaultBG; ?>'); width: 100%;">
<?php echo $body; ?>
</body>
</html>
