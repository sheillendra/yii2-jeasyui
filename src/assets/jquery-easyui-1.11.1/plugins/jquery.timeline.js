/**
 * EasyUI for jQuery 1.11.1
 * 
 * Copyright (c) 2009-2025 www.jeasyui.com. All rights reserved.
 *
 * Licensed under the freeware license: http://www.jeasyui.com/license_freeware.php
 * To use it on other terms please contact us: info@jeasyui.com
 *
 */
 (function($){
function _1(_2){
var _3=$.data(_2,"timeline").options;
$(_2).addClass("timeline-container");
$(_2).off(".timeline").on("click.timeline",".timeline-item",function(){
var _4=$(this).index(".timeline-item");
_3.onClick.call(_2,_3.data[_4]);
});
$(_2)._bind("_resize",function(e,_5){
if($(this).hasClass("easyui-fluid")||_5){
_6(_2);
}
return false;
});
};
function _6(_7,_8){
var _9=$.data(_7,"timeline").options;
var t=$(_7);
if(_8){
$.extend(_9,{width:_8.width,height:_8.height});
}
t._size(_9,t.parent());
};
function _a(_b,_c){
var _d=$(_b).timeline("options");
_d.data=_c;
$(_b).empty();
var ul=$("<ul class=\"timeline\"></ul").appendTo(_b);
for(var i=0;i<_d.data.length;i++){
var _e=_d.data[i];
var li=$("<li class=\"timeline-item\"></li>").appendTo(ul);
var _f=$("<div class=\"timeline-item-line\"></div>").appendTo(li);
var dot=$("<div class=\"timeline-item-dot\"></div>").appendTo(li);
var _10=_d.dotFormatter.call(_b,_e);
if(_10){
dot.addClass("timeline-item-dot-custom").html(_10);
}
var _11=_d.dotStyler.call(_b,_e);
if(_11){
dot.css(_11);
}
var _12=$("<div class=\"timeline-item-content\"></div>").appendTo(li);
var _13=_d.formatter.call(_b,_e);
_12.html(_13);
}
};
$.fn.timeline=function(_14,_15){
if(typeof _14=="string"){
return $.fn.timeline.methods[_14](this,_15);
}
_14=_14||{};
return this.each(function(){
var _16=$.data(this,"timeline");
if(_16){
$.extend(_16.options,_14);
}else{
_16=$.data(this,"timeline",{options:$.extend({},$.fn.timeline.defaults,$.fn.timeline.parseOptions(this),_14)});
_1(this);
}
_6(this);
_a(this,_16.options.data);
});
};
$.fn.timeline.methods={options:function(jq){
return $.data(jq[0],"timeline").options;
},loadData:function(jq,_17){
return jq.each(function(){
_a(this,_17);
});
}};
$.fn.timeline.parseOptions=function(_18){
var t=$(_18);
return $.extend({},$.parser.parseOptions(_18,[]));
};
$.fn.timeline.defaults={width:"auto",height:"auto",data:[],dotStyler:function(row){
return "";
},dotFormatter:function(row){
return null;
},formatter:function(row){
return row["content"];
},onClick:function(row){
}};
})(jQuery);

