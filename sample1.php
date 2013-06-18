<?php
function MaxArray($arr){
	$max = 0;
	foreach($arr as $values) {
		if (is_array($values)) {
			foreach($values as $a) {
				if(is_array($a)) {
					foreach($a as $b) {
						if (is_array($b)) {
							foreach($b as $c) {
								if(is_array($c)) {
									
								} else {
									if ($c > $max) {
										$max = $c;
									}
								}
							}
						}else{
							if ($b > $max) {
								$max = $b;
							}
				
						}
					}
				} else {
				if ($a > $max) {
						$max = $a;
					}
				}
			}
		 else {
			if (values > $max) {
				$max = $values;
			}
		}
	}
	return $max;
}
$arr = array(array(141,151,161), 2, 3, array(101, 202, array(303,404)));
echo MaxArray($arr);
?>