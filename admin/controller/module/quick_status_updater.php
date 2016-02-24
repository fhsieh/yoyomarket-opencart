<?php
class ControllerModuleQuickStatusUpdater extends Controller {
	private $error = array();
	private $OC_V2;
	private $OC_VERSION;

	public function __construct($registry) {
		$this->OC_V2 = substr(VERSION, 0, 1) == 2;
		$this->OC_VERSION = substr(str_replace('.','',VERSION), 0, 3);
		parent::__construct($registry);
	}

	public function index() {
		$asset_path = 'view/quick_status_updater/';
    defined('_JEXEC') && $asset_path = 'admin/' . $asset_path;
		$data['_language'] = $this->language;
    $data['_img_path'] = $asset_path . 'img/';
		$data['_config'] = $this->config;
		$data['_url'] = $this->url;
		$data['token'] = $this->session->data['token'];
		$data['OC_V2'] = $this->OC_V2;

    // tables
		$this->db_tables();

		if (!$this->OC_V2) {
			$this->document->addStyle($asset_path . 'awesome/css/font-awesome.min.css');
			$this->document->addStyle($asset_path . 'bootstrap.min.css');
			$this->document->addStyle($asset_path . 'bootstrap-theme.min.css');
			$this->document->addScript($asset_path . 'bootstrap.min.js');
		}

		$this->document->addScript($asset_path . 'itoggle.js');
		$this->document->addScript($asset_path . 'spectrum.js');
		$this->document->addScript($asset_path . 'sortable.min.js');
		$this->document->addStyle($asset_path . 'spectrum.css');
		$this->document->addStyle($asset_path . 'style.css');

		$this->load->language('module/quick_status_updater');
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		$this->load->model('setting/setting');

		// get languages
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
      foreach ($this->request->post['qosu_order_statuses'] as $k => $v) {
        if (isset($v['color'])) {
          $this->request->post['qosu_order_statuses'][$k]['color'] = '#' . ltrim($v['color'], '#');
        }
      }

			$this->model_setting_setting->editSetting('qosu', $this->request->post);
			$this->cache->delete('order_status.' . (int)$this->config->get('config_language_id'));
			$this->session->data['success'] = $this->language->get('text_success');

			if ($this->OC_V2) {
				$this->response->redirect($this->url->link('module/quick_status_updater', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->redirect($this->url->link('module/quick_status_updater', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		if (file_exists(DIR_SYSTEM.'../vqmod/xml/quick_status_updater.xml'))
			$data['module_version'] = simplexml_load_file(DIR_SYSTEM.'../vqmod/xml/quick_status_updater.xml')->version;
		else $data['module_version'] = 'unknown';

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else $data['success'] = '';

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else $data['error'] = '';

		$data['heading_title'] = $this->language->get('module_title');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['token'] = $this->session->data['token'];


 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => strip_tags($this->language->get('heading_title')),
			'href'      => $this->url->link('module/quick_status_updater', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('module/quick_status_updater', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		// tab 0
		if (isset($this->request->post['qosu_bg_mode'])) {
			$data['qosu_bg_mode'] = $this->request->post['qosu_bg_mode'];
		} else {
			$data['qosu_bg_mode'] = $this->config->get('qosu_bg_mode');
		}

    if (isset($this->request->post['qosu_notify'])) {
			$data['qosu_notify'] = $this->request->post['qosu_notify'];
		} else {
			$data['qosu_notify'] = $this->config->get('qosu_notify');
		}

    if (isset($this->request->post['qosu_barcode'])) {
			$data['qosu_barcode'] = $this->request->post['qosu_barcode'];
		} else {
			$data['qosu_barcode'] = $this->config->get('qosu_barcode');
		}

    if (isset($this->request->post['qosu_barcode_enabled'])) {
			$data['qosu_barcode_enabled'] = $this->request->post['qosu_barcode_enabled'];
		} else {
			$data['qosu_barcode_enabled'] = $this->config->get('qosu_barcode_enabled');
		}

    if (isset($this->request->post['qosu_extra_info'])) {
			$data['qosu_extra_info'] = $this->request->post['qosu_extra_info'];
		} else {
			$data['qosu_extra_info'] = $this->config->get('qosu_extra_info');
		}

		// tab 1
		$data['qosu_shipping'] = array();

		if (isset($this->request->post['qosu_shipping'])) {
			$data['qosu_shipping'] = $this->request->post['qosu_shipping'];
		} elseif ($this->config->get('qosu_shipping')) {
			$data['qosu_shipping'] = $this->config->get('qosu_shipping');
		}

		// tab 2
		$this->load->model('localisation/order_status');
		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();
		/* handled directly in model_localisation_order_status
		$qosu_os = $this->config->get('qosu_order_statuses');

		if($qosu_os) {
			$data['order_statuses'] = array();

			foreach($order_statuses as &$s) {
				if(isset($qosu_os[$s['order_status_id']])) {
					$s = $s + $qosu_os[$s['order_status_id']];
				}
			}

			usort($order_statuses, array($this, 'cmp'));
		}
		*/
		$data['order_statuses'] = $order_statuses;

		// tab 3
		$data['qosu_inputs'] = array();

		if (isset($this->request->post['qosu_inputs'])) {
			$data['qosu_inputs'] = $this->request->post['qosu_inputs'];
		} elseif ($this->config->get('qosu_inputs')) {
			$data['qosu_inputs'] = $this->config->get('qosu_inputs');
		}

		if ($this->OC_V2) {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('module/quick_status_updater.tpl', $data));
		} else {
			$data['column_left'] = '';
			$this->data = &$data;
			$this->template = 'module/quick_status_updater.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}
	}

	public function multiple_form() {
		$data['_language'] = &$this->language;
		$data['_config'] = &$this->config;
		$data['_url'] = &$this->url;
		$data['token'] = $this->session->data['token'];
    $data['OC_V2'] = $this->OC_V2;

		$this->load->language('module/quick_status_updater');


		$data['qosu_os'] = $qosu_os = $this->config->get('qosu_order_statuses');

		if (isset($this->request->get['selected'])) {
			$order_ids = array_unique($this->request->get['selected']);
		} else {
			$order_ids = array_unique($this->request->post['selected']);
		}

		$this->load->model('sale/order');

    $data['order_ids'] = array();

		foreach ($order_ids as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
        $data['order_ids'][] = $order_id;

				$data['order_langs'][$order_info['language_id']] = $order_info['language_id'];

				if (!isset($data['next_status'])) {
          if (isset($data['qosu_os'][ $order_info['order_status_id'] ])) {
            $data['next_status'] = $data['qosu_os'][ $order_info['order_status_id'] ]['next_status'];
          } else {
            $data['next_status'] = 0;
          }
				}

        if ($this->config->get('qosu_extra_info')) {
          $data['extra_info'][$order_id] = html_entity_decode($this->config->get('qosu_extra_info'));
          foreach ($order_info as $k => $v) {
            $data['extra_info'][$order_id] = str_replace('{'.$k.'}', is_string($v) ? $v : '', $data['extra_info'][$order_id]);
          }
        }
			}
		}

    if (!count($data['order_ids'])) {
      echo '<div class="modal-dialog"><div class="modal-content"><div class="modal-body" id="quick-status-dialog">' . $this->language->get('text_qosu_unknown') . implode(', ',  $order_ids) . '</div></div></div>';
      die;
    }

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		// language vars
		$data['text_qosu_add_history'] = $this->language->get('text_qosu_add_history');
		$data['text_qosu_dialog_title'] = $this->language->get('text_qosu_dialog_title');
		$data['text_qosu_tracking_number'] = $this->language->get('text_qosu_tracking_number');
        $data['text_qosu_add_tracking'] = $this->language->get('text_qosu_add_tracking'); // yym_custom
		$data['text_qosu_select_checkbox'] = $this->language->get('text_qosu_select_checkbox');
        $data['text_qosu_tracking_note'] = $this->language->get('text_qosu_tracking_note'); // yym_custom
		$data['text_qosu_order_status'] = $this->language->get('text_qosu_order_status');
		$data['text_qosu_order_id'] = $this->language->get('text_qosu_order_id');
		$data['text_qosu_notify'] = $this->language->get('text_qosu_notify');
		$data['text_qosu_comment'] = $this->language->get('text_qosu_comment');

        // yym_custom: get shipping methods from oc_tracking_method
        $data['tracking_methods'] = $this->db->query("SELECT tm.tracking_method_id, tmd.description FROM " . DB_PREFIX . "tracking_method tm LEFT JOIN " . DB_PREFIX . "tracking_method_description tmd ON (tm.tracking_method_id = tmd.tracking_method_id) WHERE tmd.language_id = " . (int)$this->config->get('config_language_id') . " ORDER BY tm.sort_order ASC")->rows;

		if ($this->OC_V2) {
			$this->response->setOutput($this->load->view('module/quick_status_updater_form.tpl', $data));
		} else {
			$this->data = &$data;
			$this->template = 'module/quick_status_updater_form.tpl';

			$this->response->setOutput($this->render());
		}
	}

	public function update_status() {
		$this->language->load('sale/order');
		$this->load->model('sale/order');

    if ($this->config->get('config_maintenance') && $this->OC_VERSION < 203) {
      echo json_encode(array('error' => '<div class="alert alert-warning"><i class="fa fa-danger"></i> Maintenance mode is active, order modification is not possible during maintenance</div>'));
      die;
    }

    if ($this->OC_V2) {
     // API login
    $this->load->model('user/api');

    $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

    if ($api_info) {
      $curl = curl_init();

      // Set SSL if required
      if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
        curl_setopt($curl, CURLOPT_PORT, 443);
      }

      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLINFO_HEADER_OUT, true);
      curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      if (defined('_JEXEC')) {
        curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?option=com_mijoshop&format=raw&route=api/login');
      } else {
        curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
      }
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

      $json = curl_exec($curl);

      if (!$json) {
        echo json_encode(array('error' => '<div class="alert alert-warning"><i class="fa fa-danger"></i> ' . sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl)) . '</div>'));
        die;
      } else {
        $response = json_decode($json, true);

        if (isset($response['cookie'])) {
          $this->session->data['cookie'] = $response['cookie'];
        }

        curl_close($curl);
      }
    }
    // end - API login

    if (!isset($this->session->data['cookie'])) {
      echo json_encode(array('error' => '<div class="alert alert-danger"><i class="fa fa-danger"></i> Error: API cookie not set, please make sure API is active (System > Users > API)</div>'));
      die;
    }
    }

		$data = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$data['error'] = $this->language->get('error_permission');
			}

			if (!isset($data['error'])) {
				$post_data =  $this->request->post;

				$data['order_id'] = $this->request->post['order_id'];

				foreach ($this->request->post['order_id'] as $order_id) { // FOREACH START
					$order_info = $this->model_sale_order->getOrder($order_id);

					// handle multiple vars
					$post_data['comment'] = $this->request->post['comment'][$order_info['language_id']];
                    /* yym_custom
					if(isset($this->request->post['shipping_method'][$order_id])) {
						$post_data['shipping_method'] = $this->request->post['shipping_method'][$order_id];
					}
					if(isset($this->request->post['tracking_number'][$order_id])) {
						$post_data['tracking_numbers'] = $this->request->post['tracking_numbers'][$order_id];
					}
                    */

          // tag replacement
          if($this->config->get('ordIdMan_rand_ord_num')) {
            $order_info['order_id'] = $order_info['order_id_user'];
          }

          foreach ($order_info as $k => $v) {
            if (!in_array($k, array('tracking_number', 'tracking_method', 'tracking_note'))) { // yym_custom: logic not necessary since columns tracking_no and tracking_url will be removed
              $post_data['comment'] = str_replace('{'.$k.'}', is_string($v) ? $v : '', $post_data['comment']);
            }
          }

          $post_data['comment'] = str_replace('{store_phone}', $this->config->get('config_telephone'), $post_data['comment']);
          $post_data['comment'] = str_replace('{store_email}', $this->config->get('config_email'), $post_data['comment']);

					// custom inputs
					if (isset($post_data['custom_inputs'])) {
						foreach($post_data['custom_inputs'] as $k => $v) {
              $post_data['comment'] = str_replace('{'.$k.'}', $v, $post_data['comment']);
						}
					}


                    /* yym_custom: override qosu tracking with multi-tracking number */

                    // remove if_tracking from email template since tracking info will go in separately.
                    $post_data['comment'] = preg_replace('/{if_tracking}(.*){\/if_tracking}/isU', '', $post_data['comment']);

                    // insert tracking numbers into oc_tracking_number
                    if (isset($post_data['tracking_numbers'][$order_id])) {
                      foreach($post_data['tracking_numbers'][$order_id] as $key => $value) {
                        if ($post_data['tracking_numbers'][$order_id][$key] != '' || $post_data['tracking_methods'][$order_id][$key] != 1 || $post_data['tracking_notes'][$order_id][$key] != '') {
                          $this->db->query("INSERT INTO " . DB_PREFIX . "tracking_number (order_id, tracking_method_id, number, note) VALUES (" . (int)$order_id . "," . (int)$post_data['tracking_methods'][$order_id][$key] . ",'" . $this->db->escape($value) . "','" . $this->db->escape($post_data['tracking_notes'][$order_id][$key]) . "')");
                        }
                      }
                    }



					// tracking number
//          $qosu_shipping = $this->config->get('qosu_shipping');
//          $tracking_url = '';
//          if(isset($post_data['shipping_method']) && isset($qosu_shipping[$post_data['shipping_method']])) {
//            $post_data['comment'] = str_replace('{tracking_url}', $qosu_shipping[$post_data['shipping_method']]['url'], $post_data['comment']);
//            $post_data['comment'] = str_replace('{tracking_title}', $qosu_shipping[$post_data['shipping_method']]['title'], $post_data['comment']);
//            $tracking_url = $qosu_shipping[$post_data['shipping_method']]['url'];
//          }

//          if(isset($post_data['tracking_no']) && $post_data['tracking_no']) {
//            $post_data['comment'] = str_replace(array('{if_tracking}', '{/if_tracking}'), '', $post_data['comment']);
//            $post_data['comment'] = str_replace('{tracking_no}', $post_data['tracking_no'], $post_data['comment']);
//
//            // save tracking url
//            if (strpos($tracking_url, '{tracking_no}') !== false) {
//              $tracking_url = str_replace('{tracking_no}', $post_data['tracking_no'], $tracking_url);
//            } else {
//              $tracking_url .= $post_data['tracking_no'];
//            }
//
//            if ($tracking_url) {
//              $this->db->query("UPDATE `" . DB_PREFIX . "order` SET tracking_no = '" . $this->db->escape($post_data['tracking_no']) . "', tracking_url = '" . $this->db->escape($tracking_url) . "' WHERE order_id = '" . (int)$order_id . "'");
//            }
//          } else {
//            $post_data['comment'] = preg_replace('/{if_tracking}(.*){\/if_tracking}/isU', '', $post_data['comment']);
//          }

          if($this->OC_V2) {
             $res = $this->api('api/order/history&order_id='.$order_id, $post_data);
          } else {
            $this->model_sale_order->addOrderHistory($order_id, $post_data);
          }

					$data['success'] = $this->language->get('text_success');
				}
			}

			$data['bg_mode'] = $this->config->get('qosu_bg_mode');
			$qosu_os = $this->config->get('qosu_order_statuses');
			if (isset($qosu_os[$post_data['order_status_id']]['color']) && $qosu_os[$post_data['order_status_id']]['color'] != '000000') {
				$data['color'] = $qosu_os[$post_data['order_status_id']]['color'];
			} else {
				$data['color'] = false;
			}
		}

		echo json_encode($data);
		die;
	}

	public function getDefaultComment() {
		$qosu_os = $this->config->get('qosu_order_statuses');
    if (isset($qosu_os[$this->request->get['status_id']]['description'])) {
      echo json_encode($qosu_os[$this->request->get['status_id']]['description']);
    }
		die;

		$this->load->model('sale/order');
		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		if ($order_info) {
			if(isset($qosu_os[$this->request->get['status_id']]['description'][$order_info['language_id']]))
				echo $qosu_os[$this->request->get['status_id']]['description'][$order_info['language_id']];
		} else {
			if(isset($qosu_os[$this->request->get['status_id']]['description'][$this->config->get('config_language_id')]))
				echo $qosu_os[$this->request->get['status_id']]['description'][$this->config->get('config_language_id')];
		}
		die;
	}

  public function api($url, $post_data = '') {
		$json = array();

		// Store
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($store_id);

		if ($store_info) {
			$base_url = $store_info['ssl'];
		} else {
			$base_url = HTTPS_CATALOG;
		}

		if (isset($this->session->data['cookie'])) {
			// Include any URL perameters
			$curl = curl_init();

			// Set SSL if required
			if (substr($base_url, 0, 5) == 'https') {
				curl_setopt($curl, CURLOPT_PORT, 443);
			}

			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $base_url . 'index.php?route=' . $url);

			if ($post_data) {
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
			}

      if(defined('_JEXEC')) {
        curl_setopt($curl, CURLOPT_COOKIE, $this->session->data['cookie']['name'].'=' . $this->session->data['cookie']['id'] . ';');
      } else {
        curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');
      }

			$json = curl_exec($curl);

      if (!$json) {
        echo json_encode(array('error' => '<div class="alert alert-danger"><i class="fa fa-danger"></i> ' . sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl)) . '</div>'));
        die;
      }

			curl_close($curl);
		}

		return $json;
	}

	public function modal_info() {
    $this->load->language('module/quick_status_updater');

    $item = $this->request->post['info'];

    $extra_class = $this->language->get('info_css_' . $item) != 'info_css_' . $item ? $this->language->get('info_css_' . $item) : 'modal-lg';
    $title = $this->language->get('info_title_' . $item) != 'info_title_' . $item ? $this->language->get('info_title_' . $item) : $this->language->get('info_title_default');
    $message = $this->language->get('info_msg_' . $item) != 'info_msg_' . $item? $this->language->get('info_msg_' . $item) : $this->language->get('info_msg_default');

    if ($item == 'tags_full') {
      $title = $this->language->get('info_title_tags');
      $message = $this->language->get('info_msg_tags_spec') . $this->language->get('info_msg_tags');
    } elseif ($item == 'extra_info') {
      $message .= $this->language->get('info_msg_tags');
    }

    echo '<div class="modal-dialog ' . $extra_class . '">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-info-circle"></i> ' . $title . '</h4>
        </div>
        <div class="modal-body">' . $message . '</div>
      </div>
    </div>';

    die;
	}

  private function cmp($a, $b) {
		if ($a['sort_order'] == $b['sort_order']) return 0;
		return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
	}

	public function install() {
    // tables
		$this->db_tables();

		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('qosu', array(
				'qosu_notify' => true,
			));

		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'module/quick_status_updater');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'module/quick_status_updater');

		header('Location:index.php?route=extension/module&token=' . $this->session->data['token']);
	}

	public function uninstall() {}

  private function db_tables() {
// yym_custom: remove tracking_no and tracking_url from oc_order
//		if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'tracking_no'")->row)
//			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `tracking_no` VARCHAR(48)");
//    if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'tracking_url'")->row)
//			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `tracking_url` VARCHAR(256)");
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/quick_status_updater')) {
			$this->error['error'] = $this->language->get('error_permission');
		}

		if (!$this->error)
			return true;
		return false;
	}
}
?>