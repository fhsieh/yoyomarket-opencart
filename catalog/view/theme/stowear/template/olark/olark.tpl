<!-- begin olark code -->
<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
f[z]=function(){
(a.s=a.s||[]).push(arguments)};var a=f[z]._={
},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
0:+new Date};a.P=function(u){
a.p[u]=new Date-a.p[0]};function s(){
a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
b.contentWindow[g].open()}catch(w){
c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('8734-721-10-6403');/*]]>*/</script><noscript><a href="https://www.olark.com/site/8734-721-10-6403/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
<!-- end olark code -->
<script type="text/javascript">
olark.configure("features.prechat_survey", true);
olark.configure('system.ask_for_name', 'required');
olark.configure('system.ask_for_email', 'required');
olark.configure('system.ask_for_phone', 'optional');
olark.configure("locale.welcome_title", "<?php echo $welcome_title; ?>");
olark.configure("locale.chatting_title", "<?php echo $chatting_title; ?>");
olark.configure("locale.unavailable_title", "<?php echo $unavailable_title; ?>");
olark.configure("locale.away_message", "<?php echo $away_message; ?>");
olark.configure("locale.welcome_message", "<?php echo $welcome_message; ?>");
olark.configure("locale.chat_input_text", "<?php echo $chat_input_text; ?>");
olark.configure("locale.name_input_text", "<?php echo $name_input_text; ?>");
olark.configure("locale.email_input_text", "<?php echo $email_input_text; ?>");
olark.configure("locale.phone_input_text", "<?php echo $phone_input_text; ?>");
olark.configure("locale.offline_note_message", "<?php echo $offline_note_message; ?>");
olark.configure("locale.send_button_text", "<?php echo $send_button_text; ?>");
olark.configure("locale.offline_note_thankyou_text", "<?php echo $offline_note_thankyou_text; ?>");
olark.configure("locale.offline_note_error_text", "<?php echo $offline_note_error_text; ?>");
olark.configure("locale.offline_note_sending_text", "<?php echo $offline_note_sending_text; ?>");
olark.configure("locale.operator_is_typing_text", "<?php echo $operator_is_typing_text; ?>");
olark.configure("locale.operator_has_stopped_typing_text", "<?php echo $operator_has_stopped_typing_text; ?>");
olark.configure("locale.introduction_error_text", "<?php echo $introduction_error_text; ?>");
olark.configure("locale.introduction_messages", "<?php echo $introduction_messages; ?>");
olark.configure("locale.introduction_submit_button_text", "<?php echo $introduction_submit_button_text; ?>");
olark.configure("locale.disabled_input_text_when_convo_has_ended", "<?php echo $disabled_input_text_when_convo_has_ended; ?>");
olark.configure("locale.disabled_panel_text_when_convo_has_ended", "<?php echo $disabled_panel_text_when_convo_has_ended; ?>");
olark.configure("locale.ended_chat_message", "<?php echo $ended_chat_message; ?>");
<?php if ($logged) { ?>
olark('api.visitor.updateFullName', { fullName: '<?php echo $name; ?>' });
olark('api.visitor.updateEmailAddress', { emailAddress: '<?php echo $email; ?>' });
olark('api.visitor.updatePhoneNumber', { phoneNumber: '<?php echo $telephone; ?>' });
<?php } ?>
olark('api.chat.updateVisitorStatus', { snippet: [<?php echo $status; ?>, <?php echo $cart; ?>] });
<?php if (isset($checkout) && $checkout) { ?>
olark('api.chat.updateVisitorNickname', { snippet: 'Checking out' });
<?php } else { ?>
olark('api.chat.updateVisitorNickname', { snippet: '' });
<?php } ?>
<?php if (isset($expand) && $expand) { ?>
olark('api.box.expand');
<?php } ?>
</script>