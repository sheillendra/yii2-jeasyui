/**
 * EasyUI for jQuery 1.11.3
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
ul.addClass("timeline-"+_d.align);
if(_d.align=="alternateReverse"){
ul.addClass("timeline-alternate");
}
var _e=false;
for(var i=0;i<_d.data.length;i++){
var _f=_d.data[i];
var li=$("<li class=\"timeline-item\"></li>").appendTo(ul);
if(_d.align=="alternate"||_d.align=="alternateReverse"){
var pos=_d.itemPosition.call(_b,_f,i);
if(!pos){
if(_d.align=="alternate"){
pos=(i+1)%2==1?"left":"right";
}else{
pos=(i+1)%2==1?"right":"left";
}
}
li.addClass("timeline-item-"+pos);
}
var _10=$("<div class=\"timeline-item-line\"></div>").appendTo(li);
var dot=$("<div class=\"timeline-item-dot\"></div>").appendTo(li);
var _11=_d.dotFormatter.call(_b,_f);
if(_11){
dot.addClass("timeline-item-dot-custom").html(_11);
}
var _12=_d.dotStyler.call(_b,_f);
if(_12){
dot.css(_12);
}
var _13=_d.labelFormatter.call(_b,_f);
if(_13){
$("<div class=\"timeline-item-label\"></div>").html(_13).appendTo(li);
_e=true;
}
var _14=$("<div class=\"timeline-item-content\"></div>").appendTo(li);
var _15=_d.formatter.call(_b,_f);
_14.html(_15);
}
if(_e){
ul.addClass("timeline-label");
}
};
$.fn.timeline=function(_16,_17){
if(typeof _16=="string"){
return $.fn.timeline.methods[_16](this,_17);
}
_16=_16||{};
return this.each(function(){
var _18=$.data(this,"timeline");
if(_18){
$.extend(_18.options,_16);
}else{
_18=$.data(this,"timeline",{options:$.extend({},$.fn.timeline.defaults,$.fn.timeline.parseOptions(this),_16)});
_1(this);
}
_6(this);
_a(this,_18.options.data);
});
};
$.fn.timeline.methods={options:function(jq){
return $.data(jq[0],"timeline").options;
},loadData:function(jq,_19){
return jq.each(function(){
_a(this,_19);
});
}};
$.fn.timeline.parseOptions=function(_1a){
var t=$(_1a);
return $.extend({},$.parser.parseOptions(_1a,["align"]));
};
$.fn.timeline.defaults={width:"auto",height:"auto",align:"left",data:[],dotStyler:function(row){
return "";
},dotFormatter:function(row){
return null;
},labelFormatter:function(row){
return row["label"];
},formatter:function(row){
return row["content"];
},itemPosition:function(row,_1b){
return null;
},onClick:function(row){
}};
})(jQuery);

