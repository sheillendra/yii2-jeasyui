/**
 * EasyUI for jQuery 1.11.3
 * 
 * Copyright (c) 2009-2025 www.jeasyui.com. All rights reserved.
 *
 * Licensed under the freeware license: http://www.jeasyui.com/license_freeware.php
 * To use it on other terms please contact us: info@jeasyui.com
 *
 */
 /**
 * droppable - EasyUI for jQuery
 * 
 */
(function($){
	function init(target){
		$(target).addClass('droppable');
		$(target)._bind('_dragenter', function(e, source){
			$.data(target, 'droppable').options.onDragEnter.apply(target, [e, source]);
		});
		$(target)._bind('_dragleave', function(e, source){
			$.data(target, 'droppable').options.onDragLeave.apply(target, [e, source]);
		});
		$(target)._bind('_dragover', function(e, source){
			$.data(target, 'droppable').options.onDragOver.apply(target, [e, source]);
		});
		$(target)._bind('_drop', function(e, source){
			$.data(target, 'droppable').options.onDrop.apply(target, [e, source]);
		});
	}
	
	$.fn.droppable = function(options, param){
		if (typeof options == 'string'){
			return $.fn.droppable.methods[options](this, param);
		}
		
		options = options || {};
		return this.each(function(){
			var state = $.data(this, 'droppable');
			if (state){
				$.extend(state.options, options);
			} else {
				init(this);
				$.data(this, 'droppable', {
					options: $.extend({}, $.fn.droppable.defaults, $.fn.droppable.parseOptions(this), options)
				});
			}
		});
	};
	
	$.fn.droppable.methods = {
		options: function(jq){
			return $.data(jq[0], 'droppable').options;
		},
		enable: function(jq){
			return jq.each(function(){
				$(this).droppable({disabled:false});
			});
		},
		disable: function(jq){
			return jq.each(function(){
				$(this).droppable({disabled:true});
			});
		}
	};
	
	$.fn.droppable.parseOptions = function(target){
		var t = $(target);
		return $.extend({},	$.parser.parseOptions(target, ['accept']), {
			disabled: (t.attr('disabled') ? true : undefined)
		});
	};
	
	$.fn.droppable.defaults = {
		accept:null,
		disabled:false,
		onDragEnter:function(e, source){},
		onDragOver:function(e, source){},
		onDragLeave:function(e, source){},
		onDrop:function(e, source){}
	};
})(jQuery);
