<?php
$starttime = microtime(true);

echo "<table width=\"100%\"><tr><th colspan=2>Mod Name</th><th>Description</th><th width=200>Tags</th><th>Likes</th></tr>\n";
$query= mysql_real_escape_string ($query);

if ($mode=="tags"){
    if ($query=="dns"){
        $qu_str="SELECT * FROM mods WHERE tags LIKE '%$query%'";
    }else{
        $qu_str="SELECT * FROM mods WHERE tags LIKE '%$query%' AND tags NOT LIKE '%dns%'";
    }
}else if ($mode=="sb"){
  if (is_numeric($query)==true){
    echo "<!--is numeric-->";
     $qu_str="SELECT * FROM mods WHERE owner=$query";
  }else{
     $qu_str="SELECT * FROM mods WHERE tags NOT LIKE '%dns%' AND (tags LIKE '%$query%' OR name LIKE '%$query%' OR overview LIKE '%$query%')";
  }
}


$res = mysql_query($qu_str." ORDER BY likes DESC",$handle) or SQLerror("MySQL Query Error","Error on searching database.mods.tags for '$query'");

$alternate=1;
// Get projects loop
$is_result=false;
$count=0;
while ($hash = mysql_fetch_assoc($res)){
	$count++;
      $is_result=true;
  
      if ($alternate==0){
         $bgcolor="#FFFFFF";
      }else{
         $bgcolor="#FFFFBD";
      }

      // Owner name from id (by Phitherek_)

      $name="";
      $q=0;
      $q = mysql_query("SELECT name FROM users WHERE id=".$hash['owner']);
      if ($q){
      $qr = mysql_fetch_array($q) or print("");
      $name=$qr['name'];
      }
      // End of Phitherek_' s code

      $image="images/topicicon_read.jpg";
      
      if ($hash['icon'])
          $image="icon/".$hash['icon'];

      // Owner name instead of id (by Phitherek_)
      echo "<tr bgcolor=\"$bgcolor\"><td width=16><img width=16 height=16 src=\"$image\" /></td><td><a href=\"viewmod.php?id={$hash['mod_id']}\">{$hash['name']}</a><br />by $name</td>";
      // End of Phitherek_' s change
      $overview=str_replace("\\'","'",$hash['overview']);
      echo "<td>$overview</td>";
      echo "<td>".tagLinks($hash['tags'])."</td>";
      echo "<td>{$hash['likes']}</td></tr>\n";

      $alternate=1-$alternate;
}

$endtime = microtime(true);
$duration = $endtime - $starttime; //calculates total time taken

if ($is_result==false)
	echo "<tr><td colspan=5 style=\"text-align:center;\"><i>no search results found</i></td></tr>";
else
	echo "<tr><td colspan=5 style=\"text-align:center;\"><i>$count results in $duration seconds</i></td></tr>";

echo "</table>";
?>
