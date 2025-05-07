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
$(_2).addClass("rate-f").hide();
var _3=$("<span class=\"rate\"><input type=\"hidden\" class=\"rate-value\"></span>").insertAfter(_2);
var _4=$(_2).attr("name");
if(_4){
_3.find("input.rate-value").attr("name",_4);
$(_2).removeAttr("name").attr("rateName",_4);
}
return _3;
};
function _5(_6){
var _7=$.data(_6,"rate");
var _8=_7.options;
_7.rate.children(".rate-item").remove();
for(var i=0;i<_8.max;i++){
var _9=$("<span class=\"rate-item\"></span>").appendTo(_7.rate).css({width:_8.size,height:_8.size});
var _a=$("<span class=\"rate-first\"></span>").appendTo(_9);
$(_8.starSvg).appendTo(_a).css({width:_8.size,height:_8.size,color:_8.color});
var _b=$("<span class=\"rate-second\"></span>").appendTo(_9);
$(_8.starSvg).appendTo(_b).css({width:_8.size,height:_8.size,color:_8.color});
_9.attr("rate-index",i);
}
if(_8.half){
_7.rate.addClass("rate-half");
}else{
_7.rate.removeClass("rate-half");
}
_c(_6,_8.disabled);
_d(_6,_8.readonly);
};
function _e(_f){
var _10=$.data(_f,"rate");
var _11=_10.options;
_10.rate.off(".rate");
if(_11.readonly||_11.disabled){
return;
}
_10.rate.on("mouseover.rate",".rate-first",function(){
var _12=$(this).closest(".rate-item");
var _13=parseInt(_12.attr("rate-index"));
_1a(_f,_13+0.5);
}).on("mouseout.rate",".rate-first",function(){
$(_f).rate("initValue",$(_f).rate("getValue"));
}).on("click.rate",".rate-first",function(){
var _14=$(this).closest(".rate-item");
var _15=parseInt(_14.attr("rate-index"));
$(_f).rate("setValue",_15+0.5);
}).on("mouseover.rate",".rate-second",function(){
var _16=$(this).closest(".rate-item");
var _17=parseInt(_16.attr("rate-index"));
_1a(_f,_17+1);
}).on("mouseout.rate",".rate-second",function(){
$(_f).rate("initValue",$(_f).rate("getValue"));
}).on("click.rate",".rate-second",function(){
var _18=$(this).closest(".rate-item");
var _19=parseInt(_18.attr("rate-index"));
$(_f).rate("setValue",_19+1);
});
};
function _1a(_1b,_1c){
var _1d=$.data(_1b,"rate");
var _1e=_1d.options;
var _1f=_1d.rate.children(".rate-item");
_1f.find("svg").css({color:_1e.color});
for(var i=0;i<Math.floor(_1c);i++){
var _20=_1f[i];
$(_20).find("svg").css({color:_1e.selectedColor});
}
if(_1c>Math.floor(_1c)){
var _20=_1f[Math.floor(_1c)];
$(_20).find(".rate-first svg").css({color:_1e.selectedColor});
}
};
function _c(_21,_22){
var _23=$.data(_21,"rate");
var _24=_23.options;
_24.disabled=_22;
var _25=_23.rate.find(".rate-value");
if(_22){
_23.rate.addClass("rate-disabled");
_25._propAttr("disabled",true);
}else{
_23.rate.removeClass("rate-disabled");
_25._propAttr("disabled",false);
}
};
function _d(_26,_27){
var _28=$.data(_26,"rate");
var _29=_28.options;
_29.readonly=_27==undefined?true:_27;
if(_29.readonly){
_28.rate.addClass("rate-readonly");
}else{
_28.rate.removeClass("rate-readonly");
}
};
$.fn.rate=function(_2a,_2b){
if(typeof _2a=="string"){
return $.fn.rate.methods[_2a](this,_2b);
}
_2a=_2a||{};
return this.each(function(){
var _2c=$.data(this,"rate");
if(_2c){
$.extend(_2c.options,_2a);
if(_2a.value!=undefined){
_2c.options.originalValue=_2a.value;
}
}else{
_2c=$.data(this,"rate",{options:$.extend({},$.fn.rate.defaults,$.fn.rate.parseOptions(this),_2a),rate:_1(this)});
_2c.options.originalValue=_2c.options.value;
}
_5(this);
_e(this);
_2c.options.value=_2c.options.value||0;
$(this).rate("initValue",_2c.options.value);
});
};
$.fn.rate.methods={options:function(jq){
return $.data(jq[0],"rate").options;
},initValue:function(jq,_2d){
return jq.each(function(){
var _2e=$(this).rate("options");
if(_2d>_2e.max){
_2d=_2e.max;
}
_2e.value=_2d;
$(this).next().find(".rate-value").val(_2d);
_1a(this,_2d);
});
},setValue:function(jq,_2f){
return jq.each(function(){
var _30=$(this).rate("options");
if(_2f>_30.max){
_2f=_30.max;
}
var _31=$(this).rate("getValue");
$(this).rate("initValue",_2f);
if(_31!=_2f){
_30.onChange.call(this,_2f,_31);
$(this).closest("form").trigger("_change",[this]);
}
});
},destroy:function(jq){
jq.each(function(){
$(this).next().remove();
$(this).remove();
});
},clear:function(jq){
return jq.each(function(){
$(this).rate("setValue",0);
});
},reset:function(jq){
return jq.each(function(){
var _32=$(this).rate("options");
$(this).rate("setValue",_32.originalValue);
});
},getValue:function(jq){
return jq.data("rate").rate.find(".rate-value").val();
},disable:function(jq){
return jq.each(function(){
_c(this,true);
_e(this);
});
},enable:function(jq){
return jq.each(function(){
_c(this,false);
_e(this);
});
},readonly:function(jq,_33){
return jq.each(function(){
_d(this,_33);
_e(this);
});
}};
$.fn.rate.parseOptions=function(_34){
var t=$(_34);
return $.extend({},$.parser.parseOptions(_34,["color","selectedColor",{max:"number",size:"number"},{half:"boolean",readonly:"boolean",disabled:"boolean"}]),{value:(t.val()||0),disabled:(t.attr("disabled")?true:undefined),readonly:(t.attr("readonly")?true:undefined)});
};
$.fn.rate.defaults={value:0,max:5,size:24,half:false,readonly:false,disabled:false,starSvg:"<svg focusable=\"false\" viewBox=\"0 0 24 24\"><path d=\"M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z\"></path></svg>",color:"#ececec",selectedColor:"#ffca3e",onChange:function(_35,_36){
}};
})(jQuery);

