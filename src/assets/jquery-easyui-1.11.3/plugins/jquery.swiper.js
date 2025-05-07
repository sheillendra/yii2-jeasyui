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
var _3=$.data(_2,"swiper");
var _4=_3.options;
$(_2).addClass("swiper").wrapInner("<div class=\"swiper-inner\"></div>");
var _5=$(_2).find(".swiper-inner");
var _6=$("<div class=\"swiper-dots\"></div>").appendTo(_2);
_5.off("transitionend.swiper").on("transitionend.swiper",function(){
_11(_2);
});
_6.off("click.swiper").on("click.swiper",".swiper-dot",function(){
var _7=$(this).index(".swiper-dot");
_4.selected=_7;
_5.children("div").each(function(_8){
$(this).css("transform","translate("+(_8*100)+"%,0px) translateZ(0px)");
});
_5.css({transform:"translate("+(-_7*100)+"%,0px) translateZ(0px)"});
_4.onChange.call(_2,_4.selected);
});
$(_2)._bind("_resize",function(e,_9){
if($(this).hasClass("easyui-fluid")||_9){
_a(_2);
}
return false;
});
};
function _b(_c){
var _d=$.data(_c,"swiper");
var _e=_d.options;
var _f=$(_c).children(".swiper-inner").children("div").length;
var _10=$(_c).children(".swiper-dots");
_10.empty();
if(_e.indicator){
for(var i=0;i<_f;i++){
$("<div class=\"swiper-dot\"></div>").appendTo(_10);
}
}
_11(_c);
if(_e.autoplay){
_d.timer=setInterval(function(){
nav(_c,1);
},_e.interval);
}else{
clearInterval(_d.timer);
}
};
function _a(_12,_13){
var _14=$.data(_12,"swiper").options;
var t=$(_12);
if(_13){
$.extend(_14,{width:_13.width,height:_13.height});
}
t._size(_14,t.parent());
};
function _11(_15){
var _16=$(_15).swiper("options");
var _17=$(_15).find(".swiper-inner").children("div").length;
var _18=$(_15).find(".swiper-inner");
_18.css({transform:"translate("+(-_16.selected*100)+"%,0px) translateZ(0px)",transitionDuration:"0s"});
_18.children("div").each(function(_19){
var val=_19*100+"%";
var _1a="translate("+val+",0px) translateZ(0px)";
if(_16.selected==0&&_19==_17-1){
_1a="translate(-100%,0px) translateZ(0px)";
}else{
if(_16.selected==_17-1&&_19==0){
_1a="translate("+(_17*100)+"%,0px) translateZ(0px)";
}
}
$(this).css({position:"absolute",width:"100%",height:"100%",transform:_1a,willChange:"transform"});
});
_18.css({transitionDuration:_16.duration/1000+"s"});
var _1b=$(_15).find(".swiper-dots");
_1b.find(".swiper-dot").removeClass("swiper-dot-active");
_1b.find(".swiper-dot:nth-child("+(_16.selected+1)+")").addClass("swiper-dot-active");
};
function nav(_1c,_1d){
var _1e=$(_1c).swiper("options");
var _1f=$(_1c).find(".swiper-inner").children("div").length;
var _20=_1e.selected+_1d;
var val=-_20*100+"%";
if(_20<0){
_20=_1f-1;
}else{
if(_20>=_1f){
_20=0;
}
}
_1e.selected=_20;
var _21=$(_1c).find(".swiper-inner");
_21.css({transform:"translate("+val+",0px) translateZ(0px)"});
_1e.onChange.call(_1c,_1e.selected);
};
$.fn.swiper=function(_22,_23){
if(typeof _22=="string"){
return $.fn.swiper.methods[_22](this,_23);
}
_22=_22||{};
return this.each(function(){
var _24=$.data(this,"swiper");
if(_24){
$.extend(_24.options,_22);
}else{
_24=$.data(this,"swiper",{options:$.extend({},$.fn.swiper.defaults,$.fn.swiper.parseOptions(this),_22)});
_1(this);
}
_b(this);
_a(this);
});
};
$.fn.swiper.methods={options:function(jq){
return $.data(jq[0],"swiper").options;
},resize:function(jq,_25){
return jq.each(function(){
_a(this,_25);
});
},navNext:function(jq){
return jq.each(function(){
nav(this,1);
});
},navPrev:function(jq){
return jq.each(function(){
nav(this,-1);
});
}};
$.fn.swiper.parseOptions=function(_26){
var t=$(_26);
return $.extend({},$.parser.parseOptions(_26,[{selected:"number",duration:"number",interval:"number"},{autoplay:"boolean",indicator:"boolean"}]));
};
$.fn.swiper.defaults={width:"auto",height:200,selected:0,duration:400,interval:2000,autoplay:false,indicator:true,onChange:function(_27){
}};
})(jQuery);

