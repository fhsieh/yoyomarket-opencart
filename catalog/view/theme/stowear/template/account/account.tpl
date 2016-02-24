<?php echo $header;
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config');
include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_top.tpl'); ?>

<div class="row"><?php /* yym_custom */ ?>
  <div class="col-sm-4 col-xs-6 text-center">
    <a href="<?php echo $edit; ?>">
      <img src="/image/catalog/information/account-edit.png" srcset="/image/catalog/information/account-edit.png 1x, /image/catalog/information/account-edit@2x.png 2x" alt="<?php echo $text_edit; ?>">
      <p><strong><?php echo $text_edit; ?></strong></p>
    </a>
  </div>
  <div class="col-sm-4 col-xs-6 text-center">
    <a href="<?php echo $password; ?>">
      <img src="/image/catalog/information/account-password.png" srcset="/image/catalog/information/account-password.png 1x, /image/catalog/information/account-password@2x.png 2x" alt="<?php echo $text_password; ?>">
      <p><strong><?php echo $text_password; ?></strong></p>
    </a>
  </div>
  <div class="clearfix visible-xs"></div>
  <div class="col-sm-4 col-xs-6 text-center">
    <a href="<?php echo $address; ?>">
      <img src="/image/catalog/information/account-address.png" srcset="/image/catalog/information/account-address.png 1x, /image/catalog/information/account-address@2x.png 2x" alt="<?php echo $text_address; ?>">
      <p><strong><?php echo $text_address; ?></strong></p>
    </a>
  </div>
  <div class="col-sm-4 col-xs-6 text-center">
    <a href="<?php echo $order; ?>">
      <img src="/image/catalog/information/account-order.png" srcset="/image/catalog/information/account-order.png 1x, /image/catalog/information/account-order@2x.png 2x" alt="<?php echo $text_order; ?>">
      <p><strong><?php echo $text_order; ?></strong></p>
    </a>
  </div>
  <div class="clearfix visible-xs"></div>
  <div class="col-sm-4 col-xs-6 text-center">
    <a href="<?php echo $wishlist; ?>">
      <img src="/image/catalog/information/account-wishlist.png" srcset="/image/catalog/information/account-wishlist.png 1x, /image/catalog/information/account-wishlist@2x.png 2x" alt="<?php echo $text_wishlist; ?>">
      <p><strong><?php echo $text_wishlist; ?></strong></p>
    </a>
  </div>
  <div class="col-sm-4 col-xs-6 text-center">
    <a href="<?php echo $reward; ?>">
      <img src="/image/catalog/information/account-reward.png" srcset="/image/catalog/information/account-reward.png 1x, /image/catalog/information/account-reward@2x.png 2x" alt="<?php echo $text_reward; ?>">
      <p><strong><?php echo $text_reward; ?></strong></p>
    </a>
  </div>
  <div class="clearfix visible-xs"></div>
  <div class="col-sm-4 col-xs-6 text-center">
    <a href="<?php echo $newsletter; ?>">
      <img src="/image/catalog/information/account-newsletter.png" srcset="/image/catalog/information/account-newsletter.png 1x, /image/catalog/information/account-newsletter@2x.png 2x" alt="<?php echo $text_newsletter; ?>">
      <p><strong><?php echo $text_newsletter; ?></strong></p>
    </a>
  </div>
</div>

<?php /* yym ?>
<h2><?php echo $text_my_account; ?></h2>
<ul class="list-unstyled">
  <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
  <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
  <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
  <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
</ul>

<h2><?php echo $text_my_orders; ?></h2>
<ul class="list-unstyled">
  <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
  <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
  <?php if ($reward) { ?>
  <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
  <?php } ?>
  <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
  <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
  <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
</ul>

<h2><?php echo $text_my_newsletter; ?></h2>
<ul class="list-unstyled">
  <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
</ul>
<?php */ ?>

<?php include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>