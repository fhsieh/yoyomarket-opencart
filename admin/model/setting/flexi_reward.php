<?php /*
name" Default 
flexi_minca" 20 
flexi_reward_status" 1 
flexi_reward_order_status" 5 
flexi_total_choice" 1 
flexi_reward_customer_groups" 1 
flexi_reward_rate" 0.1 
flexi_purchase_rate" 1 


flexi_reg_points" 2 
flexi_reg_points_desc" Registration bonus 
flexi_news_points" 2 
flexi_news_points_desc" Newsletter sign up bonus 
flexi_firsto_op" 1 
flexi_firsto_points" 1 
flexi_firsto_points_desc" First order bonus 
flexi_product_review_points" 1 
flexi_product_review_points_desc" Product review 
flexi_ignore_opt" 0 
flexi_diff_op" 0 
flexi_diff_product[0][from]" 0 
flexi_diff_product[0][to]" 6 
flexi_diff_product[0][points]" 6 
flexi_diff_points_desc[1] 'N different bonus'
flexi_same_op" 1 
flexi_same_product[0][from]" 0 
flexi_same_product[0][to]" 3 
flexi_same_product[0][points]" 5 
flexi_same_points_desc[1]  'N same bonus'
flexi_total_op" 1 
flexi_total_product[0][from]" 0 
flexi_total_product[0][to]" 0 
flexi_total_product[0][points]" 5 
flexi_total_points_desc[1] 'N total bonus'

*/

class ModelSettingFlexiReward extends Model {
	var	$modinstances = array();
	var $rel = array(
			'productreview'=>'text_product_review_points',// $this->language->get('text_product_review_points'),
			'firstorder'=> 'text_firsto_points',//$this->language->get('text_firsto_points'),
   			'diffproduct'=> 'text_diff_products', //$this->language->get('text_diff_products'),
			'sameproduct'=> 'text_same_products',//$this->language->get('text_same_products'),					  
			'totalproduct'=> 'text_total_products'//$this->language->get('text_total_products')
		);
	var $reli = array( 'productreview'=> -3000000,
				      'firstorder'=>-4000000,
					  'diffproduct'=>-5000000,
				      'sameproduct'=>-6000000,					  
				      'totalproduct'=>-7000000
	);
	
/////////////////

//  WARNING --- Risk of endless recursion!!!

// TODO::::   Has to be cleaned up and checked 1 more time

///
	
	// Review
    public function addRewardReview($review_id = '', $product_id = NULL, $customer_id=false, $customer_group_id=false, $product_name ) {
		$flexi_sett = $this->getFlexi($customer_group_id);
		if($flexi_sett['flexi_product_review_points'] == '0') {
		   return false;
		}
		  
		if( $this->getTotalCustomerRewardsByOrderId( ($this->reli['productreview'] - $product_id), $customer_id ) > 0){
		   return false;
		}
        $this->load->model('sale/customer');
        $this->model_sale_customer->addReward($customer_id, $this->rel['productreview'] . ' - '.$product_name, $flexi_sett['flexi_product_review_points'], ($this->reli['productreview'] - $product_id));
		return true;
	}
	
	// First order
    public function addRewardFirstOrder($customer_id=false, $customer_group_id=false,$order_id=false, $order_amount) {
		$flexi_sett = $this->getFlexi($customer_group_id);
		if($flexi_sett['flexi_firsto_points'] == '0') {
		   return false;
		}
		// When we check here order is already set to status complete therefor total will always be 1 event for first order. 
		// TODO:: find a better place to put the check, other than api.
		if( ($this->getTotalOrdersByCustomerId($customer_id,$customer_group_id) -1 ) > 0){
		   return false;
		}
		if( $this->getTotalCustomerRewardsByOrderId( ($this->reli['firstorder']), $customer_id ) > 0){
		   return false;
		}

		
		
		if($flexi_sett['flexi_firsto_op'] > 0) 	{
			$points = $flexi_sett['flexi_firsto_points'];
		} else {
			// We assume we get final order_amount wheter total / sub-total from addReward.
			$points = round($order_amount * $flexi_sett['flexi_firsto_points'] / 100.00);
		}
 
        $this->load->model('sale/customer');
        $this->model_sale_customer->addReward($customer_id, $this->rel['firstorder'] . ' #'.$order_id, $points, ($this->reli['firstorder']));

		return true;
	}
	/*
		Example: user has following products in cart:
		1x product A,
		2x product B option X,
		1x product B option Y,
		2x product C
			Ignore options = NO
			4 different products, [2],[2] same products, 6 total items in cart 	
		1x product A,
		3x product B,
		2x product C
			Ignore options = YES
			3 different products, [3],[2] same products, 6 total items in cart 	

	*/
    public function addRewardQuantity($order_id=false, $customer_id=false, $customer_group_id=false) {	
		$flexi_sett = $this->getFlexi($customer_group_id);

		$diff_count = 0;
		$same_products = array();
		$total_count = 0;	
		$diff_price_sum = 0;
		$total_price_sum = 0;			

		$this->load->model('sale/customer');

		// Handle ignore options - flexi_ignore_opt
		if($flexi_sett['flexi_ignore_opt'] == '1') {
			$row = $this->getOrderProductsOptionInsensitive($order_id);
		
			foreach($row as $key=>$value) {
				$row[$key]['total'] = $row[$key]['ttotal'];
				$row[$key]['quantity'] = $row[$key]['qquantity'];
			}
		} else {
			$row = $this->getOrderProductsOptionSensitive($order_id);
		}

		// Calculate totals
		$diff_count = count($row);
		foreach($row as $key=>$value) {
			$total_count += $row[$key]['quantity'];	
		
			// Diff and total will get the same total price since we sum the column.
			// Diff and total discounts are different because of quantity sum.
			$diff_price_sum += $row[$key]['total'];
			$total_price_sum += $row[$key]['total'];

			if($row[$key]['quantity'] > 1) {
				$same_products[] = $row[$key];
			}
		}
		
	//	var_dump($row,'Diff: '.$diff_count,'Diff sum: '.$diff_price_sum, 'Same: '.$total_price_sum,'Total count: '.$total_count, $same_products);
		
		

		// N different products bonus 
		//================================
		if( $this->getTotalCustomerRewardsByOrderId( ($this->reli['diffproduct'] - $order_id), $customer_id ) > 0){
		   // Do nothing
		} else {
			foreach($flexi_sett['flexi_diff_product'] as $diff){
				if($diff['points'] == '0') continue;
				if($diff_count > $diff['from'] && $diff_count <= $diff['to'] ||  $diff_count > $diff['from'] && $diff['to'] < 1){
					// Calculate points ( + vs % )
					$points = $flexi_sett['flexi_diff_op'] == '1' ? $diff['points'] : round($diff_price_sum * $diff['points'] / 100);
					// Add rewards
					$this->model_sale_customer->addReward($customer_id, $this->rel['diffproduct'].'('.$diff['from'].'< '.$diff_count.' >' .$diff['to'].')', $points, ($this->reli['diffproduct'] - $order_id));
				}
			}
		}


		// N same products bonus 
		//================================
		if( $this->getTotalCustomerRewardsByOrderId( ($this->reli['sameproduct'] - $order_id), $customer_id ) > 0){
		   // Do nothing
		} else {
			foreach($same_products as $s_product ) {
				$same_count = $s_product['quantity'];
				$same_price_sum = $s_product['total'];
				
				foreach($flexi_sett['flexi_same_product'] as $same){
					if($same['points'] == '0') continue;
					if($same_count > $same['from'] && $same_count <= $same['to'] || $same_count > $same['from'] && $same['to'] < 1){
						// Calculate points ( + vs % )
						$points = $flexi_sett['flexi_same_op'] == '1' ? $same['points'] : round($same_price_sum * $same['points'] / 100);
						// Add rewards
						$this->model_sale_customer->addReward($customer_id, $this->rel['sameproduct'].'('.$same['from'].'< '.$same_count.' >' .$same['to'].')', $points, ($this->reli['sameproduct'] - $order_id));
					}
				}
			} // foreach same_products 
		}
		
		// N total products bonus 
		//================================
		if( $this->getTotalCustomerRewardsByOrderId( ($this->reli['totalproduct'] - $order_id), $customer_id ) > 0){
		   // Do nothing
		} else {
			foreach($flexi_sett['flexi_total_product'] as $total){
				if($total['points'] == '0') continue;
				if($total_count > $total['from'] && $total_count <= $total['to'] || $total_count > $total['from'] && $total['to'] < 1){
					// Calculate points ( + vs % )
					$points = $flexi_sett['flexi_total_op'] == '1' ? $total['points'] : round($total_price_sum * $total['points'] / 100);
					// Add rewards
					$this->model_sale_customer->addReward($customer_id, $this->rel['totalproduct'].'('.$total['from'].'< '.$total_count.' >' .$total['to'].')', $points, ($this->reli['totalproduct'] - $order_id));
				}
			}
		}
		

	}
    public function addReward($order_id) {
        $this->language->load('sale/order');
        $this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);
		
		// Get Order amount based on sub-total / total setting 
		$order_amount = $this->getOrderAmount($order_id, $order_info['customer_group_id']);
        $reward_points = 0;
		$getFlexi = $this->getFlexi($order_info['customer_group_id']);

	    if ($getFlexi) {
			// Add Rewards for first order
			$this->addRewardFirstOrder($order_info['customer_id'], $order_info['customer_group_id'],$order_id, $order_amount) ;

			// Add Rewards for Quantity order
			$this->addRewardQuantity($order_id, $order_info['customer_id'], $order_info['customer_group_id']);

            $this->language->load('module/flexi_reward');

            //Checking for already added reward points
            if ($this->checkForAlreadyAddedRewards($order_id, $getFlexi['flexi_reward_order_status'],$order_info['customer_id'])) {
                return $this->language->get('error_reward_added');
            }

            $rates = $getFlexi['flexi_reward_rate'];
			$reward_points = round($order_amount * $rates);
		//	$reward_points = round($cart_subtotal * $rates);
		//	$reward_points = $getFlexi['flexi_total_choice'] == 1 ? round($order_total*$rates) : round($cart_subtotal*$rates);
        }

        if ($reward_points != 0) {

            $this->load->model('sale/customer');
			$reward_total = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($order_id);

			if(!$reward_total ) {
	            $this->model_sale_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $reward_points, $order_id);
   		        return $this->language->get('text_reward_success');
			}
        } else {
            $this->language->load('sale/order');
            return $this->language->get('text_success');
        }
    }
	// Get Order amount based on sub-total / total setting 
	public function getOrderAmount($order_id, $customer_group_id) {

        $this->language->load('sale/order');
        $this->load->model('sale/order');
      
	    $this->language->load('module/flexi_reward');


		if( ($flexi_sett = $this->getFlexi($customer_group_id)) ) {
            $order_total_info = $this->model_sale_order->getOrderTotals($order_id);
            $order_sub_total = 0;
            $order_total = 0;
            $order_coupon_amount = 0;
            $order_voucher_amount = 0;

            foreach ($order_total_info as $order_total) {
                switch ($order_total['code']) {
                    case 'sub_total':
                        $order_sub_total = (int) $order_total['value'];
                        break;

                    case 'coupon':
                        $order_coupon_amount = (int) $order_total['value'];
                        break;

                    case 'voucher':
                        $order_voucher_amount = (int) $order_total['value'];
                        break;
						
					case 'total':
                        $order_total = (int) $order_total['value'];
                        break;
                }
            }
            $cart_subtotal = $order_sub_total + $order_coupon_amount + $order_voucher_amount;
		//	var_dump(($flexi_sett['flexi_total_choice'] == 1) , $flexi_sett['flexi_total_choice'], $order_total , $cart_subtotal);
			return $flexi_sett['flexi_total_choice'] == 1 ? $order_total : $cart_subtotal;
		}
	return 0;
	} // end getOrderAmount
	
	// Calculate and return
	public function calcReward($order_id, $customer_group_id) {

        $this->language->load('sale/order');

        $this->load->model('sale/order');

        $reward_points = 0;

            $this->language->load('module/flexi_reward');

			if($this->getFlexi($customer_group_id)) {

            $order_total_info = $this->model_sale_order->getOrderTotals($order_id);
			
	//		var_dump($order_total_info);
            $order_sub_total = 0;

            $order_total = 0;

            $order_coupon_amount = 0;

            $order_voucher_amount = 0;

            foreach ($order_total_info as $order_total) {
                switch ($order_total['code']) {
                    case 'sub_total':
                        $order_sub_total = (int) $order_total['value'];
                        break;

                    case 'coupon':
                        $order_coupon_amount = (int) $order_total['value'];
                        break;

                    case 'voucher':
                        $order_voucher_amount = (int) $order_total['value'];
                        break;
						
					case 'total':
                        $order_total = (int) $order_total['value'];
                        break;
                }

            }

            $cart_subtotal = $order_sub_total + $order_coupon_amount + $order_voucher_amount;

            $flexi_sett = $this->getFlexi($customer_group_id);

			$rates = $flexi_sett['flexi_reward_rate'];

		return $flexi_sett['flexi_total_choice'] == 1 ? round($order_total*$rates) : round($cart_subtotal*$rates);

		}

	return 0;

	}

    public function checkForAlreadyAddedRewards($order_id, $flexi_reward_order_status,$customer_id) {

        $query = $this->db->query("SELECT COUNT(1) AS total_count FROM " . DB_PREFIX . "customer_reward cr WHERE cr.order_id = '" . (int) $order_id . "' AND cr.customer_id = '". (int) $customer_id . "'");

        if ($query->row['total_count'] > 0) {

            return true;

        } else {

            return false;

        }

    }

	

	public function getModulesByCode($code) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

		return $query->rows;

	}	

public function getModuleInstances($code) {

		$modules = $this->getModulesByCode($code);

		foreach ($modules as $key => $module) {

			$modules[$key]['setting'] = $m = unserialize($module['setting']);

			if($modules[$key]['setting'][$code.'_status'] != 1) unset($modules[$key]);

		}

		$this->modinstances[$code] = $modules;

		return $modules;

	}

		

	public function getFlexi($customer_group) {

		$code = 'flexi_reward';

		$instances = isset($this->modinstances[$code]) ? $this->modinstances[$code] : $this->getModuleInstances($code); 
		// TODO:::: 
		// Replace the text on front end so that each user sees his order in his language.
		$this->load->language('module/flexi_reward');
	/*	$this->rel = array(
			'productreview'=>'text_product_review_points',// $this->language->get('text_product_review_points'),
			'firstorder'=> 'text_firsto_points',//$this->language->get('text_firsto_points'),
   			'diffproduct'=> 'text_diff_products', //$this->language->get('text_diff_products'),
			'sameproduct'=> 'text_same_products',//$this->language->get('text_same_products'),					  
			'totalproduct'=> 'text_total_products'//$this->language->get('text_total_products')
		);*/
		foreach ($instances as $module) {
			if($module['setting']['flexi_reward_customer_groups'] == $customer_group) {
				return $module['setting'];
			}
		}
		return false;
	}
	public function getTotalCustomerRewardsByOrderId($order_id, $customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int) $customer_id. "'");

		return $query->row['total'];
	}
	public function getTotalOrdersByCustomerId($customer_id,$customer_group_id) {
		$flexi_sett = $this->getFlexi($customer_group_id);
		$order_status_id = $flexi_sett['flexi_reward_order_status'];
				
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int) $customer_id. "' AND order_status_id='".$order_status_id."'");

		return $query->row['total'];
	}
	// Get products - ignore options in product count but keep option+product price.
	// Product B [ Option A ] + Product B [ Option B ] = 1 products 	
	public function getOrderProductsOptionInsensitive($order_id) {
		$query = $this->db->query("		
		SELECT *, SUM(total) AS ttotal, SUM(quantity) AS qquantity
" . DB_PREFIX . "order_product 
WHERE order_id = '" . (int)$order_id . "'
GROUP BY product_id");
		return $query->rows;
	}
	// Get products - keep options in product count.
	// Product B [ Option A ] + Product B [ Option B ] = 2 products 
	public function getOrderProductsOptionSensitive($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}
	
	// Delete First Order points
	public function deleteRewardFirstOrder($customer_id,$customer_group_id) {	
		if( ($this->getTotalOrdersByCustomerId($customer_id,$customer_group_id) -1 ) > 0){
		   return false;
		}

		$order_id = $this->reli['firstorder'];
		$this->deleteRewards($order_id, $customer_id);
	}
	// Delete Rewards SQL query
	public function deleteRewards($order_id, $customer_id=false) {
		$query = "DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND points > 0 ";
		
		if($customer_id !== false){
			$query .= " AND customer_id = '".$customer_id."'";
		}
		
		$this->db->query($query);
	}

	// Delete Rewards HOOK query
	public function deleteRewardHook($order_id, $event=false, $customer_id=false) {
		if($event && array_key_exists($event,$this->reli)) {
			$order_id = $this->reli[$event];
		}
		$this->deleteRewards($order_id, $customer_id);
	}

}

?>