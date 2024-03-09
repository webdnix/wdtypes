<?php
global $post;
$meta = get_post_meta( $post->ID, $name, true );
$style = empty($style) ? '' : ' style="'.$style.'"';
$class = empty($class) ? '' : ' class="'.$class.'"';
$id = empty($id) ? '' : ' id="'.$id.'"';
$val = '';
if(!empty($meta)){
	if(!empty($tag)){
		$val = '<'.$tag.$style.$class.$id.'>'.$meta.'</'.$tag.'>';
	}else if(!empty($style) || !empty($class) || !empty($id)){
		$val = '<span'.$style.$class.$id.'>'.$meta.'</span>';
	}else{
		$val = $meta;
	}
}
echo __( $val ); 
?>