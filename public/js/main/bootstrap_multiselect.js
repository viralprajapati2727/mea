!function(t){"use strict";function e(e,i){this.$select=t(e),this.options=this.mergeOptions(t.extend({},i,this.$select.data())),this.$select.attr("data-placeholder")&&(this.options.nonSelectedText=this.$select.data("placeholder")),this.$select.attr("data-button-width")&&(this.options.buttonWidth=this.$select.data("button-width")),this.originalOptions=this.$select.clone()[0].options,this.query="",this.searchTimeout=null,this.lastToggledInput=null,this.options.multiple="multiple"===this.$select.attr("multiple"),this.options.onChange=t.proxy(this.options.onChange,this),this.options.onSelectAll=t.proxy(this.options.onSelectAll,this),this.options.onDeselectAll=t.proxy(this.options.onDeselectAll,this),this.options.onDropdownShow=t.proxy(this.options.onDropdownShow,this),this.options.onDropdownHide=t.proxy(this.options.onDropdownHide,this),this.options.onDropdownShown=t.proxy(this.options.onDropdownShown,this),this.options.onDropdownHidden=t.proxy(this.options.onDropdownHidden,this),this.options.onInitialized=t.proxy(this.options.onInitialized,this),this.options.onFiltering=t.proxy(this.options.onFiltering,this),this.buildContainer(),this.buildButton(),this.buildDropdown(),this.buildSelectAll(),this.buildDropdownOptions(),this.buildFilter(),this.updateButtonText(),this.updateSelectAll(!0),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups(),this.options.wasDisabled=this.$select.prop("disabled"),this.options.disableIfEmpty&&t("option",this.$select).length<=0&&this.disable(),this.$select.wrap('<div class="multiselect-native-select" />').after(this.$container),this.options.onInitialized(this.$select,this.$container)}"undefined"!=typeof ko&&ko.bindingHandlers&&!ko.bindingHandlers.multiselect&&(ko.bindingHandlers.multiselect={after:["options","value","selectedOptions","enable","disable"],init:function(e,i,s,l,o){var n=t(e),a=ko.toJS(i());if(n.multiselect(a),s.has("options")){var p=s.get("options");ko.isObservable(p)&&ko.computed({read:function(){p(),setTimeout(function(){var t=n.data("multiselect");t&&t.updateOriginalOptions(),n.multiselect("rebuild")},1)},disposeWhenNodeIsRemoved:e})}if(s.has("value")){var c=s.get("value");ko.isObservable(c)&&ko.computed({read:function(){c(),setTimeout(function(){n.multiselect("refresh")},1)},disposeWhenNodeIsRemoved:e}).extend({rateLimit:100,notifyWhenChangesStop:!0})}if(s.has("selectedOptions")){var h=s.get("selectedOptions");ko.isObservable(h)&&ko.computed({read:function(){h(),setTimeout(function(){n.multiselect("refresh")},1)},disposeWhenNodeIsRemoved:e}).extend({rateLimit:100,notifyWhenChangesStop:!0})}var d=function(t){setTimeout(function(){t?n.multiselect("enable"):n.multiselect("disable")})};if(s.has("enable")){var r=s.get("enable");ko.isObservable(r)?ko.computed({read:function(){d(r())},disposeWhenNodeIsRemoved:e}).extend({rateLimit:100,notifyWhenChangesStop:!0}):d(r)}if(s.has("disable")){var u=s.get("disable");ko.isObservable(u)?ko.computed({read:function(){d(!u())},disposeWhenNodeIsRemoved:e}).extend({rateLimit:100,notifyWhenChangesStop:!0}):d(!u)}ko.utils.domNodeDisposal.addDisposeCallback(e,function(){n.multiselect("destroy")})},update:function(e,i,s,l,o){var n=t(e),a=ko.toJS(i());n.multiselect("setOptions",a),n.multiselect("rebuild")}}),e.prototype={defaults:{buttonText:function(e,i){if(this.disabledText.length>0&&(i.prop("disabled")||0==e.length&&this.disableIfEmpty))return this.disabledText;if(0===e.length)return this.nonSelectedText;if(this.allSelectedText&&e.length===t("option",t(i)).length&&1!==t("option",t(i)).length&&this.multiple)return this.selectAllNumber?this.allSelectedText+" ( "+e.length+" )":this.allSelectedText;if(e.length>this.numberDisplayed)return e.length+" "+this.nSelectedText;var s="",l=this.delimiterText;return e.each(function(){var e=void 0!==t(this).attr("label")?t(this).attr("label"):t(this).text();s+=e+l}),s.substr(0,s.length-this.delimiterText.length)},buttonTitle:function(e,i){if(0===e.length)return this.nonSelectedText;var s="",l=this.delimiterText;return e.each(function(){var e=void 0!==t(this).attr("label")?t(this).attr("label"):t(this).text();s+=e+l}),s.substr(0,s.length-this.delimiterText.length)},checkboxName:function(t){return!1},optionLabel:function(e){return t(e).attr("label")||t(e).text()},optionClass:function(e){return t(e).attr("class")||""},onChange:function(t,e){},onDropdownShow:function(t){},onDropdownHide:function(t){},onDropdownShown:function(t){},onDropdownHidden:function(t){},onSelectAll:function(){},onDeselectAll:function(){},onInitialized:function(t,e){},onFiltering:function(t){},enableHTML:!0,buttonClass:"btn btn-light",inheritClass:!1,buttonWidth:"100%",buttonContainer:'<div class="btn-group" />',dropRight:!1,dropUp:!1,selectedClass:"active",maxHeight:!1,includeSelectAllOption:!1,includeSelectAllIfMoreThan:0,selectAllText:" Select all",selectAllValue:"multiselect-all",selectAllName:!1,selectAllNumber:!0,selectAllJustVisible:!0,enableFiltering:!1,enableCaseInsensitiveFiltering:!1,enableFullValueFiltering:!1,enableClickableOptGroups:!1,enableCollapsibleOptGroups:!1,filterPlaceholder:"Search",filterBehavior:"text",includeFilterClearBtn:!0,preventInputChangeEvent:!1,nonSelectedText:"None selected",nSelectedText:"selected",allSelectedText:"All selected",numberDisplayed:3,disableIfEmpty:!1,disabledText:"",delimiterText:", ",templates:{button:'<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',ul:'<div class="multiselect-container dropdown-menu"></div>',filter:'<div class="multiselect-item multiselect-filter"><div class="input-group"><input class="form-control multiselect-search" type="text"><i class="icon-search4"></i></div></div>',filterClearBtn:'<span class="input-group-append"><button class="btn btn-light btn-icon multiselect-clear-filter" type="button"><i class="icon-cross2"></i></button></span>',li:'<div class="multiselect-item dropdown-item form-check" tabindex="0"><label class="form-check-label"></label></div>',divider:'<div class="multiselect-item dropdown-divider"></div>',liGroup:'<div class="multiselect-item multiselect-group"><label class="form-check-label"></label></div>'}},constructor:e,buildContainer:function(){this.$container=t(this.options.buttonContainer),this.$container.on("show.bs.dropdown",this.options.onDropdownShow),this.$container.on("hide.bs.dropdown",this.options.onDropdownHide),this.$container.on("shown.bs.dropdown",this.options.onDropdownShown),this.$container.on("hidden.bs.dropdown",this.options.onDropdownHidden)},buildButton:function(){this.$button=t(this.options.templates.button).addClass(this.options.buttonClass),this.$select.attr("class")&&this.options.inheritClass&&this.$button.addClass(this.$select.attr("class")),this.$select.prop("disabled")?this.disable():this.enable(),this.options.buttonWidth&&"auto"!==this.options.buttonWidth&&(this.$button.css({width:"100%",overflow:"hidden","text-overflow":"ellipsis"}),this.$container.css({width:this.options.buttonWidth}));var e=this.$select.attr("tabindex");e&&this.$button.attr("tabindex",e),this.$container.prepend(this.$button)},buildDropdown:function(){if(this.$ul=t(this.options.templates.ul),this.options.dropRight&&this.$ul.addClass("dropdown-menu-right"),this.options.maxHeight&&this.$ul.css({"max-height":this.options.maxHeight+"px","overflow-y":"auto","overflow-x":"hidden"}),this.options.dropUp){var e=Math.min(this.options.maxHeight,26*t('option[data-role!="divider"]',this.$select).length+19*t('option[data-role="divider"]',this.$select).length+(this.options.includeSelectAllOption?26:0)+(this.options.enableFiltering||this.options.enableCaseInsensitiveFiltering?44:0)),i=e+34;this.$ul.css({"max-height":e+"px","overflow-y":"auto","overflow-x":"hidden","margin-top":"-"+i+"px"})}this.$container.append(this.$ul)},buildDropdownOptions:function(){this.$select.children().each(t.proxy(function(e,i){var s=t(i),l=s.prop("tagName").toLowerCase();s.prop("value")!==this.options.selectAllValue&&("optgroup"===l?this.createOptgroup(i):"option"===l&&("divider"===s.data("role")?this.createDivider():this.createOptionValue(i)))},this)),t(".multiselect-item:not(.multiselect-group) input",this.$ul).on("change",t.proxy(function(e){var i=t(e.target),s=i.prop("checked")||!1,l=i.val()===this.options.selectAllValue;this.options.selectedClass&&(s?i.closest(".multiselect-item").addClass(this.options.selectedClass):i.closest(".multiselect-item").removeClass(this.options.selectedClass));var o=i.val(),n=this.getOptionByValue(o),a=t("option",this.$select).not(n),p=t("input",this.$container).not(i);if(l?s?this.selectAll(this.options.selectAllJustVisible,!0):this.deselectAll(this.options.selectAllJustVisible,!0):(s?(n.prop("selected",!0),this.options.multiple?n.prop("selected",!0):(this.options.selectedClass&&t(p).closest(".multiselect-item").removeClass(this.options.selectedClass),t(p).prop("checked",!1),a.prop("selected",!1),this.$button.click())):n.prop("selected",!1),this.options.onChange(n,s),this.updateSelectAll(),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups()),this.$select.change(),this.updateButtonText(),this.options.preventInputChangeEvent)return!1},this)),t(".dropdown-item",this.$ul).on("mousedown",function(t){if(t.shiftKey)return!1}),t(".dropdown-item",this.$ul).on("touchstart click",t.proxy(function(e){e.stopPropagation();var i=t(e.target);if(e.shiftKey&&this.options.multiple){i.is("label")&&(e.preventDefault(),(i=i.find("input")).prop("checked",!i.prop("checked")));var s=i.prop("checked")||!1;if(null!==this.lastToggledInput&&this.lastToggledInput!==i){var l=i.closest(".multiselect-item").index(),o=this.lastToggledInput.closest(".multiselect-item").index();if(l>o){var n=o;o=l,l=n}++o;var a=this.$ul.find(".multiselect-item").slice(l,o).find("input");a.prop("checked",s),this.options.selectedClass&&a.closest(".multiselect-item").toggleClass(this.options.selectedClass,s);for(var p=0,c=a.length;p<c;p++){var h=t(a[p]);this.getOptionByValue(h.val()).prop("selected",s)}}i.trigger("change")}i.is("input")&&!i.closest("div, a").is(".multiselect-item")&&(this.lastToggledInput=i),i.blur()},this)),this.$container.off("keydown.multiselect").on("keydown.multiselect",t.proxy(function(e){if(!t('input[type="text"]',this.$container).is(":focus"))if(9===e.keyCode&&this.$container.hasClass("show"))this.$button.click();else{var i=t(this.$container).find(".dropdown-item:not(.disabled)").filter(":visible");if(!i.length)return;var s=i.index(i.filter(":focus"));38===e.keyCode&&s>0?s--:40===e.keyCode&&s<i.length-1?s++:~s||(s=0);var l=i.eq(s);if(l.focus(),32===e.keyCode||13===e.keyCode){var o=l.find("input");o.prop("checked",!o.prop("checked")),o.change()}e.stopPropagation(),e.preventDefault()}},this)),this.options.enableClickableOptGroups&&this.options.multiple&&t(".multiselect-group input",this.$ul).on("change",t.proxy(function(e){e.stopPropagation();var i=t(e.target).prop("checked")||!1,s=t(e.target).closest(".multiselect-item"),l=s.nextUntil(".multiselect-group").not(".multiselect-filter-hidden").not(".disabled").find("input"),o=[];this.options.selectedClass&&(i?s.addClass(this.options.selectedClass):s.removeClass(this.options.selectedClass)),t.each(l,t.proxy(function(e,s){var l=t(s).val(),n=this.getOptionByValue(l);i?(t(s).prop("checked",!0),t(s).closest(".multiselect-item").addClass(this.options.selectedClass),n.prop("selected",!0)):(t(s).prop("checked",!1),t(s).closest(".multiselect-item").removeClass(this.options.selectedClass),n.prop("selected",!1)),o.push(this.getOptionByValue(l))},this)),this.options.onChange(o,i),this.updateButtonText(),this.updateSelectAll()},this)),this.options.enableCollapsibleOptGroups&&this.options.multiple&&(t(".multiselect-group .caret-container",this.$ul).on("click",t.proxy(function(e){var i=t(e.target).closest(".multiselect-item").nextUntil(".multiselect-group").not(".multiselect-filter-hidden"),s=!0;i.each(function(){s=s&&t(this).is(":visible")}),s?i.hide().addClass("multiselect-collapsible-hidden"):i.show().removeClass("multiselect-collapsible-hidden")},this)),t(".multiselect-all",this.$ul).css("background","#f3f3f3").css("border-bottom","1px solid #eaeaea"),t(".multiselect-all > label.form-check-label",this.$ul).css("padding","3px 20px 3px 35px"),t(".multiselect-group > input",this.$ul).css("margin","4px 0px 5px -20px"))},createOptionValue:function(e){var i=t(e);i.is(":selected")&&i.prop("selected",!0);var s=this.options.optionLabel(e),l=this.options.optionClass(e),o=i.val(),n=this.options.multiple?"checkbox":"radio",a=t(this.options.templates.li),p=t("label",a);p.addClass("form-check-label"),a.addClass(l),this.options.enableHTML?p.html(" "+s+"<span class='form-check-control-indicator' />"):p.text(" "+s);var c=t("<input/>").attr("type",n),h=this.options.checkboxName(i);h&&c.attr("name",h),p.prepend(c);var d=i.prop("selected")||!1;c.val(o),o===this.options.selectAllValue&&a.addClass("multiselect-item multiselect-all"),p.attr("title",i.attr("title")),this.$ul.append(a),i.is(":disabled")&&c.attr("disabled","disabled").prop("disabled",!0).closest(".dropdown-item").attr("tabindex","-1").addClass("disabled"),c.prop("checked",d),d&&this.options.selectedClass&&c.closest(".dropdown-item").addClass(this.options.selectedClass)},createDivider:function(e){var i=t(this.options.templates.divider);this.$ul.append(i)},createOptgroup:function(e){var i=t(e).attr("label"),s=t(e).attr("value"),l=t('<div class="multiselect-item multiselect-group form-check"><label></label></div>'),o=this.options.optionClass(e);l.addClass(o),this.options.enableHTML?t("label",l).html(" "+i):t("label",l).text(" "+i),this.options.enableCollapsibleOptGroups&&this.options.multiple&&t(l).addClass("dropdown-toggle"),this.options.enableClickableOptGroups&&this.options.multiple&&(t("label",l).addClass("form-check-label").prepend('<input type="checkbox" value="'+s+'"/> <span class="form-check-control-indicator" />'),l.closest(".multiselect-item").addClass("dropdown-item")),t(e).is(":disabled")&&l.addClass("disabled"),this.$ul.append(l),t("option",e).each(t.proxy(function(t,e){this.createOptionValue(e)},this))},buildSelectAll:function(){if("number"==typeof this.options.selectAllValue&&(this.options.selectAllValue=this.options.selectAllValue.toString()),!this.hasSelectAll()&&this.options.includeSelectAllOption&&this.options.multiple&&t("option",this.$select).length>this.options.includeSelectAllIfMoreThan){this.options.includeSelectAllDivider&&this.$ul.prepend(t(this.options.templates.divider));var e=t(this.options.templates.li);t("label",e).addClass("form-check-label"),this.options.enableHTML?t("label",e).html(" "+this.options.selectAllText+"<span class='form-check-control-indicator' />"):t("label",e).text(" "+this.options.selectAllText),this.options.selectAllName?t("label",e).prepend('<input type="checkbox" name="'+this.options.selectAllName+'" />'):t("label",e).prepend('<input type="checkbox" />');var i=t("input",e);i.val(this.options.selectAllValue),e.addClass("multiselect-item multiselect-all"),this.$ul.prepend(e),i.prop("checked",!1)}},buildFilter:function(){if(this.options.enableFiltering||this.options.enableCaseInsensitiveFiltering){var e=Math.max(this.options.enableFiltering,this.options.enableCaseInsensitiveFiltering);if(this.$select.find("option").length>=e){if(this.$filter=t(this.options.templates.filter),t("input",this.$filter).attr("placeholder",this.options.filterPlaceholder),this.options.includeFilterClearBtn){var i=t(this.options.templates.filterClearBtn);i.on("click",t.proxy(function(e){clearTimeout(this.searchTimeout),this.$filter.find(".multiselect-search").val(""),t(".multiselect-item",this.$ul).show().removeClass("multiselect-filter-hidden"),this.updateSelectAll(),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups()},this)),this.$filter.find(".input-group").append(i)}this.$ul.prepend(this.$filter),this.$filter.val(this.query).on("click",function(t){t.stopPropagation()}).on("input keydown",t.proxy(function(e){13===e.which&&e.preventDefault(),clearTimeout(this.searchTimeout),this.searchTimeout=this.asyncFunction(t.proxy(function(){var i,s;this.query!==e.target.value&&(this.query=e.target.value,t.each(t(".multiselect-item",this.$ul),t.proxy(function(e,l){var o=t("input",l).length>0?t("input",l).val():"",n=t("label",l).text(),a="";if("text"===this.options.filterBehavior?a=n:"value"===this.options.filterBehavior?a=o:"both"===this.options.filterBehavior&&(a=n+"\n"+o),o!==this.options.selectAllValue&&n){var p=!1;if(this.options.enableCaseInsensitiveFiltering&&(a=a.toLowerCase(),this.query=this.query.toLowerCase()),this.options.enableFullValueFiltering&&"both"!==this.options.filterBehavior){var c=a.trim().substring(0,this.query.length);this.query.indexOf(c)>-1&&(p=!0)}else a.indexOf(this.query)>-1&&(p=!0);t(l).toggle(p).toggleClass("multiselect-filter-hidden",!p),t(l).hasClass("multiselect-group")?(i=l,s=p):(p&&t(i).show().removeClass("multiselect-filter-hidden"),!p&&s&&t(l).show().removeClass("multiselect-filter-hidden"))}},this)));this.updateSelectAll(),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups(),this.options.onFiltering(e.target)},this),300,this)},this))}}},destroy:function(){this.$container.remove(),this.$select.unwrap(),this.$select.prop("disabled",this.options.wasDisabled),this.$select.data("multiselect",null)},refresh:function(){var e=t.map(t(".multiselect-item input",this.$ul),t);t("option",this.$select).each(t.proxy(function(i,s){for(var l,o=t(s),n=o.val(),a=e.length;0<a--;)if(n===(l=e[a]).val()){o.is(":selected")?(l.prop("checked",!0),this.options.selectedClass&&l.closest(".multiselect-item").addClass(this.options.selectedClass)):(l.prop("checked",!1),this.options.selectedClass&&l.closest(".multiselect-item").removeClass(this.options.selectedClass)),o.is(":disabled")?l.attr("disabled","disabled").prop("disabled",!0).closest(".multiselect-item").addClass("disabled"):l.prop("disabled",!1).closest(".multiselect-item").removeClass("disabled");break}},this)),this.updateButtonText(),this.updateSelectAll(),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups()},select:function(e,i){t.isArray(e)||(e=[e]);for(var s=0;s<e.length;s++){var l=e[s];if(null!=l){var o=this.getOptionByValue(l),n=this.getInputByValue(l);void 0!==o&&void 0!==n&&(this.options.multiple||this.deselectAll(!1),this.options.selectedClass&&n.closest(".multiselect-item").addClass(this.options.selectedClass),n.prop("checked",!0),o.prop("selected",!0),i&&this.options.onChange(o,!0))}}this.updateButtonText(),this.updateSelectAll(),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups()},clearSelection:function(){this.deselectAll(!1),this.updateButtonText(),this.updateSelectAll(),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups()},deselect:function(e,i){t.isArray(e)||(e=[e]);for(var s=0;s<e.length;s++){var l=e[s];if(null!=l){var o=this.getOptionByValue(l),n=this.getInputByValue(l);void 0!==o&&void 0!==n&&(this.options.selectedClass&&n.closest(".multiselect-item").removeClass(this.options.selectedClass),n.prop("checked",!1),o.prop("selected",!1),i&&this.options.onChange(o,!1))}}this.updateButtonText(),this.updateSelectAll(),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups()},selectAll:function(e,i){e=void 0===e||e;var s=t(".multiselect-item:not(.dropdown-divider):not(.disabled):not(.multiselect-group)",this.$ul),l=t(".multiselect-item:not(.dropdown-divider):not(.disabled):not(.multiselect-group):not(.multiselect-filter-hidden):not(.multiselect-collapisble-hidden)",this.$ul).filter(":visible");e?(t("input:enabled",l).prop("checked",!0),l.addClass(this.options.selectedClass),t("input:enabled",l).each(t.proxy(function(e,i){var s=t(i).val(),l=this.getOptionByValue(s);t(l).prop("selected",!0)},this))):(t("input:enabled",s).prop("checked",!0),s.addClass(this.options.selectedClass),t("input:enabled",s).each(t.proxy(function(e,i){var s=t(i).val(),l=this.getOptionByValue(s);t(l).prop("selected",!0)},this))),t('.multiselect-item input[value="'+this.options.selectAllValue+'"]',this.$ul).prop("checked",!0),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups(),i&&this.options.onSelectAll()},deselectAll:function(e,i){e=void 0===e||e;var s=t(".multiselect-item:not(.dropdown-divider):not(.disabled):not(.multiselect-group)",this.$ul),l=t(".multiselect-item:not(.dropdown-divider):not(.disabled):not(.multiselect-group):not(.multiselect-filter-hidden):not(.multiselect-collapisble-hidden)",this.$ul).filter(":visible");e?(t('input[type="checkbox"]:enabled',l).prop("checked",!1),l.removeClass(this.options.selectedClass),t('input[type="checkbox"]:enabled',l).each(t.proxy(function(e,i){var s=t(i).val(),l=this.getOptionByValue(s);t(l).prop("selected",!1)},this))):(t('input[type="checkbox"]:enabled',s).prop("checked",!1),s.removeClass(this.options.selectedClass),t('input[type="checkbox"]:enabled',s).each(t.proxy(function(e,i){var s=t(i).val(),l=this.getOptionByValue(s);t(l).prop("selected",!1)},this))),t('.multiselect-item input[value="'+this.options.selectAllValue+'"]',this.$ul).prop("checked",!1),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups(),i&&this.options.onDeselectAll()},rebuild:function(){this.$ul.html(""),this.options.multiple="multiple"===this.$select.attr("multiple"),this.buildSelectAll(),this.buildDropdownOptions(),this.buildFilter(),this.updateButtonText(),this.updateSelectAll(!0),this.options.enableClickableOptGroups&&this.options.multiple&&this.updateOptGroups(),this.options.disableIfEmpty&&t("option",this.$select).length<=0?this.disable():this.enable(),this.options.dropRight&&this.$ul.addClass("dropdown-menu-right")},dataprovider:function(e){var i=0,s=this.$select.empty();t.each(e,function(e,l){var o;if(t.isArray(l.children))i++,o=t("<optgroup/>").attr({label:l.label||"Group "+i,disabled:!!l.disabled}),function(t,e){for(var i=0;i<t.length;++i)e(t[i],i)}(l.children,function(e){var i={value:e.value,label:e.label||e.value,title:e.title,selected:!!e.selected,disabled:!!e.disabled};for(var s in e.attributes)i["data-"+s]=e.attributes[s];o.append(t("<option/>").attr(i))});else{var n={value:l.value,label:l.label||l.value,title:l.title,class:l.class,selected:!!l.selected,disabled:!!l.disabled};for(var a in l.attributes)n["data-"+a]=l.attributes[a];(o=t("<option/>").attr(n)).text(l.label||l.value)}s.append(o)}),this.rebuild()},enable:function(){this.$select.prop("disabled",!1),this.$button.prop("disabled",!1).removeClass("disabled")},disable:function(){this.$select.prop("disabled",!0),this.$button.prop("disabled",!0).addClass("disabled")},setOptions:function(t){this.options=this.mergeOptions(t)},mergeOptions:function(e){return t.extend(!0,{},this.defaults,this.options,e)},hasSelectAll:function(){return t("a.multiselect-all",this.$ul).length>0},updateOptGroups:function(){var e=t(".multiselect-group",this.$ul),i=this.options.selectedClass;e.each(function(){var e=t(this).nextUntil(".multiselect-group").not(".multiselect-filter-hidden").not(".disabled"),s=!0;e.each(function(){t("input",this).prop("checked")||(s=!1)}),i&&(s?t(this).addClass(i):t(this).removeClass(i)),t("input",this).prop("checked",s)})},updateSelectAll:function(e){if(this.hasSelectAll()){var i=t(".dropdown-item:not(.multiselect-group):not(.multiselect-all):not(.disabled) input:enabled",this.$ul),s=i.length,l=i.filter(":checked").length,o=t("a.multiselect-all",this.$ul),n=o.find("input");l>0&&l===s?(n.prop("checked",!0),o.addClass(this.options.selectedClass).addClass("asflkjnsdflkjbsdhqf")):(n.prop("checked",!1),o.removeClass(this.options.selectedClass))}},updateButtonText:function(){var e=this.getSelected();this.options.enableHTML?t(".multiselect .multiselect-selected-text",this.$container).html(this.options.buttonText(e,this.$select)):t(".multiselect .multiselect-selected-text",this.$container).text(this.options.buttonText(e,this.$select)),t(".multiselect",this.$container).attr("title",this.options.buttonTitle(e,this.$select))},getSelected:function(){return t("option",this.$select).filter(":selected")},getOptionByValue:function(e){for(var i=t("option",this.$select),s=e.toString(),l=0;l<i.length;l+=1){var o=i[l];if(o.value===s)return t(o)}},getInputByValue:function(e){for(var i=t(".multiselect-item input:not(.multiselect-search)",this.$ul),s=e.toString(),l=0;l<i.length;l+=1){var o=i[l];if(o.value===s)return t(o)}},updateOriginalOptions:function(){this.originalOptions=this.$select.clone()[0].options},asyncFunction:function(t,e,i){var s=Array.prototype.slice.call(arguments,3);return setTimeout(function(){t.apply(i||window,s)},e)},setAllSelectedText:function(t){this.options.allSelectedText=t,this.updateButtonText()}},t.fn.multiselect=function(i,s,l){return this.each(function(){var o=t(this).data("multiselect");o||(o=new e(this,"object"==typeof i&&i),t(this).data("multiselect",o)),"string"==typeof i&&(o[i](s,l),"destroy"===i&&t(this).data("multiselect",!1))})},t.fn.multiselect.Constructor=e,t(function(){t("select[data-role=multiselect]").multiselect()})}(window.jQuery);