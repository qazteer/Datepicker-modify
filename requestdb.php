<?php 

public function onAfterSaveProduct(&$product){
	$db = JFactory::getDbo();
	$post = JRequest::get('post');
	$query = $db->getQuery(true);
	$query = "UPDATE `#__jshopping_products` SET `date_admin`= '".$post['dynamicDateAdmin']."' WHERE `product_id`=".$product->product_id;
	$db->setQuery($query);
 	$result = $db->execute();
 	if($result){
 		$product->date_admin = $post['dynamicDateAdmin'];
 	}
}

?>