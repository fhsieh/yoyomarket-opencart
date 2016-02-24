<?php  $class = 3; 
if($category_number == 6) $class = 2;
if($category_number == 5) $class = 25;
if($category_number == 3) $class = 4;
if($category_number == 2) $class = 6; 

$i = 0;
echo '<div class="category-wall"><div class="row">';
	foreach($categories as $category) {
		if($i%$category_number == 0 && $i != 0) echo '</div><div class="row">';
		echo '<div class="col-sm-' . $class . ' col-xs-6">';
			if($category['image']) echo '<a href="' . $category['href'] . '"><img src="' . $category['image'] .'" alt="' . $category['name'] . '"></a>';
			echo '<div class="name"><a href="' . $category['href'] . '">' . $category['name'] . '</a></div>';
			
			if(count($category['children']) > 0) {
				echo '<ul>';
				foreach($category['children'] as $child) {
					echo '<li><a href="' . $child['href'] . '">' . $child['name'] . '</a></li>';
				}
				echo '</ul>';
				echo '<a href="' . $category['href'] . '" class="more-link">' . $more_text . '</a>';
			}
		echo '</div>';
		$i++;
	}
echo '</div></div>'; ?>