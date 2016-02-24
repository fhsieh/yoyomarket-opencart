<?php
if($type == 1) {
	if($position == 'footer_bottom' || $position == 'footer_top' || $position == 'footer_left' || $position == 'footer_right' || $position == 'customfooter_top' || $position == 'customfooter_bottom') {
		echo '<h4>'.$block_heading.'</h4>';
		echo '<div class="strip-line"></div>';
		echo $block_content;
	} else {
		echo '<div class="box">';
			echo '<div class="box-heading">';
				echo $block_heading;
			echo '</div>';
			echo '<div class="strip-line"></div>';
			echo '<div class="box-content">';
				echo $block_content;
			echo '</div>';
		echo '</div>';	
	}	
} else {
	echo $text;
}
?>