<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/
include_once(__ROOT__."/includes/core/cfuncs.php");
include_once(__ROOT__."/includes/core/user.php");

function getBoardIndex(){
	global $Connection;
	$Connection->Connect();

	$qRes = $Connection->Query("SELECT * FROM devnet_cats ORDER BY 'position' ASC");
	while( $catRow = mysql_fetch_assoc($qRes) ){
		echo 	"<div class='sidebar' id='".$catRow['id']."'>
					<div class='sideheader'><div class='midheader'>".$catRow['title']."</div><div id='".$catRow['id']."MiniBtn' class='miniButton' onclick='miniMax(document.getElementById(".$catRow['id']."), document.getElementById(\"".$catRow['id']."MiniBtn\"));'><center>-</center></div></div>";

		$sectRes = $Connection->Query("SELECT * FROM devnet_sect WHERE catid='".$catRow['id']."' ORDER BY 'position' ASC");
		while( $secRow = mysql_fetch_assoc($sectRes) ){
			if( $secRow['subfor'] == 0 ){
				echo 	"<div class='forumcontainer'>
							<div class='imgLeftCont'><img src='imgx/black/Options.png' width=32 height=32 /></div>
							<div class='mainCont'>".$secRow['title']."</b><br /><span style='font-size:10px; font-weight:normal; color:#707070;'>".$secRow['desc']."</span></div>
							<div class='lastPostCont'>";
							if( !empty($secRow['lastpost']) ){
								$userDetails = breakPosterDetails($secRow['lastpost']);
								$Avatar = !empty($userDetails['user']['avatar']) ? $userDetails['user']['avatar'] : 'imgx/no-avatar.png';
								echo 	"<div class='smallavatarbox'><img width=32 height=32 src='$Avatar' /></div>
										<div class='smallTextBox'><a href='#'>Thread ".$userDetails['user']['uname']."</a><br /><span style='color:#707070;'>".$userDetails['timestamp']."</span></div>";
							}else{
								echo "<span style='color:#999999; font-size:10px;'>No Posts</span>";
							}
							echo "</div>
							<div class='threadCount'>".$secRow['postcount']." Posts<br />".$secRow['threadcount']." Threads</div>
						</div>";
			}
		}
		echo "</div>";
	}	
	$Connection->Close();

}


?>