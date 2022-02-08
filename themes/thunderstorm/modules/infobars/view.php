<?php
/**
 * [PART OF QUICK WEB FRAME]
 * theme / desktopfront / view / common / infobars.php
 */

if (isset($this->GLOBAL['infomsg'])){
	echo '<div id="infobar">'.$this->GLOBAL['infomsg'].'</div>';
}

if (isset($this->GLOBAL['errormsg'])){
	echo '<div id="errorbar">'.$this->GLOBAL['errormsg'].'</div>';
}
