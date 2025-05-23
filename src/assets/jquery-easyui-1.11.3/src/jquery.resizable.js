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
 * resizable - EasyUI for jQuery
 * 
 */
(function($){
	function resize(e){
		var resizeData = e.data;
		var options = $.data(resizeData.target, 'resizable').options;
		if (resizeData.dir.indexOf('e') != -1) {
			var width = resizeData.startWidth + e.pageX - resizeData.startX;
			width = Math.min(
						Math.max(width, options.minWidth),
						options.maxWidth
					);
			resizeData.width = width;
		}
		if (resizeData.dir.indexOf('s') != -1) {
			var height = resizeData.startHeight + e.pageY - resizeData.startY;
			height = Math.min(
					Math.max(height, options.minHeight),
					options.maxHeight
			);
			resizeData.height = height;
		}
		if (resizeData.dir.indexOf('w') != -1) {
			var width = resizeData.startWidth - e.pageX + resizeData.startX;
			width = Math.min(
						Math.max(width, options.minWidth),
						options.maxWidth
					);
			resizeData.width = width;
			resizeData.left = resizeData.startLeft + resizeData.startWidth - resizeData.width;
		}
		if (resizeData.dir.indexOf('n') != -1) {
			var height = resizeData.startHeight - e.pageY + resizeData.startY;
			height = Math.min(
						Math.max(height, options.minHeight),
						options.maxHeight
					);
			resizeData.height = height;
			resizeData.top = resizeData.startTop + resizeData.startHeight - resizeData.height;
		}
	}
	
	function applySize(e){
		var resizeData = e.data;
		var t = $(resizeData.target);
		t.css({
			left: resizeData.left,
			top: resizeData.top
		});
		if (t.outerWidth() != resizeData.width){t._outerWidth(resizeData.width)}
		if (t.outerHeight() != resizeData.height){t._outerHeight(resizeData.height)}
	}
	
	function doDown(e){
		$.fn.resizable.isResizing = true;
		$.data(e.data.target, 'resizable').options.onStartResize.call(e.data.target, e);
		return false;
	}
	
	function doMove(e){
		resize(e);
		if ($.data(e.data.target, 'resizable').options.onResize.call(e.data.target, e) != false){
			applySize(e)
		}
		return false;
	}
	
	function doUp(e){
		$.fn.resizable.isResizing = false;
		resize(e, true);
		applySize(e);
		$.data(e.data.target, 'resizable').options.onStopResize.call(e.data.target, e);
		$(document)._unbind('.resizable');
		$('body').css('cursor','');
		return false;
	}

	// get the resize direction
	function getDirection(e) {
		var opts = $(e.data.target).resizable('options');
		var tt = $(e.data.target);
		var dir = '';
		var offset = tt.offset();
		var width = tt.outerWidth();
		var height = tt.outerHeight();
		var edge = opts.edge;
		if (e.pageY > offset.top && e.pageY < offset.top + edge) {
			dir += 'n';
		} else if (e.pageY < offset.top + height && e.pageY > offset.top + height - edge) {
			dir += 's';
		}
		if (e.pageX > offset.left && e.pageX < offset.left + edge) {
			dir += 'w';
		} else if (e.pageX < offset.left + width && e.pageX > offset.left + width - edge) {
			dir += 'e';
		}
		
		var handles = opts.handles.split(',');
		handles = $.map(handles, function(h){return $.trim(h).toLowerCase();});
		if ($.inArray('all', handles) >= 0 || $.inArray(dir, handles) >= 0){
			return dir;
		}
		for(var i=0; i<dir.length; i++){
			var index = $.inArray(dir.substr(i,1), handles);
			if (index >= 0){
				return handles[index];
			}
		}
		return '';
	}

	$.fn.resizable = function(options, param){
		if (typeof options == 'string'){
			return $.fn.resizable.methods[options](this, param);
		}
		
		return this.each(function(){
			var opts = null;
			var state = $.data(this, 'resizable');
			if (state) {
				$(this)._unbind('.resizable');
				opts = $.extend(state.options, options || {});
			} else {
				opts = $.extend({}, $.fn.resizable.defaults, $.fn.resizable.parseOptions(this), options || {});
				$.data(this, 'resizable', {
					options:opts
				});
			}
			
			if (opts.disabled == true) {
				return;
			}
			$(this)._bind('mousemove.resizable', {target:this}, function(e){
				if ($.fn.resizable.isResizing){return}
				var dir = getDirection(e);
				$(e.data.target).css('cursor', dir ? dir+'-resize' : '');
			})._bind('mouseleave.resizable', {target:this}, function(e){
				$(e.data.target).css('cursor', '');
			})._bind('mousedown.resizable', {target:this}, function(e){
				var dir = getDirection(e);
				if (dir == ''){return;}
				
				function getCssValue(css) {
					var val = parseInt($(e.data.target).css(css));
					if (isNaN(val)) {
						return 0;
					} else {
						return val;
					}
				}
				
				var data = {
					target: e.data.target,
					dir: dir,
					startLeft: getCssValue('left'),
					startTop: getCssValue('top'),
					left: getCssValue('left'),
					top: getCssValue('top'),
					startX: e.pageX,
					startY: e.pageY,
					startWidth: $(e.data.target).outerWidth(),
					startHeight: $(e.data.target).outerHeight(),
					width: $(e.data.target).outerWidth(),
					height: $(e.data.target).outerHeight(),
					deltaWidth: $(e.data.target).outerWidth() - $(e.data.target).width(),
					deltaHeight: $(e.data.target).outerHeight() - $(e.data.target).height()
				};
				$(document)._bind('mousedown.resizable', data, doDown);
				$(document)._bind('mousemove.resizable', data, doMove);
				$(document)._bind('mouseup.resizable', data, doUp);
				$('body').css('cursor', dir+'-resize');
			});
		});
	};
	
	$.fn.resizable.methods = {
		options: function(jq){
			return $.data(jq[0], 'resizable').options;
		},
		enable: function(jq){
			return jq.each(function(){
				$(this).resizable({disabled:false});
			});
		},
		disable: function(jq){
			return jq.each(function(){
				$(this).resizable({disabled:true});
			});
		}
	};
	
	$.fn.resizable.parseOptions = function(target){
		var t = $(target);
		return $.extend({},
				$.parser.parseOptions(target, [
					'handles',{minWidth:'number',minHeight:'number',maxWidth:'number',maxHeight:'number',edge:'number'}
				]), {
			disabled: (t.attr('disabled') ? true : undefined)
		})
	};
	
	$.fn.resizable.defaults = {
		disabled:false,
		handles:'n, e, s, w, ne, se, sw, nw, all',
		minWidth: 10,
		minHeight: 10,
		maxWidth: 10000,//$(document).width(),
		maxHeight: 10000,//$(document).height(),
		edge:5,
		onStartResize: function(e){},
		onResize: function(e){},
		onStopResize: function(e){}
	};
	
	$.fn.resizable.isResizing = false;
	
})(jQuery);
