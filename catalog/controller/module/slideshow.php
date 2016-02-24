<?php
class ControllerModuleSlideshow extends Controller {
  public function index($setting) {
    static $module = 0;

    $this->load->model('design/banner');
    $this->load->model('tool/image');

    $this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
    $this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

    $data['banners'] = array();

    $results = $this->model_design_banner->getBanner($setting['banner_id']);

    foreach ($results as $result) {
      if (is_file(DIR_IMAGE . $result['image'])) {
        $srcset = array();
        $srcset[] = 'image/' . $result['image'] . ' 978w'; // -lg
//        if (is_file(DIR_IMAGE . str_replace('-lg', '-lg@2x', $result['image']))) {
//          $srcset[] = 'image/' . str_replace('-lg', '-lg@2x', $result['image']) . ' 978w 2x'; // -lg@2x
//        }
        if (is_file(DIR_IMAGE . str_replace('-lg', '-sm@2x',    $result['image']))) {
          $srcset[] = 'image/' . str_replace('-lg', '-sm@2x',    $result['image']) . ' 767w'; // -sm@2x
        }

        $data['banners'][] = array(
          'title'   => $result['title'],
          'link'    => $result['link'],
          'image'   => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),
          'srcset'  => implode(', ', $srcset)
        );
      }
    }

    $data['module'] = $module++;

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/slideshow.tpl')) {
      return $this->load->view($this->config->get('config_template') . '/template/module/slideshow.tpl', $data);
    } else {
      return $this->load->view('default/template/module/slideshow.tpl', $data);
    }
  }
}
