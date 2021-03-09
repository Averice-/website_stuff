<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/
include_once(__ROOT__."/includes/core/user.php");

$Nav = array();
$Cur = 0;

$Nav['home'] = array();
$Nav['home']['img'] = "Configuration.png";
$Nav['home']['text'] = "Index";
$Nav['home']['link'] = "index.php";
$Nav['home']['child'] = array(	"- Articles" => "#",
								"- Events" => "#");

$Nav['user'] = array();
$Nav['user']['img'] = "Send-message.png";
$Nav['user']['text'] = "User Center";
$Nav['user']['link'] = "#";
if( userIsLogged() ){
	$Nav['user']['child'] = array(	"- Profile" => "#",
									"- Messages" => "#",
									"- Edit Profile" => "#",
									"- Logout" => "#");
}else{
	$Nav['user']['child'] = array(	"- Login" => "#",
									"- Register" => "index.php?p=register");
}

$Nav['forum'] = array();
$Nav['forum']['img'] = "Diagram.png";
$Nav['forum']['text'] = "Boards";
$Nav['forum']['link'] = "#";
$Nav['forum']['child'] = array(	"- New Posts" => "#",
								"- Hot Topics" => "#",
								"- Latest Topics" => "#");

$Nav['code'] = array();
$Nav['code']['img'] = "Clipboard-file.png";
$Nav['code']['text'] = "Codebin";
$Nav['code']['link'] = "#";
$Nav['code']['child'] = array(	"- Create Paste" => "#",
								"- New Pastes" => "#",
								"- Popular Pastes" => "#",
								"- Recently Solved" => "#",
								"- Problem Center" => "#");

$Nav['art'] = array();
$Nav['art']['img'] = "Dice.png";
$Nav['art']['text'] = "Creative";
$Nav['art']['link'] = "#";
$Nav['art']['child'] = array(	"- Visuals/Textures" => "#",
								"- Models" => "#",
								"- Tutorials" => "#",
								"- Popular Items" => "#");

$Nav['prog'] = array();
$Nav['prog']['img'] = "Equipment.png";
$Nav['prog']['text'] = "Programs";
$Nav['prog']['link'] = "#";
$Nav['prog']['child'] = array(	"- Applications" => "#",
								"- Frameworks" => "#",
								"- Tutorials" => "#",
								"- Popular" => "#");

echo "<table width=220>";
	foreach( $Nav as $key ){
		$Cur++;
		$NavStyle = $Cur % 2 == 0 ? "navLink2" : "navLink";
		echo 	"<tr class='$NavStyle'>
					<td style='border-bottom:#CC0000 solid 1px;'><img src='imgx/black/".$key['img']."' width=16 height=16 /><a style='margin-top:2px;' href='".$key['link']."'>".$key['text']."</a></td>
				</tr>";
				foreach( $key['child'] as $k=>$v ){
					$Cur++;
					$NavStyle = $Cur % 2 == 0 ? "navLink2" : "navLink";
					echo 	"<tr class='$NavStyle'>
								<td><a style='margin-left:3em; font-weight:normal;' href='$v'>$k</a></td>
							</tr>";
				}
	}
echo "</table>";



?>