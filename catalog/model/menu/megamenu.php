<?php
/* 
Version: 1.0
Author: Artur Sułkowski
Website: http://artursulkowski.pl
*/

class ModelMenuMegamenu extends Model {		
	public function getMenu() {
		$output = array();
		$lang_id = $this->config->get('config_language_id');
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE parent_id='0' AND status='0' ORDER BY rang");
		foreach ($query->rows as $row) {
			$icon = false;
			if($row['icon'] != '') {
				if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
					$link = $this->config->get('config_ssl') . 'image/' . $row['icon'];
				} else {
					$link = $this->config->get('config_url') . 'image/' . $row['icon'];
				}
				$icon = '<img src="'.$link.'" alt="">';
			}
			$description = false;
			$description_array = unserialize($row['description']);
			if(isset($description_array[$lang_id])) {
				if(!empty($description_array[$lang_id])) {
					$icon = $icon.'<div class="description-left">';
					$description = '<br><span class="description">'.$description_array[$lang_id].'</span></div>';
				}
			}
			$output[] = array(
				'icon' => $icon,
				'name' => unserialize($row['name']),
				'link' => $row['link'],
				'description' => $description,
				'new_window' => $row['new_window'],
				'position' => $row['position'],
				'submenu_width' => $row['submenu_width'],
				'submenu_type' => $row['submenu_type'],
				'submenu' => $this->getSubmenu($row['id'])
			);
		}
		return $output;
	}

	public function getSubmenu($id) {
		global $loader, $registry;
		$output = array();
		$lang_id = $this->config->get('config_language_id');
		
		// Product model
		$loader->model('catalog/product');
		$model = $registry->get('model_catalog_product');
		
		// Tool model
		$loader->model('tool/image');
		$model_image = $registry->get('model_tool_image');
				
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE parent_id='".$id."' AND status='0' ORDER BY rang");
		foreach ($query->rows as $row) {
			$content = unserialize($row['content']);
			if(isset($content['html']['text'][$lang_id])) {
				$html = htmlspecialchars_decode($content['html']['text'][$lang_id]);
			} else {
				$html = false;
			}
			
			if(isset($content['categories'])) {
				if(is_array($content['categories'])) {
					$categories = $this->getCategories($content['categories']);
				} else {
					$categories = false;
				}
			} else {
				$categories = false;
			}
			
			if(isset($content['product']['id'])) {
				$product = $model->getProduct($content['product']['id']);
				if(is_array($product)) {
					$product_link = $this->url->link('product/product', 'product_id=' . $content['product']['id']);
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}
					
					if ((float)$product['special']) {
						$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}
				} else {
					$product = false;
					$product_link = false;
					$price = false;
					$special = false;
				}
			} else {	
				$product = false;
				$product_link = false;
				$price = false;
				$special = false;
			}

			if(isset($product['image'])) {
				if(!isset($content['product']['width'])) $content['product']['width'] = 400;
				if(!isset($content['product']['height'])) $content['product']['height'] = 400;
				if($content['product']['width'] < 1) $content['product']['width'] = 400;
				if($content['product']['height'] < 1) $content['product']['height'] = 400;
				$product_image = $model_image->resize($product['image'], $content['product']['width'], $content['product']['height']);
			} else {	
				$product_image = false;
			}
			$output[] = array(
				'content_width' => intval($row['content_width']),
				'content_type' => $row['content_type'],
				'html' => $html,
				'product' => array(
					'name' => $product['name'],
					'link' => $product_link,
					'image' => $product_image,
					'price' => $price,
					'special' => $special
				),
				'categories' => $categories,
				'submenu' => $this->getSubmenu($row['id'])
			);
		}
		return $output;
	}
	
	public function getCategories($array = array()) {
		global $loader, $registry;
		
		$output = false;
		
		// Category model
		$loader->model('catalog/category');
		$model = $registry->get('model_catalog_category');
		
		$output .= '<div class="row">';
			$row_fluid = 12;
			if($array['columns'] == 2) { $row_fluid = 6; }
			if($array['columns'] == 3) { $row_fluid = 4; }
			if($array['columns'] == 4) { $row_fluid = 3; }
			if($array['columns'] == 5) { $row_fluid = 25; }
			if($array['columns'] == 6) { $row_fluid = 2; }
			if(!($array['columns'] > 0 && $array['columns'] < 7)) { $array['columns'] = 1; }
			$menu_class = 'hover-menu';
			if($array['submenu'] == 2) { $menu_class = 'static-menu'; }
			
			for ($i = 0; $i < count($array['categories']);) {
				$output .= '<div class="col-sm-'.$row_fluid.' '.$menu_class.'">';
					$output .= '<div class="menu">';
						$output .= '<ul>';
							$j = $i + ceil(count($array['categories']) / $array['columns']);
							for (; $i < $j; $i++) { 
								if(isset($array['categories'][$i]['id'])) {
									$info_category = $model->getCategory($array['categories'][$i]['id']);
									if(isset($info_category['category_id'])) {
										$path = '';
										
										if($info_category['parent_id'] > 0) {
											$path = $info_category['parent_id'];
											$info_category2 = $model->getCategory($info_category['parent_id']);
											if($info_category2['parent_id'] > 0) {
												$path = $info_category2['parent_id'] . '_' . $path;
												$info_category3 = $model->getCategory($info_category2['parent_id']);
												if($info_category3['parent_id'] > 0) {
													$path = $info_category3['parent_id'] . '_' . $path;
												}
											}
										}
										
										if($path != '') {
											$path = $path . '_';
										}
										if(is_array($info_category)) {
											$link = $this->url->link('product/category', 'path=' . $path . $info_category['category_id']);
											$output .= '<li><a href="'.$link.'" onclick="window.location = \''.$link.'\';" class="main-menu">'.$info_category['name'].'</a>';
												if(isset($array['categories'][$i]['children'])) {
													if(!empty($array['categories'][$i]['children'])) {
														$output .= $this->getCategoriesChildren($array['categories'][$i]['children'], $info_category['category_id'], $array['submenu_columns'], $array['submenu']);
													}
												}
											$output .= '</li>';
										}
									}
								}
							}
						$output .= '</ul>';
					$output .= '</div>';
				$output .= '</div>';
			}
		$output .= '</div>';
		return $output;
	}
	
	public function getCategoriesChildren($array = array(), $path, $columns, $type, $submenu = false) {
		global $loader, $registry;
		
		$output = false;
		
		// Category model
		$loader->model('catalog/category');
		$model = $registry->get('model_catalog_category');
				
		if($type == 2) {
			$row_fluid = 12;
			if($columns == 2) { $row_fluid = 6; }
			if($columns == 3) { $row_fluid = 4; }
			if($columns == 4) { $row_fluid = 3; }
			if($columns == 5) { $row_fluid = 25; }
			if($columns == 6) { $row_fluid = 2; }
			if(!($columns > 0 && $columns < 7)) { $columns = 1; }
			if($submenu == true) { $columns = 1; $row_fluid = 12; }
			
			if($columns != 1) {
				$output .= '<div class="row visible">';
			}
				for ($i = 0; $i < count($array);) {
					if($columns != 1) {
						$output .= '<div class="col-sm-'.$row_fluid.'">';
					}
						$output .= '<ul>';
							$j = $i + ceil(count($array) / $columns);
							for (; $i < $j; $i++) { 
								if(isset($array[$i]['id'])) {
									$info_category = $model->getCategory($array[$i]['id']);
									if(isset($info_category['category_id'])) {
										$path = '';
										
										if($info_category['parent_id'] > 0) {
											$path = $info_category['parent_id'];
											$info_category2 = $model->getCategory($info_category['parent_id']);
											if($info_category2['parent_id'] > 0) {
												$path = $info_category2['parent_id'] . '_' . $path;
												$info_category3 = $model->getCategory($info_category2['parent_id']);
												if($info_category3['parent_id'] > 0) {
													$path = $info_category3['parent_id'] . '_' . $path;
												}
											}
										}
										
										if($path != '') {
											$path = $path . '_';
										}
										if(is_array($info_category)) {
											$link = $this->url->link('product/category', 'path=' . $path . $info_category['category_id']);
											$output .= '<li><a href="'.$link.'" onclick="window.location = \''.$link.'\';">'.$info_category['name'].'</a>';
												if(isset($array[$i]['children'])) {
													if(!empty($array[$i]['children'])) {
														$output .= $this->getCategoriesChildren($array[$i]['children'], $path.'_'.$info_category['category_id'], $columns, $type, true);
													}
												}
											$output .= '</li>';
										}
									}
								}
							}
						$output .= '</ul>';
					if($columns != 1) {
						$output .= '</div>';
					}
				}
			if($columns != 1) {
				$output .= '</div>';
			}
		} else {
			$output .= '<ul>';
				foreach($array as $row) {
					$info_category = $model->getCategory($row['id']);
					if(isset($info_category['category_id'])) {
						$path = '';
						
						if($info_category['parent_id'] > 0) {
							$path = $info_category['parent_id'];
							$info_category2 = $model->getCategory($info_category['parent_id']);
							if($info_category2['parent_id'] > 0) {
								$path = $info_category2['parent_id'] . '_' . $path;
								$info_category3 = $model->getCategory($info_category2['parent_id']);
								if($info_category3['parent_id'] > 0) {
									$path = $info_category3['parent_id'] . '_' . $path;
								}
							}
						}
						
						if($path != '') {
							$path = $path . '_';
						}
						
						$link = $this->url->link('product/category', 'path=' . $path . $info_category['category_id']);
						$output .= '<li><a href="'.$link.'" onclick="window.location = \''.$link.'\';">'.$info_category['name'].'</a>';
							if(isset($row['children'])) {
								if(!empty($row['children'])) {
									$output .= $this->getCategoriesChildren($row['children'], $path.'_'.$info_category['category_id'], $columns, $type);
								}
							}
						$output .= '</li>';
					}
				}
			$output .= '</ul>';
		}
		return $output;
	}
}
?>