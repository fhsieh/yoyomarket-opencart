<?php
switch($column) {
	case 'attributes': ?>
<table id="qe-attributes" cellpadding="0" cellspacing="0" border="0" class="table table-condensed<?php echo ($aqe_row_hover_highlighting) ? ' table-hover' : ''; ?><?php echo ($aqe_alternate_row_colour) ? ' table-striped' : ''; ?> qe">
	<thead>
		<tr>
			<th class="text-left"><?php echo $entry_attribute; ?></th>
			<th class="text-left"><?php echo $entry_text; ?></th>
			<th width="1"></th>
		</tr>
	</thead>
	<tbody data-bind="foreach: attributes">
		<tr data-bind="css: {'danger': hasError}">
			<td>
				<input type="text" class="form-control attributes typeahead" placeholder="<?php echo $text_autocomplete; ?>" data-bind="value: name, typeahead: { options:{ autoSelect: true }, datasets: $root.ta_dataset}, css: {'has-error': id.hasError}, tooltip: {title: id.errorMsg}" />
				<input type="hidden" data-bind="attr: {name: 'product_attribute[' + $index()  + '][attribute_id]'}, value: id" data-column="<?php echo $column; ?>" />
			</td>
			<td class="multi-lang-values">
				<!-- ko foreach: values -->
				<div class="input-group col-sm-4 pull-left"><!-- yym -->
					<span class="input-group-addon" data-bind="attr: {title: $root.languages[language_id].name}"><img data-bind="attr: {src: $root.languages[language_id].flag}" /></span>
					<textarea class="form-control input-sm" rows="2" data-bind="attr: {name: 'product_attribute[' + $parentContext.$index() + '][value][' + language_id + ']'}, value: value"></textarea>
				</div>
				<!-- /ko -->
			</td>
			<td class="text-vertical-middle"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-bind="click: $parent.removeAttribute, tooltip: {}"><i class="fa fa-minus-circle"></i> <span class="visible-lg-inline"><?php echo $button_remove; ?></span></button></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" class="text-right"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove_all; ?>" data-bind="click: removeAll, disable: attributes().length == 0"><i class="fa fa-trash-o"></i> <span class="visible-lg-inline"><?php echo $button_remove_all; ?></span></button> <button type="button" class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add_attribute; ?>" data-bind="click: addAttribute"><i class="fa fa-plus-circle"></i> <span class="visible-lg-inline"><?php echo $button_add_attribute; ?></span></button></td>
		</tr>
	</tfoot>
</table>
<script type="text/javascript"><!--
!function(e,t){var o=<?php echo json_encode($languages); ?>,a=<?php echo json_encode($product_attributes); ?>,i=new Bloodhound({remote:"<?php echo $typeahead; ?>",datumTokenizer:Bloodhound.tokenizers.obj.whitespace("value"),queryTokenizer:Bloodhound.tokenizers.whitespace,dupDetector:function(e,t){return e.id&&t.id&&e.id==t.id},limit:10});i.initialize();var n=function(e,t,o){this.id=e,this.name=t,this.flag=o},s=function(e,t){this.language_id=t,this.value=ko.observable(e),this.hasError=ko.computed(this.hasError,this)};s.prototype=new e.observable_object_methods;var r=function(e,t,o){var a=this;this.id=ko.observable(e).extend({validate:{context:a}}),this.name=ko.observable(t),this.values=ko.observableArray(ko.utils.arrayMap(o,function(e){return new s(e.value,e.language_id)})).withIndex("language_id").extend({hasError:{check:!0,context:a},applyErrors:{context:a}}),this.hasError=ko.computed(this.hasError,this)};r.prototype=new e.observable_object_methods;var u=function(){var e=this;this.languages={},t.each(o,function(t,o){e.languages[t]=new n(o.language_id,o.name,"view/image/flags/"+o.image)}),this.attributes=ko.observableArray(ko.utils.arrayMap(a,function(e){return new r(e.attribute_id,e.name,e.values)})).extend({hasError:{check:!0,context:e},applyErrors:{context:e}}),this.removeAttribute=function(t){e.attributes.remove(t)},this.addAttribute=function(){var o=[];t.each(e.languages,function(e,t){o.push({value:"",language_id:t.id})}),e.attributes.push(new r(null,"",o))},this.removeAll=function(){e.attributes.removeAll()},this.ta_dataset={name:"<?php echo $column; ?>",source:i.ttAdapter(),templates:{empty:['<div class="tt-no-suggestion"><?php echo addslashes($text_no_records_found); ?></div>'].join("\n"),suggestion:Handlebars.compile(['<p><span class="tt-nowrap">{{value}}<span class="tt-secondary-right">{{group}}</span></span></p>'].join(""))}},this.hasError=ko.computed(this.hasError,this)};u.prototype=new e.observable_object_methods,e.view_models.actionQuickEditVM=new u,ko.applyBindings(e.view_models.actionQuickEditVM,t("#qe-attributes")[0])}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	case 'discounts': ?>
<table id="qe-discounts" cellpadding="0" cellspacing="0" border="0" class="table table-condensed<?php echo ($aqe_row_hover_highlighting) ? ' table-hover' : ''; ?><?php echo ($aqe_alternate_row_colour) ? ' table-striped' : ''; ?> qe">
	<thead>
		<tr>
			<th class="text-left"><?php echo $entry_customer_group; ?></th>
			<th class="text-left quantity"><?php echo $entry_quantity; ?></th>
			<th class="text-left priority"><?php echo $entry_priority; ?></th>
			<th class="text-left price"><?php echo $entry_price; ?></th>
			<th class="text-left"><?php echo $entry_date_start; ?></th>
			<th class="text-left"><?php echo $entry_date_end; ?></th>
			<th width="1"></th>
		</tr>
	</thead>
	<tbody data-bind="foreach: discounts">
		<tr data-bind="css: {'danger': hasError}">
			<td class="text-left">
				<select class="form-control" data-bind="attr: {name: 'product_discount[' + $index()  + '][customer_group_id]'}, value: customer_group_id">
					<?php foreach ($customer_groups as $customer_group) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
					<?php } ?>
				</select>
			</td>
			<td class="text-right"><input type="text" class="form-control text-right quantity" data-bind="attr: {name: 'product_discount[' + $index() + '][quantity]'}, value: quantity" /></td>
			<td class="text-right"><input type="text" class="form-control text-right priority" data-bind="attr: {name: 'product_discount[' + $index() + '][priority]'}, value: priority" /></td>
			<td class="text-right"><input type="text" class="form-control text-right price" data-bind="attr: {name: 'product_discount[' + $index() + '][price]'}, value: price" /></td>
			<td class="text-left">
				<div class="input-group date" data-bind="datetimepicker: { pickTime: false }">
					<input type="text" class="form-control" data-date-format="YYYY-MM-DD" data-bind="attr: {name: 'product_discount[' + $index() + '][date_start]'}, value: date_start, css: {'has-error': date_start.hasError}, tooltip: {title: date_start.errorMsg}" data-container="body" />
					<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
				</div>
			</td>
			<td class="text-left">
				<div class="input-group date" data-bind="datetimepicker: { pickTime: false }">
					<input type="text" class="form-control" data-date-format="YYYY-MM-DD" data-bind="attr: {name: 'product_discount[' + $index() + '][date_end]'}, value: date_end, css: {'has-error': date_end.hasError}, tooltip: {title: date_end.errorMsg}" />
					<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
				</div>
			</td>
			<td class="text-vertical-middle"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-bind="click: $parent.removeDiscount, tooltip: {}"><i class="fa fa-minus-circle"></i> <span class="visible-lg-inline"><?php echo $button_remove; ?></span></button></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7" class="text-right"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove_all; ?>" data-bind="click: removeAll, disable: discounts().length == 0"><i class="fa fa-trash-o"></i> <span class="visible-lg-inline"><?php echo $button_remove_all; ?></span></button> <button type="button" class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add_discount; ?>" data-bind="click: addDiscount"><i class="fa fa-plus-circle"></i> <span class="visible-lg-inline"><?php echo $button_add_discount; ?></span></button></td>
		</tr>
	</tfoot>
</table>
<script type="text/javascript"><!--
!function(e,t){var o=<?php echo json_encode($product_discounts); ?>,i=function(t,o,i,r,s,n){var a=this;this.customer_group_id=ko.observable(t),this.quantity=ko.observable(o).extend({numeric:{precision:0,context:a}}),this.priority=ko.observable(i).extend({numeric:{precision:0,context:a}}),this.price=ko.observable(r).extend({numeric:{precision:0,context:a}}),this.date_start=ko.observable(s).extend({validate:{context:a,method:e.validate_datetime,message:"<?php echo addslashes($error_date); ?>",format:"YYYY-MM-DD"}}),this.date_end=ko.observable(n).extend({validate:{context:a,method:e.validate_datetime,message:"<?php echo addslashes($error_date); ?>",format:"YYYY-MM-DD"}}),this.hasError=ko.computed(this.hasError,this)};i.prototype=new e.observable_object_methods;var r=function(){var e=this;e.discounts=ko.observableArray(ko.utils.arrayMap(o,function(e){return new i(e.customer_group_id,e.quantity,e.priority,e.price,"0000-00-00"==e.date_start?"":e.date_start,"0000-00-00"==e.date_end?"":e.date_end)})).extend({applyErrors:{context:e}}),e.removeDiscount=function(t){e.discounts.remove(t)},e.addDiscount=function(){e.discounts.push(new i(null,0,0,"0.0000","",""))},e.removeAll=function(){e.discounts.removeAll()},this.hasError=ko.computed(this.hasError,this)};r.prototype=new e.observable_object_methods,e.view_models.actionQuickEditVM=new r,ko.applyBindings(e.view_models.actionQuickEditVM,t("#qe-discounts")[0])}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	case 'specials': ?>
<table id="qe-specials" cellpadding="0" cellspacing="0" border="0" class="table table-condensed<?php echo ($aqe_row_hover_highlighting) ? ' table-hover' : ''; ?><?php echo ($aqe_alternate_row_colour) ? ' table-striped' : ''; ?> qe">
	<thead>
		<tr>
			<th class="text-left"><?php echo $entry_customer_group; ?></th>
			<th class="text-left priority"><?php echo $entry_priority; ?></th>
			<th class="text-left price"><?php echo $entry_price; ?></th>
			<th class="text-left"><?php echo $entry_date_start; ?></th>
			<th class="text-left"><?php echo $entry_date_end; ?></th>
			<th width="1"></th>
		</tr>
	</thead>
	<tbody data-bind="foreach: specials">
		<tr data-bind="css: {'danger': hasError}">
			<td class="text-left">
				<select class="form-control" data-bind="attr: {name: 'product_special[' + $index()  + '][customer_group_id]'}, value: customer_group_id">
					<?php foreach ($customer_groups as $customer_group) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
					<?php } ?>
				</select>
			</td>
			<td class="text-right"><input type="text" class="form-control text-right priority" data-bind="attr: {name: 'product_special[' + $index() + '][priority]'}, value: priority" /></td>
			<td class="text-right"><input type="text" class="form-control text-right price" data-bind="attr: {name: 'product_special[' + $index() + '][price]'}, value: price" /></td>
			<td class="text-left">
				<div class="input-group date" data-bind="datetimepicker: { pickTime: false }">
					<input type="text" class="form-control" data-date-format="YYYY-MM-DD" data-bind="attr: {name: 'product_special[' + $index() + '][date_start]'}, value: date_start, css: {'has-error': date_start.hasError}, tooltip: {title: date_start.errorMsg}" data-container="body" />
					<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
				</div>
			</td>
			<td class="text-left">
				<div class="input-group date" data-bind="datetimepicker: { pickTime: false }">
					<input type="text" class="form-control" data-date-format="YYYY-MM-DD" data-bind="attr: {name: 'product_special[' + $index() + '][date_end]'}, value: date_end, css: {'has-error': date_end.hasError}, tooltip: {title: date_end.errorMsg}" />
					<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
				</div>
			</td>
			<td class="text-vertical-middle"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-bind="click: $parent.removeSpecial, tooltip: {}"><i class="fa fa-minus-circle"></i> <span class="visible-lg-inline"><?php echo $button_remove; ?></span></button></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6" class="text-right"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove_all; ?>" data-bind="click: removeAll, disable: specials().length == 0"><i class="fa fa-trash-o"></i> <span class="visible-lg-inline"><?php echo $button_remove_all; ?></span></button> <button type="button" class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add_special; ?>" data-bind="click: addSpecial"><i class="fa fa-plus-circle"></i> <span class="visible-lg-inline"><?php echo $button_add_special; ?></span></button></td>
		</tr>
	</tfoot>
</table>
<script type="text/javascript"><!--
!function(e,t){var o=<?php echo json_encode($product_specials); ?>,r=function(t,o,r,a,i){var s=this;this.customer_group_id=ko.observable(t),this.priority=ko.observable(o).extend({numeric:{precision:0,context:s}}),this.price=ko.observable(r).extend({numeric:{precision:0,context:s}}),this.date_start=ko.observable(a).extend({validate:{context:s,method:e.validate_datetime,message:"<?php echo addslashes($error_date); ?>",format:"YYYY-MM-DD"}}),this.date_end=ko.observable(i).extend({validate:{context:s,method:e.validate_datetime,message:"<?php echo addslashes($error_date); ?>",format:"YYYY-MM-DD"}}),this.hasError=ko.computed(this.hasError,this)};r.prototype=new e.observable_object_methods;var a=function(){var e=this;e.specials=ko.observableArray(ko.utils.arrayMap(o,function(e){return new r(e.customer_group_id,e.priority,e.price,"0000-00-00"==e.date_start?"":e.date_start,"0000-00-00"==e.date_end?"":e.date_end)})).extend({applyErrors:{context:e}}),e.removeSpecial=function(t){e.specials.remove(t)},e.addSpecial=function(){e.specials.push(new r(null,0,"0.0000","",""))},e.removeAll=function(){e.specials.removeAll()},e.hasError=ko.computed(this.hasError,this)};a.prototype=new e.observable_object_methods,e.view_models.actionQuickEditVM=new a,ko.applyBindings(e.view_models.actionQuickEditVM,t("#qe-specials")[0])}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	case 'filters': ?>
<div class="row" id="qe-filters">
	<div class="col-xs-12">
		<select name="product_filter[]" multiple="multiple" size="12" class="form-control">
		<?php foreach ($filters as $fg) { ?>
		<optgroup label="<?php echo addslashes($fg['name']); ?>">
		<?php foreach ($fg['filters'] as $f) { ?>
			<option value="<?php echo $f['filter_id']; ?>"<?php echo (in_array($f['filter_id'], $product_filters)) ? ' selected': ''; ?>><?php echo $f['name']; ?></option>
		<?php } ?>
		</optgroup>
		<?php } ?>
		</select>
	</div>
</div>
<script type="text/javascript"><!--
!function(e,t){t(function(){t("#qe-filters select").select2({placeholder:"<?php echo addslashes($text_select_filter); ?>",allowClear:!0,formatSelection:function(e){var l=t(e.element).parent();return l&&l.is("optgroup")&&l.attr("label")?l.attr("label")+" &gt; "+e.text:e.text}})})}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	case 'recurrings': ?>
<table id="qe-recurrings" cellpadding="0" cellspacing="0" border="0" class="table table-condensed<?php echo ($aqe_row_hover_highlighting) ? ' table-hover' : ''; ?><?php echo ($aqe_alternate_row_colour) ? ' table-striped' : ''; ?> qe">
	<thead>
		<tr>
			<th class="text-left"><?php echo $entry_recurring; ?></th>
			<th class="text-left"><?php echo $entry_customer_group; ?></th>
			<th width="1"></th>
		</tr>
	</thead>
	<tbody data-bind="foreach: recurrings">
		<tr data-bind="css: {'danger': hasError}">
			<td class="text-left">
				<select class="form-control" data-bind="attr: {name: 'product_recurring[' + $index()  + '][recurring_id]'}, value: recurring_id, css: {'has-error': recurring_id.hasError}, tooltip: {title: recurring_id.errorMsg}">
					<?php foreach ($recurrings as $recurring) { ?>
					<option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
					<?php } ?>
				</select>
			</td>
			<td class="text-left">
				<select class="form-control" data-bind="attr: {name: 'product_recurring[' + $index()  + '][customer_group_id]'}, value: customer_group_id">
					<?php foreach ($customer_groups as $customer_group) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
					<?php } ?>
				</select>
			</td>
			<td class="text-vertical-middle"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-bind="click: $parent.removeRecurring, tooltip: {}"><i class="fa fa-minus-circle"></i> <span class="visible-lg-inline"><?php echo $button_remove; ?></span></button></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" class="text-right"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove_all; ?>" data-bind="click: removeAll, disable: recurrings().length == 0"><i class="fa fa-trash-o"></i> <span class="visible-lg-inline"><?php echo $button_remove_all; ?></span></button> <button type="button" class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add_recurring; ?>" data-bind="click: addRecurring"><i class="fa fa-plus-circle"></i> <span class="visible-lg-inline"><?php echo $button_add_recurring; ?></span></button></td>
		</tr>
	</tfoot>
</table>
<script type="text/javascript"><!--
!function(r,e){var o=<?php echo json_encode($product_recurrings); ?>,i=function(r,e){var o=this;this.recurring_id=ko.observable(r).extend({validate:{context:o}}),this.customer_group_id=ko.observable(e),this.hasError=ko.computed(this.hasError,this)};i.prototype=new r.observable_object_methods;var n=function(){var r=this;r.recurrings=ko.observableArray(ko.utils.arrayMap(o,function(r){return new i(r.recurring_id,r.customer_group_id)})).extend({applyErrors:{context:r}}),r.removeRecurring=function(e){r.recurrings.remove(e)},r.addRecurring=function(){r.recurrings.push(new i(null,null))},r.removeAll=function(){r.recurrings.removeAll()},r.hasError=ko.computed(this.hasError,this)};n.prototype=new r.observable_object_methods,r.view_models.actionQuickEditVM=new n,ko.applyBindings(r.view_models.actionQuickEditVM,e("#qe-recurrings")[0])}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	case 'related': ?>
<div class="row" id="qe-related">
	<div class="col-xs-12">
		<!-- ko foreach: related -->
		<input type="hidden" name="product_related[]" data-bind="value: $data" />
		<!-- /ko -->
		<input type="hidden" id="rp-input" class="form-control" value="<?php echo implode(",", array_keys($product_related)); ?>">
	</div>
</div>
<script type="text/javascript"><!--
!function(e,t){var o=<?php echo json_encode(array_keys($product_related)); ?>,n=<?php echo json_encode($product_related); ?>,a=<?php echo $product_id; ?>,d=function(){var e=this;e.related=ko.observableArray(ko.utils.arrayMap(o,function(e){return e.toString()})),e.removeRelated=function(t){e.related.remove(t)},e.addRelated=function(t){e.related.push(t)}};e.view_models.actionQuickEditVM=new d,ko.applyBindings(e.view_models.actionQuickEditVM,t("#qe-related")[0]),t(function(){var e=n;t("#rp-input").select2({placeholder:"<?php echo addslashes($text_autocomplete); ?>",allowClear:!0,minimumInputLength:1,multiple:!0,formatResult:function(e){return e.model?t("<span/>").append(t("<strong/>").html(e.text),t("<small/>",{style:"padding-left:25px;","class":"text-muted"}).html(e.model)):e.text},initSelection:function(o,n){var a=[];t(o.val().split(",")).each(function(t,o){a.push({id:o,text:e.hasOwnProperty(o)?e[o].name:o})}),n(a)},ajax:{type:"GET",url:"<?php echo $filter; ?>",dataType:"json",cache:!1,quietMillis:150,data:function(e){return{query:e,type:"product",token:"<?php echo $token; ?>"}},results:function(e){var o=[];return t.each(e,function(e,t){t.id!=a&&o.push({id:t.id,text:t.value,model:t.model})}),{results:o}}}}),t("body").on("change","#rp-input",function(e){var t=ko.contextFor(this).$root;t&&(e.added?t.addRelated(e.added.id):e.removed&&t.removeRelated(e.removed.id))})})}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	case 'descriptions': ?>
<div class="row" id="qe-descriptions">
	<div class="col-xs-12">
		<ul class="nav nav-tabs">
			<!-- ko foreach: descriptions -->
			<li data-bind="css: {active: language_id() == $root.default_language}"><a data-bind="attr: {href: '#lang-' + language_id()}" data-toggle="tab"><img data-bind="attr: {src: $root.languages[language_id()].flag, title: $root.languages[language_id()].name}" /> <!-- ko text: $root.languages[language_id()].name --><!--/ko--> <!-- ko if: hasError --> <i class="fa fa-exclamation-circle text-danger"></i><!-- /ko --></a></li>
			<!-- /ko -->
 		</ul>
		<div class="tab-content">
			<!-- ko foreach: descriptions -->
			<div class="tab-pane" data-bind="attr: {id: 'lang-' + language_id()}, css: {active: language_id() == $root.default_language}">
				<div class="form-group">
					<label data-bind="attr: {for: 'description' + language_id()}" class="control-label"><?php echo $entry_description; ?></label>
					<textarea data-bind="summernote: {height: 300}, attr: {name: 'product_description[' + language_id() + '][description]', id: 'description' + language_id()}, value: description" class="form-control" rows="5"></textarea>
				</div>
				<div class="form-group required" data-bind="css: {'has-error': meta_title.hasError}">
					<label data-bind="attr: {for: 'meta_title' + language_id()}" class="control-label"><?php echo $entry_meta_title; ?></label>
					<input type="text" data-bind="attr: {name: 'product_description[' + language_id() + '][meta_title]', id: 'meta_title' + language_id()}, value: meta_title" class="form-control" />
					<div class="error-container" data-bind="visible: meta_title.hasError && meta_title.errorMsg">
						<span class="help-block error-text" data-bind="text: meta_title.errorMsg"></span>
					</div>
				</div>
				<div class="form-group">
					<label data-bind="attr: {for: 'meta_description' + language_id()}" class="control-label"><?php echo $entry_meta_description; ?></label>
					<textarea data-bind="attr: {name: 'product_description[' + language_id() + '][meta_description]', id: 'meta_description' + language_id()}, value: meta_description" class="form-control" rows="3"></textarea>
				</div>
				<div class="form-group">
					<label data-bind="attr: {for: 'meta_keyword' + language_id()}" class="control-label"><?php echo $entry_meta_keyword; ?></label>
					<textarea data-bind="attr: {name: 'product_description[' + language_id() + '][meta_keyword]', id: 'meta_keyword' + language_id()}, value: meta_keyword" class="form-control" rows="3"></textarea>
				</div>
			</div>
			<!-- /ko -->
		</div>
	</div>
</div>
<script type="text/javascript"><!--
!function(e,t,a){var i=<?php echo json_encode($languages); ?>,r=<?php echo json_encode($product_description); ?>;e.texts=t.extend({},e.texts,{error_meta_title:"<?php echo addslashes($error_meta_title); ?>"});var n=function(e){var t=this.min_length!=a?this.min_length:0,i=this.max_length!=a?this.max_length:255;e.length<t||e.length>i?(this.target.hasError(!0),this.target.errorMsg(this.message)):(this.target.hasError(!1),this.target.errorMsg(""))},o=function(e,t,a){this.id=e,this.name=t,this.flag=a},s=function(t,a,i,r,o){var s=this;this.language_id=ko.observable(t),this.description=ko.observable(a),this.meta_title=ko.observable(i).extend({validate:{method:n,min_length:3,max_length:255,message:e.texts.error_meta_title,context:s}}),this.meta_description=ko.observable(r),this.meta_keyword=ko.observable(o),this.hasError=ko.computed(this.hasError,this)};s.prototype=new e.observable_object_methods;var h=function(){var e=this;this.languages={},t.each(i,function(t,a){e.languages[a.language_id]=new o(a.language_id,a.name,"view/image/flags/"+a.image)}),this.default_language="<?php echo $default_language; ?>",this.descriptions=ko.observableArray(t.map(i,function(e){return r.hasOwnProperty(e.language_id)?new s(e.language_id,r[e.language_id].hasOwnProperty("description")?r[e.language_id].description:"",r[e.language_id].hasOwnProperty("meta_title")?r[e.language_id].meta_title:"",r[e.language_id].hasOwnProperty("meta_description")?r[e.language_id].meta_description:"",r[e.language_id].hasOwnProperty("meta_keyword")?r[e.language_id].meta_keyword:""):new s(e.language_id,"","","","")})).withIndex("language_id").extend({hasError:{check:!0,context:e},applyErrors:{context:e}}),this.hasError=ko.computed(this.hasError,this)};h.prototype=new e.observable_object_methods,e.view_models.actionQuickEditVM=new h,ko.applyBindings(e.view_models.actionQuickEditVM,t("#qe-descriptions")[0])}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	case 'images': ?>
<table id="qe-images" cellpadding="0" cellspacing="0" border="0" class="table table-condensed<?php echo ($aqe_row_hover_highlighting) ? ' table-hover' : ''; ?><?php echo ($aqe_alternate_row_colour) ? ' table-striped' : ''; ?> qe">
	<thead>
		<tr>
			<th class="text-left" width="1"><?php echo $entry_image; ?></th>
			<th class="text-right"><?php echo $entry_sort_order; ?></th>
			<th width="1"></th>
		</tr>
	</thead>
	<tbody data-bind="foreach: images">
		<tr>
			<td class="text-left">
				<a href="#" data-toggle="im" class="img-thumbnail img-relative" data-bind="attr: {id: 'ai-thumb-' + $index()}">
					<div class="content-overlay">
						<span class="fa fa-4x fa-refresh fa-spin"></span>
					</div>
					<img data-bind="attr: {src: thumb}">
				</a>
				<input type="hidden" data-bind="attr: {name: 'product_image[' + $index()  + '][image]', id: 'ai-image-' + $index()}, value: image" />
			</td>
			<td class="text-vertical-middle"><input type="text" class="form-control text-right sort-order pull-right" data-bind="attr: {name: 'product_image[' + $index() + '][sort_order]'}, value: sort_order" /></td>
			<td class="text-vertical-middle"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-bind="click: $parent.removeImage, tooltip: {}"><i class="fa fa-minus-circle"></i> <span class="visible-lg-inline"><?php echo $button_remove; ?></span></button></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" class="text-right"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove_all; ?>" data-bind="click: removeAll, disable: images().length == 0"><i class="fa fa-trash-o"></i> <span class="visible-lg-inline"><?php echo $button_remove_all; ?></span></button> <button type="button" class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add_image; ?>" data-bind="click: addImage"><i class="fa fa-plus-circle"></i> <span class="visible-lg-inline"><?php echo $button_add_image; ?></span></button></td>
		</tr>
	</tfoot>
</table>
<input type="hidden" value="" id="ai-new-image" />
<script type="text/javascript"><!--
!function(e,o){var a=<?php echo json_encode($product_images); ?>,i="<?php echo $no_image; ?>",n=function(e,o,a){this.image=ko.observable(e),this.thumb=ko.observable(o),this.sort_order=ko.observable(a),this.hasError=ko.computed(this.hasError,this)};n.prototype=new e.observable_object_methods;var t=function(){var e=this;e.images=ko.observableArray(ko.utils.arrayMap(a,function(e){return new n(e.image,e.thumb,e.sort_order)})).extend({hasError:{check:!0,context:e},applyErrors:{context:e}}),e.addImage=function(){e.images.push(new n("",i,0))},e.removeImage=function(o){e.images.remove(o)},e.removeAll=function(){e.images.removeAll()},e.clearImage=function(e){var o=e.$data;o.image(""),o.thumb(i)},e.browseImage=function(e){var a="ai-new-image",i="",n=o.Deferred();return o("#"+a).val(""),o.ajax({url:"index.php?route=common/filemanager&token=<?php echo $token; ?>&target="+encodeURIComponent(a),dataType:"html"}).done(function(n){o("#modal-image").append(n),o("#modal-image").modal("show"),o("#modal-image").on("hide.bs.modal",function(){var n=o("#ai-thumb-"+e.$index()+" .content-overlay");o("#"+a).val()&&(i=o("#"+a).val(),o.ajax({url:"index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image="+encodeURIComponent(i)+"&width=<?php echo $additional_image_width; ?>&height=<?php echo $additional_image_height; ?>",dataType:"text",beforeSend:function(){n&&n.addClass("in")}}).done(function(o){e.$data.image(i),e.$data.thumb(o)}).always(function(){n&&n.removeClass("in")})),o("#modal-image").off("hide.bs.modal")}).on("hidden.bs.modal",function(){o("#modal-image").empty(),o("#modal-image").off("hidden.bs.modal")})}).always(function(){n.resolve()}),n.promise()},this.hasError=ko.computed(this.hasError,this)};t.prototype=new e.observable_object_methods,e.view_models.actionQuickEditVM=new t,ko.applyBindings(e.view_models.actionQuickEditVM,o("#qe-images")[0])}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	case 'options': ?>
<div id="qe-options" class="form-horizontal">
	<div class="row">
		<div class="col-xs-4 col-sm-4 col-md-3">
			<ul class="nav nav-pills nav-stacked">
				<!-- ko foreach: options -->
				<li data-bind="attr: {class: $index() == 0 ? 'active' : ''}"><a data-bind="attr: {href: '#tab-option-' + $index()}" data-toggle="tab"><button type="button" data-toggle="tooltip" class="btn btn-xs btn-link remove-option" data-bind="click: $parent.removeOption, tooltip: {}" title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></button> <!-- ko text: name --><!-- /ko --></a></li>
				<!-- /ko -->
				<li><input type="text" class="form-control options typeahead" placeholder="<?php echo $text_autocomplete; ?>" data-bind="typeahead: { options:{ autoSelect: true }, datasets: $root.ta_dataset}"></li>
			</ul>
		</div>
		<div class="col-xs-8 col-sm-8 col-md-9">
			<div class="tab-content">
				<!-- ko foreach: options -->
				<div data-bind="attr: {id: 'tab-option-' + $index(), class: $index() == 0 ? 'tab-pane active' : 'tab-pane'}">
					<input type="hidden" data-bind="attr: {name: 'product_option[' + $index()  + '][product_option_id]'}, value: product_option_id" />
					<input type="hidden" data-bind="attr: {name: 'product_option[' + $index()  + '][option_id]'}, value: option_id" />
					<input type="hidden" data-bind="attr: {name: 'product_option[' + $index()  + '][name]'}, value: name" />
					<input type="hidden" data-bind="attr: {name: 'product_option[' + $index()  + '][type]'}, value: type" />
					<div class="form-group">
						<label data-bind="attr: {for: 'input-required-' + $index()}" class="col-sm-3 col-lg-2 control-label"><?php echo $entry_required; ?></label>
						<div class="col-sm-4 col-md-3">
							<select data-bind="attr: {id: 'input-required-' + $index(), name: 'product_option[' + $index()  + '][required]'}, value: required" class="form-control">
								<option value="1"><?php echo $text_yes; ?></option>
								<option value="0"><?php echo $text_no; ?></option>
							</select>
						</div>
					</div>
					<!-- ko if: type() == "text" -->
					<div class="form-group">
						<label data-bind="attr: {for: 'input-option-value-' + $index()}" class="col-sm-3 col-lg-2 control-label"><?php echo $entry_option_value; ?></label>
						<div class="col-sm-9 col-lg-10">
							<input type="text" data-bind="attr: {id: 'input-option-value-' + $index(), name: 'product_option[' + $index()  + '][value]'}, value: value" class="form-control">
						</div>
					</div>
					<!-- /ko -->
					<!-- ko if: type() == "textarea" -->
					<div class="form-group">
						<label data-bind="attr: {for: 'input-option-value-' + $index()}" class="col-sm-3 col-lg-2 control-label"><?php echo $entry_option_value; ?></label>
						<div class="col-sm-9 col-lg-10">
							<textarea data-bind="attr: {id: 'input-option-value-' + $index(), name: 'product_option[' + $index()  + '][value]'}, value: value" class="form-control" rows="5"></textarea>
						</div>
					</div>
					<!-- /ko -->
					<!-- ko if: type() == "file" -->
					<input type="hidden" data-bind="attr: {id: 'input-option-value-' + $index(), name: 'product_option[' + $index()  + '][value]'}, value: value">
					<!-- /ko -->
					<!-- ko if: type() == "date" -->
					<div class="form-group">
						<label data-bind="attr: {for: 'input-option-value-' + $index()}" class="col-sm-3 col-lg-2 control-label"><?php echo $entry_option_value; ?></label>
						<div class="col-sm-6 col-md-5">
							<div class="input-group date" data-bind="datetimepicker: { pickTime: false }">
								<input type="text" data-date-format="YYYY-MM-DD" data-bind="attr: {id: 'input-option-value-' + $index(), name: 'product_option[' + $index()  + '][value]'}, value: value" class="form-control">
								<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
							</div>
						</div>
					</div>
					<!-- /ko -->
					<!-- ko if: type() == "time" -->
					<div class="form-group">
						<label data-bind="attr: {for: 'input-option-value-' + $index()}" class="col-sm-3 col-lg-2 control-label"><?php echo $entry_option_value; ?></label>
						<div class="col-sm-6 col-md-5">
							<div class="input-group time" data-bind="datetimepicker: { pickTime: true, pickDate: false }">
								<input type="text" data-date-format="HH:mm" data-bind="attr: {id: 'input-option-value-' + $index(), name: 'product_option[' + $index()  + '][value]'}, value: value" class="form-control">
								<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
							</div>
						</div>
					</div>
					<!-- /ko -->
					<!-- ko if: type() == "datetime" -->
					<div class="form-group">
						<label data-bind="attr: {for: 'input-option-value-' + $index()}" class="col-sm-3 col-lg-2 control-label"><?php echo $entry_option_value; ?></label>
						<div class="col-sm-8 col-md-6">
							<div class="input-group datetime" data-bind="datetimepicker: { pickTime: true }">
								<input type="text" data-date-format="YYYY-MM-DD HH:mm" data-bind="attr: {id: 'input-option-value-' + $index(), name: 'product_option[' + $index()  + '][value]'}, value: value" class="form-control">
								<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
							</div>
						</div>
					</div>
					<!-- /ko -->
					<!-- ko if: $.inArray(type(), ['select', 'radio', 'checkbox', 'image']) != -1 -->
					<div class="table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-condensed<?php echo ($aqe_row_hover_highlighting) ? ' table-hover' : ''; ?><?php echo ($aqe_alternate_row_colour) ? ' table-striped' : ''; ?> qe">
							<thead>
								<tr>
									<th class="text-left"><?php echo $entry_option_value; ?></th>
									<th class="text-right quantity"><?php echo $entry_quantity; ?></th>
									<th class="text-left subtract"><?php echo $entry_subtract; ?></th>
									<th class="text-right price"><?php echo $entry_price; ?></th>
									<th class="text-right points"><?php echo $entry_option_points; ?></th>
									<th class="text-right weight"><?php echo $entry_weight; ?></th>
									<th width="1"></th>
								</tr>
							</thead>
							<tbody data-bind="foreach: product_option_value">
								<tr>
									<td class="text-vertical-middle text-left">
										<select data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][option_value_id]'}, options: $root.option_values[$parent.option_id()], optionsValue: 'option_value_id', optionsText: 'name', value: option_value_id" class="form-control">
											<option data-bind="value: option_value_id, text: name"></option>
										</select>
										<input type="hidden" data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][product_option_value_id]'}, value: product_option_value_id" /></td>
									</td>
									<td class="text-vertical-middle text-right"><input type="text" class="form-control text-right quantity" data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][quantity]'}, value: quantity" /></td>
									<td class="text-vertical-middle text-left">
										<select data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][subtract]'}, value: subtract" class="form-control">
											<option value="1"><?php echo $text_yes; ?></option>
											<option value="0"><?php echo $text_no; ?></option>
										</select>
									</td>
									<td class="text-right">
										<select data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][price_prefix]'}, value: price_prefix" class="form-control prefix">
											<option value="+"><?php echo $text_plus; ?></option>
											<option value="-"><?php echo $text_minus; ?></option>
										</select>
										<input type="text" class="form-control text-right price" data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][price]'}, value: price" />
									</td>
									<td class="text-right">
										<select data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][points_prefix]'}, value: points_prefix" class="form-control prefix">
											<option value="+"><?php echo $text_plus; ?></option>
											<option value="-"><?php echo $text_minus; ?></option>
										</select>
										<input type="text" class="form-control text-right points" data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][points]'}, value: points" />
									</td>
									<td class="text-right">
										<select data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][weight_prefix]'}, value: weight_prefix" class="form-control prefix">
											<option value="+"><?php echo $text_plus; ?></option>
											<option value="-"><?php echo $text_minus; ?></option>
										</select>
										<input type="text" class="form-control text-right weight" data-bind="attr: {name: 'product_option[' + $parentContext.$index() + '][product_option_value][' + $index() + '][weight]'}, value: weight" />
									</td>
									<td class="text-vertical-middle"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-bind="click: $parent.removeOptionValue, tooltip: {}"><i class="fa fa-minus-circle"></i> <span class="visible-lg-inline"><?php echo $button_remove; ?></span></button></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="7" class="text-right"><button type="button" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_remove_all; ?>" data-bind="click: removeAll, disable: product_option_value().length == 0, tooltip: {}"><i class="fa fa-trash-o"></i> <span class="visible-lg-inline"><?php echo $button_remove_all; ?></span></button> <button type="button" class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add_option; ?>" data-bind="click: addOptionValue, tooltip: {}"><i class="fa fa-plus-circle"></i> <span class="visible-lg-inline"><?php echo $button_add_option; ?></span></button></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<!-- /ko -->
				</div>
				<!-- /ko -->
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
!function(o,e,t){var i=<?php echo json_encode($product_options); ?>,r=<?php echo json_encode($option_values); ?>,s=new Bloodhound({remote:"<?php echo $typeahead; ?>",datumTokenizer:Bloodhound.tokenizers.obj.whitespace("value"),queryTokenizer:Bloodhound.tokenizers.whitespace,dupDetector:function(o,e){return o.id&&e.id&&o.id==e.id},limit:10});s.initialize();var n=function(o,e,t,i,r,s,n,a,p,u){this.product_option_value_id=ko.observable(o),this.option_value_id=ko.observable(e),this.quantity=ko.observable(t),this.subtract=ko.observable(i),this.price=ko.observable(r),this.price_prefix=ko.observable(s),this.points=ko.observable(n),this.points_prefix=ko.observable(a),this.weight=ko.observable(p),this.weight_prefix=ko.observable(u),this.hasError=ko.computed(this.hasError,this)};n.prototype=new o.observable_object_methods;var a=function(o,e,i,r,s,a,p){var u=this;this.product_option_id=ko.observable(o),this.option_id=ko.observable(e),this.name=ko.observable(i),this.type=ko.observable(r),this.required=ko.observable(a),this.product_option_value=ko.observableArray(ko.utils.arrayMap(p,function(o){return new n(o.product_option_value_id,o.option_value_id,o.quantity,o.subtract,o.price,o.price_prefix,o.points,o.points_prefix,o.weight,o.weight_prefix)})).extend({hasError:{check:!0,context:u},applyErrors:{context:u}}),s=s==t?"":s,this.value=ko.observable(s),this.removeOptionValue=function(o){u.product_option_value.remove(o)},this.addOptionValue=function(){u.product_option_value.push(new n(null,"",0,0,"0.0000","+",0,"+","0.0000","+"))},this.removeAll=function(){u.product_option_value.removeAll()},this.hasError=ko.computed(this.hasError,this)};a.prototype=new o.observable_object_methods;var p=function(){var o=this;o.options=ko.observableArray(ko.utils.arrayMap(i,function(o){return new a(o.product_option_id,o.option_id,o.name,o.type,o.value,o.required,o.product_option_value)})).extend({hasError:{check:!0,context:o},applyErrors:{context:o}}),o.option_values=r,o.removeOption=function(e){o.options.remove(e)},o.addOption=function(e){e.value.length&&!o.option_values.hasOwnProperty(e.id)&&(o.option_values[e.id]=e.value),o.options.push(new a("",e.id,e.value,e.type,"",0,[]))},this.ta_dataset={name:"<?php echo $column; ?>",source:s.ttAdapter(),templates:{empty:['<div class="tt-no-suggestion"><?php echo addslashes($text_no_records_found); ?></div>'].join("\n"),suggestion:Handlebars.compile(['<p><span class="tt-nowrap">{{value}}<span class="tt-secondary-right">{{category}}</span></span></p>'].join(""))}},this.hasError=ko.computed(this.hasError,this)};p.prototype=new o.observable_object_methods,o.view_models.actionQuickEditVM=new p,ko.applyBindings(o.view_models.actionQuickEditVM,e("#qe-options")[0])}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
		<?php break;
	default:
		break;
}
?>
