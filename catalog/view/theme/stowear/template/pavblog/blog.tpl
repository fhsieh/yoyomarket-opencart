<?php echo $header; 
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config'); 
include('catalog/view/theme/'.$config->get('config_template').'/template/new_elements/wrapper_top.tpl'); ?>

<div class="post-item-panel" style="padding-top:7px">
	<ul class="clearfix">
		<?php if( $config->get('blog_show_created') ) { ?>
		<li class="date"><p><i class="icon-calendar"></i><?php echo date("M",strtotime($blog['created']));?> <?php echo date("d",strtotime($blog['created']));?>, <?php echo date("Y",strtotime($blog['created']));?></p></li>
		<?php } ?>
		
		<?php if( $config->get('blog_show_author') ) { ?>
		<li><p><i class="icon-user"></i><?php echo $this->language->get("text_write_by");?> <?php echo $blog['author'];?></p></li>
		<?php } ?>
		
		<?php if( $config->get('blog_show_category') ) { ?>
		<li><p><i class="icon-folder-open"></i><?php echo $this->language->get("text_published_in");?> <a href="<?php echo $blog['category_link'];?>" title="<?php echo $blog['category_title'];?>"><?php echo $blog['category_title'];?></a></p></li>
		<?php } ?>
		
		<?php if( $config->get('blog_show_hits') ) { ?>
		<li><p><i class="icon-paper-clip"></i><span><?php echo $this->language->get("text_hits");?> <?php echo $blog['hits'];?></span></p></li>
		<?php } ?>
		
		<?php if( $config->get('blog_show_comment_counter') ) { ?>
		<li><p><i class="icon-comments"></i><span><?php echo $this->language->get("text_comment_count");?> <?php echo $blog['comment_count'];?></span></p></li>
		<?php } ?>
	</ul>
</div>

<div class="clearfix post-content">
	<?php if( $blog['thumb_large'] ) { ?>
	<div class="blog-post-img">
		<img src="<?php echo $blog['thumb_large'];?>" title="<?php echo $blog['title'];?>" />
	</div>
	<?php } ?>
	
	<div class="post-text">
		<?php echo $description; ?>
		<?php echo $content;?>
		<?php if( $blog['video_code'] ) { ?>
		<div class="clearfix" style="padding:0px 0px 10px 0px"><?php echo html_entity_decode($blog['video_code'], ENT_QUOTES, 'UTF-8');?></div>
		<?php } ?>
	</div>
	
	 <div class="blog-social clearfix">
			
			<div class="social-wrap clearfix" style="padding: 5px 0px">
				<div class="social-heading"><b><?php echo $this->language->get('text_like_this');?> </b> </div>
				
				<!-- Twitter Button -->
				<div class="itemTwitterButton">
					<a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal"><?php echo $this->language->get('text_twitter_share'); ?></a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
				</div>
	
				<!-- Facebook Button -->
				<div class="itemFacebookButton">
					<div id="fb-root"></div>
					<script type="text/javascript">
						(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) {return;}
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_US/all.js#appId=177111755694317&xfbml=1";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					</script>
					<div class="fb-like" data-send="false" data-width="200" data-show-faces="true"></div>
				</div>
				<!-- Google +1 Button -->
				<div class="itemGooglePlusOneButton">	
					<g:plusone annotation="inline" width="120"></g:plusone>
					<script type="text/javascript">
					  (function() {
						window.___gcfg = {lang: 'en'}; // Define button default language here
						var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
						po.src = 'https://apis.google.com/js/plusone.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
					  })();
					</script>
				</div>
			</div>	
	 </div>
	 
	<?php if( !empty($tags) ) { ?>
	<div class="tags">
		<?php echo $this->language->get('text_tags');?>
		<ul>
			<?php foreach( $tags as $tag => $tagLink ) { ?>
				<li><a href="<?php echo $tagLink; ?>" title="<?php echo $tag; ?>"><?php echo $tag; ?></a></li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
	
	<div class="post-bottom row">
		<?php if( !empty($samecategory) ) { ?>
		<div class="col-sm-6">
			<h4><?php echo $this->language->get('text_in_same_category');?></h4>
			<ul>
				<?php foreach( $samecategory as $item ) { ?>
				<li><a href="<?php echo $this->url->link('pavblog/blog',"id=".$item['blog_id']);?>"><?php echo $item['title'];?></a></li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
		
		<?php if( !empty($related) ) { ?>
		<div class="col-sm-6">
			<h4><?php echo $this->language->get('text_in_related_by_tag');?></h4>
			<ul>
				<?php foreach( $related as $item ) { ?>
				<li><a href="<?php echo $this->url->link('pavblog/blog',"id=".$item['blog_id']);?>"><?php echo $item['title'];?></a></li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div>
</div>

	<?php if( $config->get('blog_show_comment_form') ) { ?>
		<?php if( $config->get('comment_engine') == 'diquis' ) { ?>
			<div id="disqus_thread"></div>
			<script type="text/javascript">
								/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
								var disqus_shortname = '<?php echo $config->get('diquis_account');?>'; // required: replace example with your forum shortname
		
								/* * * DON'T EDIT BELOW THIS LINE * * */
								(function() {
									var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
									dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
									(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
								})();
			</script>
			<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
			<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
			<div style="height:20px"></div>
		<?php } elseif( $config->get('comment_engine') == 'facebook' ) { ?>
			<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) {return;}
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $config->get("facebook_appid");?>";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-comments" data-href="<?php echo $link; ?>" 
						data-num-posts="<?php echo $config->get("comment_limit");?>" data-width="<?php echo $config->get("facebook_width")?>">
				</div>
			<div style="height:20px"></div>
		<?php } else { ?>
			<?php if( count($comments) ) { ?>
			<!-- Comments -->
			<div class="box">
				<h4 class="box-heading"><?php echo $this->language->get('text_list_comments'); ?>: <?php echo count($comments); ?></h4>
				<div class="strip-line"></div>
				<div class="box-content">
					<div class="comments">
						<?php foreach( $comments as $comment ) { $default=''; ?>
							<!-- Comment -->
							<div class="comment" id="comment<?php echo $comment['comment_id'];?>">
								<div class="avatar"><img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $comment['email'] ) ) ) . "?d=" . urlencode( $default ) . "&s=60" ?>" alt=""></div>
								<div class="comment-entry">
									<div class="comment-author clearfix">
										<strong><?php echo $comment['user'];?></strong>
										<span class="date"><?php echo $comment['created'];?></span>
										<span class="reply"><a href="<?php echo $link;?>#comment<?php echo $comment['comment_id'];?>"><?php echo $this->language->get('text_comment_link');?></a></span>
									</div>
									<p><?php echo $comment['comment'];?></p>
								</div>
							</div>
						<?php } ?>
					</div>
					
					<div class="pagination">
						<?php echo $pagination;?>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<div class="box">
				<h4 class="box-heading"><?php echo $this->language->get("text_leave_a_comment");?></h4>
				<div class="strip-line"></div>
				<div class="box-content" style="padding-bottom:20px">
					<form action="<?php echo $comment_action;?>" method="post" id="comment-add">
						<div class="warning" style="display:none"></div>
						<div class="input-group">
							<label for="comment-user"><?php echo $this->language->get('entry_name');?></label>
							<input type="text" name="comment[user]" value="" id="comment-user"/>
						</div>
						<div class="input-group">
							<label for="comment-email"><?php echo $this->language->get('entry_email');?></label>
							<input type="text" name="comment[email]" value="" id="comment-email"/>
						</div>
						<div class="input-group">
							<label for="comment-comment"><?php echo $this->language->get('entry_comment');?></label>
							<textarea name="comment[comment]"  id="comment-comment"></textarea>
						</div>
						<?php if( $config->get('enable_recaptcha') ) { ?>
						<div class="recaptcha">
							 <p><b><?php echo $entry_captcha; ?></b> </p>
							
						  
						    <img src="index.php?route=pavblog/blog/captcha" alt="" aligh="left"/>
						    <input type="text" name="captcha" value="<?php echo $captcha; ?>" size="10" />
						</div>
						<?php } ?>
						<input type="hidden" name="comment[blog_id]" value="<?php echo $blog['blog_id']; ?>" />
						<br/>
						<div class="buttons-wrap">
							<button class="button" type="submit">
								<span><?php echo $this->language->get('text_submit');?></span>
							</button>
						</div>
					</form>
				</div>
			</div>
			<script type="text/javascript">
				$( "#comment-add .message" ).hide();
				$("#comment-add").submit( function(){
					$.ajax( {type: "POST",url:$("#comment-add").attr("action"),data:$("#comment-add").serialize(), dataType: "json",}).done( function( data ){
						if( data.hasError ){
							$( "#comment-add .warning" ).html( data.message ).show();	
						}else {
							location.href='<?php echo str_replace("&amp;","&",$link);?>';
						}
					} );
					return false;
				} );
				
			</script>
		<?php } ?>
	<?php } ?>

<?php include('catalog/view/theme/'.$config->get('config_template').'/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>