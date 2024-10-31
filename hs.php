<?php
/*
Plugin Name: RS Highscore Widget
Plugin URI: http://benlantaff.com/?page_id=49
Description: This plugin allows you to display your Runescape Highscores as a widget in your sidebar.  Make sure to put your username in the widget control panel.
Author: Ben Lantaff
Version: 1.0
Author URI: http://www.benlantaff.com
*/

function displayHighscores() 
{

$options = get_option("widget_RSHighscores");
$player = $options['rsname'];

$order = array("Overall", 
         "Attack", 
         "Defence", 
         "Strength", 
         "Hitpoints", 
         "Ranged", 
         "Prayer", 
         "Magic", 
         "Cooking", 
         "Woodcutting", 
         "Fletching", 
         "Fishing", 
         "Firemaking", 
         "Crafting", 
         "Smithing", 
         "Mining", 
         "Herblore", 
         "Agility", 
         "Thieving", 
         "Slayer", 
         "Farming", 
         "Runecraft", 
         "Hunter", 
         "Construction", 
         "Summoning"); 

         $ch = curl_init();


//Set curl to return the data instead of printing it to the browser.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//Set the URL
curl_setopt($ch, CURLOPT_URL, "http://hiscore.runescape.com/index_lite.ws?player=".$player);
//this is the URL you use to get the data

//Execute the fetch
$source = curl_exec($ch);

//Close the connection
curl_close($ch);
$source = explode("\n", $source);
$i = 0;

foreach ($order as $key => $value)
{
   $value = strtolower($value);
   $temp = explode(",", $source[$i]);
   $temp = array("rank" => $temp[0], "level" => $temp[1], "exp" => $temp[2]);
   $stats[$value] = $temp;
   $eval = "\$$value = array(\$temp[\"rank\"], \$temp[\"level\"], \$temp[\"exp\"]);";
   eval($eval);
   $i++;
//This tells it to parse it all out.
}

  echo '<style type="text/css">#hsTable {font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size: 12px;border-collapse: collapse;text-align: left;}#hsTable th{font-size: 14px;font-weight: normal;color: #039;padding: 10px 8px;border-bottom: 2px solid #6678b1;}#hsTable td{color: #669;padding: 9px 8px 0px 8px;}#hsTable tbody tr:hover td{color: #009;}</style>';
  echo '
<table id="hsTable">
<tr><th scope="col">Skill</th><th scope="col">Lvl</th></tr>
<tr><td>Attack</td><td>'.$attack[1].'</td></tr>
<tr><td>Defence</td><td>'.$defence[1].'</td></tr>
<tr><td>Strength</td><td>'.$strength[1].'</td></tr>
<tr><td>HP</td><td>'.$hitpoints[1].'</td></tr>
<tr><td>Ranged</td><td>'.$ranged[1].'</td></tr>
<tr><td>Prayer</td><td>'.$prayer[1].'</td></tr>
<tr><td>Magic</td><td>'.$magic[1].'</td></tr>
<tr><td>Cooking</td><td>'.$cooking[1].'</td></tr>
<tr><td>Woodcutting</td><td>'.$woodcutting[1].'</td></tr>
<tr><td>Fletching</td><td>'.$fletching[1].'</td></tr>
<tr><td>Fishing</td><td>'.$fishing[1].'</td></tr>
<tr><td>Firemaking</td><td>'.$firemaking[1].'</td></tr>
<tr><td>Crafting</td><td>'.$crafting[1].'</td></tr>
<tr><td>Smithing</td><td>'.$smithing[1].'</td></tr>
<tr><td>Mining</td><td>'.$mining[1].'</td></tr>
<tr><td>Herblore</td><td>'.$herblore[1].'</td></tr>
<tr><td>Agility</td><td>'.$agility[1].'</td></tr>
<tr><td>Thieving</td><td>'.$thieving[1].'</td></tr>
<tr><td>Slayer</td><td>'.$slayer[1].'</td></tr>
<tr><td>Farming</td><td>'.$farming[1].'</td></tr>
<tr><td>Runecrafting</td><td>'.$runecraft[1].'</td></tr>
<tr><td>Hunter</td><td>'.$hunter[1].'</td></tr>
<tr><td>Construction</td><td>'.$construction[1].'</td></tr>
<tr><td>Summoning</td><td>'.$summoning[1].'</td></tr>
</table>';

}

function widget_RSHighscores($args) {
  extract($args);

  $options = get_option("widget_RSHighscores");
  if (!is_array( $options ))
{
$options = array(
      'rsname' => 'Type RS name here'
      ); 
  }      

  echo $before_widget;
    echo $before_title;
      echo $options['rsname'];
    echo $after_title;

    //Our Widget Content
    displayHighscores();
  echo $after_widget;
}

function RSHighscores_control() 
{
  $options = get_option("widget_RSHighscores");
  if (!is_array( $options ))
{
$options = array(
      'rsname' => 'Type RS name here'
      ); 
  }    

  if ($_POST['RSHighscores-Submit']) 
  {
    $options['rsname'] = $_POST['RSHighscores-WidgetTitle'];
    update_option("widget_RSHighscores", $options);
  }

?>
  <p>
    <label for="RSHighscores-WidgetTitle">RS name: </label>
    <input type="text" id="RSHighscores-WidgetTitle" name="RSHighscores-WidgetTitle" value="<?php echo $options['rsname'];?>" />
    <input type="hidden" id="RSHighscores-Submit" name="RSHighscores-Submit" value="1" />
  </p>
<?php
}

function RSHighscores_init()
{
  register_sidebar_widget(__('RS Highscores'), 'widget_RSHighscores');
  register_widget_control(   'RS Highscores', 'RSHighscores_control', 200, 200 );     
}
add_action("plugins_loaded", "RSHighscores_init");
?>