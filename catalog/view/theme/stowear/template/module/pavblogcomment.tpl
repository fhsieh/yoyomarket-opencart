<div class="box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="strip-line"></div>
	<div class="box-content" >
		<?php if( !empty($comments) ) { ?>
		<div class="blog-comments clearfix">
			 <?php $default=''; foreach( $comments as $comment ) { ?>
				<div class="pav-comment clearfix">
					<a href="<?php echo $comment['link'];?>" title="<?php echo $comment['user'];?>">
					<img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $comment['email'] ) ) ) . "?d=" . urlencode( $default ) . "&s=60" ?>" align="left"/>
					</a>
					<div class="comment"><span class="comment-author"><?php echo $comment['user'];?></span><br><?php echo utf8_substr( $comment['comment'], 50 ); ?></div>
				</div>
			 <?php } ?>
		</div>
		<?php } ?>
	</div>
 </div>
