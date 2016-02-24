<div id="div-mailchimp-<?=$module_index?>">
	<div class="mailchimp-form-inner">
		<?php if(!empty($heading_title)){ ?>
		<h3><?php echo $heading_title; ?></h3>
		<?php } ?>
		<p><?php echo $html; ?></p>
		<form class="newsletter-frm" id="mailchimp-frm-<?=$module_index?>" method="post" action="#" onsubmit="return false;">
			<input type="hidden" name="list_id" id="list_id" value="<?=$list_id?>" /> 
			<?php if(isset($md5code)){ ?>
			<input type="hidden" name="md5code" value="<?=$md5code?>" /> 
			<?php } ?>
			<?php 
				function sp_cmp($a, $b){
				    if ($a['order'] == $b['order']) {
				        return 0;
				    }
				    return ($a['order'] < $b['order']) ? -1 : 1;
				}

				usort($setting['list_fields'], "sp_cmp");

				foreach ($setting['list_fields'] as $list_field) { 
					if(!isset($list_field['tag'])){continue;}
					
					echo '<div class="form-group"><input name="',$list_field['tag'],'" id="',$list_field['tag'],'" type="text" class="form-control" value="" placeholder="',$list_field[$language_id]['name'],'" /></div>';	
				} 
			?>
		    <div class="form-group">
		        <button type="submit" class="btn-send btn btn-primary">
		        <?php echo (!empty($button_text)?$button_text:$text_submit); ?>
		        </button>
		    </div>
		</form>
	</div>
</div>
<?php if(isset($popup) && intval($popup) == 1){ ?>
<style type="text/css">
#div-mailchimp-<?=$module_index?>{
	background: none repeat scroll 0 0 #fff;
	border: 5px solid rgba(0, 0, 0, 0.3);
	box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
	left: 37%;
	padding: 30px;
	position: fixed;
	top: 20%;
	width: 420px;
	z-index: 800;
	display: none;
}	
.mailchimp-form-inner{
	position: relative;
}
.mcgray-bg{
	background-color: #000;
	height: 100%;
	left: 0;
	opacity: 0.75;
	position: fixed;
	top: 0;
	width: 100%;
	z-index: 700;
}
.mcf-close-btn{
	background: none repeat scroll 0 0 #23a1d1;
	border: 1px solid #23a1d1;
	border-radius: 50%;
	display: inline-block;
	height: 31px;
	line-height: 31px;
	position: absolute;
	right: -15px;
	text-align: center;
	top: -15px;
	width: 31px;
	color: #fff;
}
</style>
<script type="text/javascript">
$(function(){
	if(!$('.mcgray-bg').length){
		$('body').append('<div class="mcgray-bg"></div>');		
	}

	$('#div-mailchimp-<?=$module_index?>').slideDown(400);

	$('.mcgray-bg').bind('click', function(){
		$('#div-mailchimp-<?=$module_index?>, .mcgray-bg').hide();
	});

	$('#div-mailchimp-<?=$module_index?>').prepend('<a class="mcf-close-btn" href="#" onclick="$(\'.mcgray-bg\').trigger(\'click\'); return false;"><i class="fa fa-times"></i></a>')

	var window_width = $(window).width();
	if(window_width > 420){
		var left = parseInt((window_width - 420)/2, 10);
		$('#div-mailchimp-<?=$module_index?>').css('left',left+'px');
	} else {
		$('#div-mailchimp-<?=$module_index?>').css('left','0px').css('width','100%');
	}

	$(window).resize(function(event) {
		var window_width = $(window).width();
		if(window_width > 420){
			var left = parseInt((window_width - 420)/2, 10);
			$('#div-mailchimp-<?=$module_index?>').css('left',left+'px');
		} else {
			$('#div-mailchimp-<?=$module_index?>').css('left','0px').css('width','100%');
		}
	});
});
</script>
<?php } ?>
<script type="text/javascript">
$(function(){
	$('#mailchimp-frm-<?=$module_index?> input').bind('blur', function(){
		if($.trim($(this).val()).length == 0){
			$(this).val($(this).attr('placeholder'));
		}
	}).bind('focus', function(){
		if($.trim($(this).val()) == $(this).attr('placeholder')){
			$(this).val('');
		}
	});

	$('#mailchimp-frm-<?=$module_index?> .btn-send').bind('click', function(){
		if(!$('#mailchimp-frm-<?=$module_index?> .btn-send').hasClass('sending')){
			submitNewsletter();
		}
	});
});

function submitNewsletter(){
	$.ajax({
		type:'POST',
        async: true,
        url: 'index.php?route=module/mailchimp/add2list',
        data: $('#mailchimp-frm-<?=$module_index?>').serialize(),
        dataType: 'json',
        beforeSend: function() {
          	$('#mailchimp-frm-<?=$module_index?> .btn-send').addClass('sending').prepend('<i class="fa fa-spinner fa-spin"></i> ');
        },      
        success: function(json) { 
        	$('#mailchimp-frm-<?=$module_index?> .btn-send').removeClass('sending');
        	$('#mailchimp-frm-<?=$module_index?> .btn-send .fa').remove();

        	$('#mailchimp-frm-<?=$module_index?> .alert').remove();
        	if(typeof(json.email)!='undefined' && json.email == $.trim($('#mailchimp-frm-<?=$module_index?> #EMAIL').val())){
        		$('#mailchimp-frm-<?=$module_index?>').prepend('<div class="alert alert-success"><a class="close" onclick="$(\'.alert\').hide()">&times;</a>  <?=$success_message?></div>');

        		$('#mailchimp-frm-<?=$module_index?> input').val('');
        	} else if(typeof(json.error)!='undefined') {
        		$('#mailchimp-frm-<?=$module_index?>').prepend('<div class="alert alert-danger"><a class="close" onclick="$(\'.alert\').hide()">&times;</a>'+json.error+'</div>');
        	}

        },
        error: function(xhr, ajaxOptions, thrownError) {
          	alert("Something went wrong, please try again!")
        }
    });
}
</script>
