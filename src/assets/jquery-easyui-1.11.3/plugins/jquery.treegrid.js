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
var _3=$.data(_2,"treegrid");
var _4=_3.options;
$(_2).datagrid($.extend({},_4,{url:null,data:null,loader:function(){
return false;
},onBeforeLoad:function(){
return false;
},onLoadSuccess:function(){
},onResizeColumn:function(_5,_6){
_16(_2);
_4.onResizeColumn.call(_2,_5,_6);
},onBeforeSortColumn:function(_7,_8){
if(_4.onBeforeSortColumn.call(_2,_7,_8)==false){
return false;
}
},onSortColumn:function(_9,_a){
_4.sortName=_9;
_4.sortOrder=_a;
if(_4.remoteSort){
_15(_2);
}else{
var _b=$(_2).treegrid("getData");
_56(_2,null,_b);
}
_4.onSortColumn.call(_2,_9,_a);
},onClickCell:function(_c,_d){
_4.onClickCell.call(_2,_d,_37(_2,_c));
},onDblClickCell:function(_e,_f){
_4.onDblClickCell.call(_2,_f,_37(_2,_e));
},onRowContextMenu:function(e,_10){
_4.onContextMenu.call(_2,e,_37(_2,_10));
}}));
var _11=$.data(_2,"datagrid").options;
_4.columns=_11.columns;
_4.frozenColumns=_11.frozenColumns;
_3.dc=$.data(_2,"datagrid").dc;
if(_4.pagination){
var _12=$(_2).datagrid("getPager");
_12.pagination({total:0,pageNumber:_4.pageNumber,pageSize:_4.pageSize,pageList:_4.pageList,onSelectPage:function(_13,_14){
_4.pageNumber=_13||1;
_4.pageSize=_14;
_12.pagination("refresh",{pageNumber:_13,pageSize:_14});
_15(_2);
}});
_4.pageSize=_12.pagination("options").pageSize;
}
};
function _16(_17,_18){
var _19=$.data(_17,"datagrid").options;
var dc=$.data(_17,"datagrid").dc;
if(!dc.body1.is(":empty")&&(!_19.nowrap||_19.autoRowHeight)){
if(_18!=undefined){
var _1a=_1b(_17,_18);
for(var i=0;i<_1a.length;i++){
_1c(_1a[i][_19.idField]);
}
}
}
$(_17).datagrid("fixRowHeight",_18);
function _1c(_1d){
var tr1=_19.finder.getTr(_17,_1d,"body",1);
var tr2=_19.finder.getTr(_17,_1d,"body",2);
tr1.css("height","");
tr2.css("height","");
var _1e=Math.max(tr1.height(),tr2.height());
tr1.css("height",_1e);
tr2.css("height",_1e);
};
};
function _1f(_20){
var dc=$.data(_20,"datagrid").dc;
var _21=$.data(_20,"treegrid").options;
if(!_21.rownumbers){
return;
}
dc.body1.find("div.datagrid-cell-rownumber").each(function(i){
$(this).html(i+1);
});
};
function _22(_23){
return function(e){
$.fn.datagrid.defaults.rowEvents[_23?"mouseover":"mouseout"](e);
var tt=$(e.target);
var fn=_23?"addClass":"removeClass";
if(tt.hasClass("tree-hit")){
tt.hasClass("tree-expanded")?tt[fn]("tree-expanded-hover"):tt[fn]("tree-collapsed-hover");
}
};
};
function _24(e){
var tt=$(e.target);
var tr=tt.closest("tr.datagrid-row");
if(!tr.length||!tr.parent().length){
return;
}
var _25=tr.attr("node-id");
var _26=_27(tr);
if(tt.hasClass("tree-hit")){
_28(_26,_25);
}else{
if(tt.hasClass("tree-checkbox")){
_29(_26,_25);
}else{
var _2a=$(_26).datagrid("options");
if(!tt.parent().hasClass("datagrid-cell-check")&&!_2a.singleSelect&&e.shiftKey){
var _2b=$(_26).treegrid("getChildren");
var _2c=$.easyui.indexOfArray(_2b,_2a.idField,_2a.lastSelectedIndex);
var _2d=$.easyui.indexOfArray(_2b,_2a.idField,_25);
var _2e=Math.min(Math.max(_2c,0),_2d);
var to=Math.max(_2c,_2d);
var row=_2b[_2d];
var td=tt.closest("td[field]",tr);
if(td.length){
var _2f=td.attr("field");
_2a.onClickCell.call(_26,_25,_2f,row[_2f]);
}
$(_26).treegrid("clearSelections");
for(var i=_2e;i<=to;i++){
$(_26).treegrid("selectRow",_2b[i][_2a.idField]);
}
_2a.onClickRow.call(_26,row);
}else{
$.fn.datagrid.defaults.rowEvents.click(e);
}
}
}
};
function _27(t){
return $(t).closest("div.datagrid-view").children(".datagrid-f")[0];
};
function _29(_30,_31,_32,_33){
var _34=$.data(_30,"treegrid");
var _35=_34.checkedRows;
var _36=_34.options;
if(!_36.checkbox){
return;
}
var row=_37(_30,_31);
if(!row.checkState){
return;
}
var tr=_36.finder.getTr(_30,_31);
var ck=tr.find(".tree-checkbox");
if(_32==undefined){
if(ck.hasClass("tree-checkbox1")){
_32=false;
}else{
if(ck.hasClass("tree-checkbox0")){
_32=true;
}else{
if(row._checked==undefined){
row._checked=ck.hasClass("tree-checkbox1");
}
_32=!row._checked;
}
}
}
row._checked=_32;
if(_32){
if(ck.hasClass("tree-checkbox1")){
return;
}
}else{
if(ck.hasClass("tree-checkbox0")){
return;
}
}
if(!_33){
if(_36.onBeforeCheckNode.call(_30,row,_32)==false){
return;
}
}
if(_36.cascadeCheck){
_38(_30,row,_32);
_39(_30,row);
}else{
_3a(_30,row,_32?"1":"0");
}
if(!_33){
_36.onCheckNode.call(_30,row,_32);
}
};
function _3a(_3b,row,_3c){
var _3d=$.data(_3b,"treegrid");
var _3e=_3d.checkedRows;
var _3f=_3d.options;
if(!row.checkState||_3c==undefined){
return;
}
var tr=_3f.finder.getTr(_3b,row[_3f.idField]);
var ck=tr.find(".tree-checkbox");
if(!ck.length){
return;
}
row.checkState=["unchecked","checked","indeterminate"][_3c];
row.checked=(row.checkState=="checked");
ck.removeClass("tree-checkbox0 tree-checkbox1 tree-checkbox2");
ck.addClass("tree-checkbox"+_3c);
if(_3c==0){
$.easyui.removeArrayItem(_3e,_3f.idField,row[_3f.idField]);
}else{
$.easyui.addArrayItem(_3e,_3f.idField,row);
}
};
function _38(_40,row,_41){
var _42=_41?1:0;
_3a(_40,row,_42);
$.easyui.forEach(row.children||[],true,function(r){
_3a(_40,r,_42);
});
};
function _39(_43,row){
var _44=$.data(_43,"treegrid").options;
var _45=_46(_43,row[_44.idField]);
if(_45){
_3a(_43,_45,_47(_45));
_39(_43,_45);
}
};
function _47(row){
var len=0;
var c0=0;
var c1=0;
$.easyui.forEach(row.children||[],false,function(r){
if(r.checkState){
len++;
if(r.checkState=="checked"){
c1++;
}else{
if(r.checkState=="unchecked"){
c0++;
}
}
}
});
if(len==0){
return undefined;
}
var _48=0;
if(c0==len){
_48=0;
}else{
if(c1==len){
_48=1;
}else{
_48=2;
}
}
return _48;
};
function _49(_4a,_4b){
var _4c=$.data(_4a,"treegrid").options;
if(!_4c.checkbox){
return;
}
var row=_37(_4a,_4b);
var tr=_4c.finder.getTr(_4a,_4b);
var ck=tr.find(".tree-checkbox");
if(_4c.view.hasCheckbox(_4a,row)){
if(!ck.length){
row.checkState=row.checkState||"unchecked";
$("<span class=\"tree-checkbox\"></span>").insertBefore(tr.find(".tree-title"));
}
if(row.checkState=="checked"){
_29(_4a,_4b,true,true);
}else{
if(row.checkState=="unchecked"){
_29(_4a,_4b,false,true);
}else{
var _4d=_47(row);
if(_4d===0){
_29(_4a,_4b,false,true);
}else{
if(_4d===1){
_29(_4a,_4b,true,true);
}
}
}
}
}else{
ck.remove();
row.checkState=undefined;
row.checked=undefined;
_39(_4a,row);
}
};
function _4e(_4f,_50){
var _51=$.data(_4f,"treegrid").options;
var tr1=_51.finder.getTr(_4f,_50,"body",1);
var tr2=_51.finder.getTr(_4f,_50,"body",2);
var _52=$(_4f).datagrid("getColumnFields",true).length+(_51.rownumbers?1:0);
var _53=$(_4f).datagrid("getColumnFields",false).length;
_54(tr1,_52);
_54(tr2,_53);
function _54(tr,_55){
$("<tr class=\"treegrid-tr-tree\">"+"<td style=\"border:0px\" colspan=\""+_55+"\">"+"<div></div>"+"</td>"+"</tr>").insertAfter(tr);
};
};
function _56(_57,_58,_59,_5a,_5b){
var _5c=$.data(_57,"treegrid");
var _5d=_5c.options;
var dc=_5c.dc;
_59=_5d.loadFilter.call(_57,_59,_58);
var _5e=_37(_57,_58);
if(_5e){
var _5f=_5d.finder.getTr(_57,_58,"body",1);
var _60=_5d.finder.getTr(_57,_58,"body",2);
var cc1=_5f.next("tr.treegrid-tr-tree").children("td").children("div");
var cc2=_60.next("tr.treegrid-tr-tree").children("td").children("div");
if(!_5a){
_5e.children=[];
}
}else{
var cc1=dc.body1;
var cc2=dc.body2;
if(!_5a){
_5c.data=[];
}
}
if(!_5a){
cc1.empty();
cc2.empty();
}
if(_5d.view.onBeforeRender){
_5d.view.onBeforeRender.call(_5d.view,_57,_58,_59);
}
_5d.view.render.call(_5d.view,_57,cc1,true);
_5d.view.render.call(_5d.view,_57,cc2,false);
if(_5d.showFooter){
_5d.view.renderFooter.call(_5d.view,_57,dc.footer1,true);
_5d.view.renderFooter.call(_5d.view,_57,dc.footer2,false);
}
if(_5d.view.onAfterRender){
_5d.view.onAfterRender.call(_5d.view,_57);
}
if(!_58&&_5d.pagination){
var _61=$.data(_57,"treegrid").total;
var _62=$(_57).datagrid("getPager");
var _63=_62.pagination("options");
if(_63.total!=_59.total){
_62.pagination("refresh",{pageNumber:_5d.pageNumber,total:_59.total});
if(_5d.pageNumber!=_63.pageNumber&&_63.pageNumber>0){
_5d.pageNumber=_63.pageNumber;
_15(_57);
}
}
}
_16(_57);
_1f(_57);
$(_57).treegrid("showLines");
$(_57).treegrid("setSelectionState");
$(_57).treegrid("autoSizeColumn");
if(!_5b){
_5d.onLoadSuccess.call(_57,_5e,_59);
}
};
function _15(_64,_65,_66,_67,_68){
var _69=$.data(_64,"treegrid").options;
var _6a=$(_64).datagrid("getPanel").find("div.datagrid-body");
if(_65==undefined&&_69.queryParams){
_69.queryParams.id=undefined;
}
if(_66){
_69.queryParams=_66;
}
var _6b=$.extend({},_69.queryParams);
if(_69.pagination){
$.extend(_6b,{page:_69.pageNumber,rows:_69.pageSize});
}
if(_69.sortName){
$.extend(_6b,{sort:_69.sortName,order:_69.sortOrder});
}
var row=_37(_64,_65);
if(_69.onBeforeLoad.call(_64,row,_6b)==false){
return;
}
var _6c=_6a.find("tr[node-id=\""+_65+"\"] span.tree-folder");
_6c.addClass("tree-loading");
$(_64).treegrid("loading");
var _6d=_69.loader.call(_64,_6b,function(_6e){
_6c.removeClass("tree-loading");
$(_64).treegrid("loaded");
_56(_64,_65,_6e,_67);
if(_68){
_68();
}
},function(){
_6c.removeClass("tree-loading");
$(_64).treegrid("loaded");
_69.onLoadError.apply(_64,arguments);
if(_68){
_68();
}
});
if(_6d==false){
_6c.removeClass("tree-loading");
$(_64).treegrid("loaded");
}
};
function _6f(_70){
var _71=_72(_70);
return _71.length?_71[0]:null;
};
function _72(_73){
return $.data(_73,"treegrid").data;
};
function _46(_74,_75){
var row=_37(_74,_75);
if(row._parentId){
return _37(_74,row._parentId);
}else{
return null;
}
};
function _1b(_76,_77){
var _78=$.data(_76,"treegrid").data;
if(_77){
var _79=_37(_76,_77);
_78=_79?(_79.children||[]):[];
}
var _7a=[];
$.easyui.forEach(_78,true,function(_7b){
_7a.push(_7b);
});
return _7a;
};
function _7c(_7d,_7e){
var _7f=$.data(_7d,"treegrid").options;
var tr=_7f.finder.getTr(_7d,_7e);
var _80=tr.children("td[field=\""+_7f.treeField+"\"]");
return _80.find("span.tree-indent,span.tree-hit").length;
};
function _37(_81,_82){
var _83=$.data(_81,"treegrid");
var _84=_83.options;
var _85=null;
$.easyui.forEach(_83.data,true,function(_86){
if(_86[_84.idField]==_82){
_85=_86;
return false;
}
});
return _85;
};
function _87(_88,_89,_8a){
var _8b=$.data(_88,"treegrid");
var _8c=null;
$.easyui.forEach(_8b.data,true,function(_8d){
if(_8d[_89]==_8a){
_8c=_8d;
return false;
}
});
return _8c;
};
function _8e(_8f,_90){
var _91=$.data(_8f,"treegrid").options;
var row=_37(_8f,_90);
var tr=_91.finder.getTr(_8f,_90);
var hit=tr.find("span.tree-hit");
if(hit.length==0){
return;
}
if(hit.hasClass("tree-collapsed")){
return;
}
if(_91.onBeforeCollapse.call(_8f,row)==false){
return;
}
hit.removeClass("tree-expanded tree-expanded-hover").addClass("tree-collapsed");
hit.next().removeClass("tree-folder-open");
row.state="closed";
tr=tr.next("tr.treegrid-tr-tree");
var cc=tr.children("td").children("div");
if(_91.animate){
cc.slideUp("normal",function(){
$(_8f).treegrid("autoSizeColumn");
_16(_8f,_90);
_91.onCollapse.call(_8f,row);
});
}else{
cc.hide();
$(_8f).treegrid("autoSizeColumn");
_16(_8f,_90);
_91.onCollapse.call(_8f,row);
}
};
function _92(_93,_94){
var _95=$.data(_93,"treegrid").options;
var tr=_95.finder.getTr(_93,_94);
var hit=tr.find("span.tree-hit");
var row=_37(_93,_94);
if(hit.length==0){
return;
}
if(hit.hasClass("tree-expanded")){
return;
}
if(_95.onBeforeExpand.call(_93,row)==false){
return;
}
hit.removeClass("tree-collapsed tree-collapsed-hover").addClass("tree-expanded");
hit.next().addClass("tree-folder-open");
var _96=tr.next("tr.treegrid-tr-tree");
if(_96.length){
var cc=_96.children("td").children("div");
_97(cc);
}else{
_4e(_93,row[_95.idField]);
var _96=tr.next("tr.treegrid-tr-tree");
var cc=_96.children("td").children("div");
cc.hide();
var _98=$.extend({},_95.queryParams||{});
_98.id=row[_95.idField];
_15(_93,row[_95.idField],_98,true,function(){
if(cc.is(":empty")){
_96.remove();
}else{
_97(cc);
}
});
}
function _97(cc){
row.state="open";
if(_95.animate){
cc.slideDown("normal",function(){
$(_93).treegrid("autoSizeColumn");
_16(_93,_94);
_95.onExpand.call(_93,row);
});
}else{
cc.show();
$(_93).treegrid("autoSizeColumn");
_16(_93,_94);
_95.onExpand.call(_93,row);
}
};
};
function _28(_99,_9a){
var _9b=$.data(_99,"treegrid").options;
var tr=_9b.finder.getTr(_99,_9a);
var hit=tr.find("span.tree-hit");
if(hit.hasClass("tree-expanded")){
_8e(_99,_9a);
}else{
_92(_99,_9a);
}
};
function _9c(_9d,_9e){
var _9f=$.data(_9d,"treegrid").options;
var _a0=_1b(_9d,_9e);
if(_9e){
_a0.unshift(_37(_9d,_9e));
}
for(var i=0;i<_a0.length;i++){
_8e(_9d,_a0[i][_9f.idField]);
}
};
function _a1(_a2,_a3){
var _a4=$.data(_a2,"treegrid").options;
var _a5=_1b(_a2,_a3);
if(_a3){
_a5.unshift(_37(_a2,_a3));
}
for(var i=0;i<_a5.length;i++){
_92(_a2,_a5[i][_a4.idField]);
}
};
function _a6(_a7,_a8){
var _a9=$.data(_a7,"treegrid").options;
var ids=[];
var p=_46(_a7,_a8);
while(p){
var id=p[_a9.idField];
ids.unshift(id);
p=_46(_a7,id);
}
for(var i=0;i<ids.length;i++){
_92(_a7,ids[i]);
}
};
function _aa(_ab,_ac){
var _ad=$.data(_ab,"treegrid");
var _ae=_ad.options;
if(_ac.parent){
var tr=_ae.finder.getTr(_ab,_ac.parent);
if(tr.next("tr.treegrid-tr-tree").length==0){
_4e(_ab,_ac.parent);
}
var _af=tr.children("td[field=\""+_ae.treeField+"\"]").children("div.datagrid-cell");
var _b0=_af.children("span.tree-icon");
if(_b0.hasClass("tree-file")){
_b0.removeClass("tree-file").addClass("tree-folder tree-folder-open");
var hit=$("<span class=\"tree-hit tree-expanded\"></span>").insertBefore(_b0);
if(hit.prev().length){
hit.prev().remove();
}
}
}
_56(_ab,_ac.parent,_ac.data,_ad.data.length>0,true);
};
function _b1(_b2,_b3){
var ref=_b3.before||_b3.after;
var _b4=$.data(_b2,"treegrid").options;
var _b5=_46(_b2,ref);
_aa(_b2,{parent:(_b5?_b5[_b4.idField]:null),data:[_b3.data]});
var _b6=_b5?_b5.children:$(_b2).treegrid("getRoots");
for(var i=0;i<_b6.length;i++){
if(_b6[i][_b4.idField]==ref){
var _b7=_b6[_b6.length-1];
_b6.splice(_b3.before?i:(i+1),0,_b7);
_b6.splice(_b6.length-1,1);
break;
}
}
_b8(true);
_b8(false);
_1f(_b2);
$(_b2).treegrid("showLines");
function _b8(_b9){
var _ba=_b9?1:2;
var tr=_b4.finder.getTr(_b2,_b3.data[_b4.idField],"body",_ba);
var _bb=tr.closest("table.datagrid-btable");
tr=tr.parent().children();
var _bc=_b4.finder.getTr(_b2,ref,"body",_ba);
if(_b3.before){
tr.insertBefore(_bc);
}else{
var sub=_bc.next("tr.treegrid-tr-tree");
tr.insertAfter(sub.length?sub:_bc);
}
_bb.remove();
};
};
function _bd(_be,_bf){
var _c0=$.data(_be,"treegrid");
var _c1=_c0.options;
var _c2=_46(_be,_bf);
$(_be).datagrid("deleteRow",_bf);
$.easyui.removeArrayItem(_c0.checkedRows,_c1.idField,_bf);
_1f(_be);
if(_c2){
_49(_be,_c2[_c1.idField]);
}
_c0.total-=1;
$(_be).datagrid("getPager").pagination("refresh",{total:_c0.total});
$(_be).treegrid("showLines");
};
function _c3(_c4){
var t=$(_c4);
var _c5=t.treegrid("options");
if(_c5.lines){
t.treegrid("getPanel").addClass("tree-lines");
}else{
t.treegrid("getPanel").removeClass("tree-lines");
return;
}
t.treegrid("getPanel").find("span.tree-indent").removeClass("tree-line tree-join tree-joinbottom");
t.treegrid("getPanel").find("div.datagrid-cell").removeClass("tree-node-last tree-root-first tree-root-one");
var _c6=t.treegrid("getRoots");
if(_c6.length>1){
_c7(_c6[0]).addClass("tree-root-first");
}else{
if(_c6.length==1){
_c7(_c6[0]).addClass("tree-root-one");
}
}
_c8(_c6);
_c9(_c6);
function _c8(_ca){
$.map(_ca,function(_cb){
if(_cb.children&&_cb.children.length){
_c8(_cb.children);
}else{
var _cc=_c7(_cb);
_cc.find(".tree-icon").prev().addClass("tree-join");
}
});
if(_ca.length){
var _cd=_c7(_ca[_ca.length-1]);
_cd.addClass("tree-node-last");
_cd.find(".tree-join").removeClass("tree-join").addClass("tree-joinbottom");
}
};
function _c9(_ce){
$.map(_ce,function(_cf){
if(_cf.children&&_cf.children.length){
_c9(_cf.children);
}
});
for(var i=0;i<_ce.length-1;i++){
var _d0=_ce[i];
var _d1=t.treegrid("getLevel",_d0[_c5.idField]);
var tr=_c5.finder.getTr(_c4,_d0[_c5.idField]);
var cc=tr.next().find("tr.datagrid-row td[field=\""+_c5.treeField+"\"] div.datagrid-cell");
cc.find("span:eq("+(_d1-1)+")").addClass("tree-line");
}
};
function _c7(_d2){
var tr=_c5.finder.getTr(_c4,_d2[_c5.idField]);
var _d3=tr.find("td[field=\""+_c5.treeField+"\"] div.datagrid-cell");
return _d3;
};
};
$.fn.treegrid=function(_d4,_d5){
if(typeof _d4=="string"){
var _d6=$.fn.treegrid.methods[_d4];
if(_d6){
return _d6(this,_d5);
}else{
return this.datagrid(_d4,_d5);
}
}
_d4=_d4||{};
return this.each(function(){
var _d7=$.data(this,"treegrid");
if(_d7){
$.extend(_d7.options,_d4);
}else{
_d7=$.data(this,"treegrid",{options:$.extend({},$.fn.treegrid.defaults,$.fn.treegrid.parseOptions(this),_d4),data:[],checkedRows:[],tmpIds:[]});
}
_1(this);
if(_d7.options.data){
$(this).treegrid("loadData",_d7.options.data);
}
_15(this);
});
};
$.fn.treegrid.methods={options:function(jq){
return $.data(jq[0],"treegrid").options;
},resize:function(jq,_d8){
return jq.each(function(){
$(this).datagrid("resize",_d8);
});
},fixRowHeight:function(jq,_d9){
return jq.each(function(){
_16(this,_d9);
});
},loadData:function(jq,_da){
return jq.each(function(){
_56(this,_da.parent,_da);
});
},load:function(jq,_db){
return jq.each(function(){
$(this).treegrid("options").pageNumber=1;
$(this).treegrid("getPager").pagination({pageNumber:1});
$(this).treegrid("reload",_db);
});
},reload:function(jq,id){
return jq.each(function(){
var _dc=$(this).treegrid("options");
var _dd={};
if(typeof id=="object"){
_dd=id;
}else{
_dd=$.extend({},_dc.queryParams);
_dd.id=id;
}
if(_dd.id){
var _de=$(this).treegrid("find",_dd.id);
if(_de.children){
_de.children.splice(0,_de.children.length);
}
_dc.queryParams=_dd;
var tr=_dc.finder.getTr(this,_dd.id);
tr.next("tr.treegrid-tr-tree").remove();
tr.find("span.tree-hit").removeClass("tree-expanded tree-expanded-hover").addClass("tree-collapsed");
_92(this,_dd.id);
}else{
_15(this,null,_dd);
}
});
},reloadFooter:function(jq,_df){
return jq.each(function(){
var _e0=$.data(this,"treegrid").options;
var dc=$.data(this,"datagrid").dc;
if(_df){
$.data(this,"treegrid").footer=_df;
}
if(_e0.showFooter){
_e0.view.renderFooter.call(_e0.view,this,dc.footer1,true);
_e0.view.renderFooter.call(_e0.view,this,dc.footer2,false);
if(_e0.view.onAfterRender){
_e0.view.onAfterRender.call(_e0.view,this);
}
$(this).treegrid("fixRowHeight");
}
});
},getData:function(jq){
return $.data(jq[0],"treegrid").data;
},getFooterRows:function(jq){
return $.data(jq[0],"treegrid").footer;
},getRoot:function(jq){
return _6f(jq[0]);
},getRoots:function(jq){
return _72(jq[0]);
},getParent:function(jq,id){
return _46(jq[0],id);
},getChildren:function(jq,id){
return _1b(jq[0],id);
},getLevel:function(jq,id){
return _7c(jq[0],id);
},find:function(jq,id){
return _37(jq[0],id);
},findBy:function(jq,_e1){
return _87(jq[0],_e1.field,_e1.value);
},isLeaf:function(jq,id){
var _e2=$.data(jq[0],"treegrid").options;
var tr=_e2.finder.getTr(jq[0],id);
var hit=tr.find("span.tree-hit");
return hit.length==0;
},select:function(jq,id){
return jq.each(function(){
$(this).datagrid("selectRow",id);
});
},unselect:function(jq,id){
return jq.each(function(){
$(this).datagrid("unselectRow",id);
});
},collapse:function(jq,id){
return jq.each(function(){
_8e(this,id);
});
},expand:function(jq,id){
return jq.each(function(){
_92(this,id);
});
},toggle:function(jq,id){
return jq.each(function(){
_28(this,id);
});
},collapseAll:function(jq,id){
return jq.each(function(){
_9c(this,id);
});
},expandAll:function(jq,id){
return jq.each(function(){
_a1(this,id);
});
},expandTo:function(jq,id){
return jq.each(function(){
_a6(this,id);
});
},append:function(jq,_e3){
return jq.each(function(){
_aa(this,_e3);
});
},insert:function(jq,_e4){
return jq.each(function(){
_b1(this,_e4);
});
},remove:function(jq,id){
return jq.each(function(){
_bd(this,id);
});
},pop:function(jq,id){
var row=jq.treegrid("find",id);
jq.treegrid("remove",id);
return row;
},refresh:function(jq,id){
return jq.each(function(){
var _e5=$.data(this,"treegrid").options;
_e5.view.refreshRow.call(_e5.view,this,id);
});
},update:function(jq,_e6){
return jq.each(function(){
var _e7=$.data(this,"treegrid").options;
var row=_e6.row;
_e7.view.updateRow.call(_e7.view,this,_e6.id,row);
if(row.checked!=undefined){
row=_37(this,_e6.id);
$.extend(row,{checkState:row.checked?"checked":(row.checked===false?"unchecked":undefined)});
_49(this,_e6.id);
}
});
},beginEdit:function(jq,id){
return jq.each(function(){
$(this).datagrid("beginEdit",id);
$(this).treegrid("fixRowHeight",id);
});
},endEdit:function(jq,id){
return jq.each(function(){
$(this).datagrid("endEdit",id);
});
},cancelEdit:function(jq,id){
return jq.each(function(){
$(this).datagrid("cancelEdit",id);
});
},showLines:function(jq){
return jq.each(function(){
_c3(this);
});
},setSelectionState:function(jq){
return jq.each(function(){
$(this).datagrid("setSelectionState");
var _e8=$(this).data("treegrid");
for(var i=0;i<_e8.tmpIds.length;i++){
_29(this,_e8.tmpIds[i],true,true);
}
_e8.tmpIds=[];
});
},getCheckedNodes:function(jq,_e9){
_e9=_e9||"checked";
var _ea=[];
$.easyui.forEach(jq.data("treegrid").checkedRows,false,function(row){
if(row.checkState==_e9){
_ea.push(row);
}
});
return _ea;
},checkNode:function(jq,id){
return jq.each(function(){
_29(this,id,true);
});
},uncheckNode:function(jq,id){
return jq.each(function(){
_29(this,id,false);
});
},clearChecked:function(jq){
return jq.each(function(){
var _eb=this;
var _ec=$(_eb).treegrid("options");
$(_eb).datagrid("clearChecked");
$.map($(_eb).treegrid("getCheckedNodes"),function(row){
_29(_eb,row[_ec.idField],false,true);
});
});
}};
$.fn.treegrid.parseOptions=function(_ed){
return $.extend({},$.fn.datagrid.parseOptions(_ed),$.parser.parseOptions(_ed,["treeField",{checkbox:"boolean",cascadeCheck:"boolean",onlyLeafCheck:"boolean"},{animate:"boolean"}]));
};
var _ee=$.extend({},$.fn.datagrid.defaults.view,{render:function(_ef,_f0,_f1){
var _f2=$.data(_ef,"treegrid").options;
var _f3=$(_ef).datagrid("getColumnFields",_f1);
var _f4=$.data(_ef,"datagrid").rowIdPrefix;
if(_f1){
if(!(_f2.rownumbers||(_f2.frozenColumns&&_f2.frozenColumns.length))){
return;
}
}
var _f5=this;
if(this.treeNodes&&this.treeNodes.length){
var _f6=_f7.call(this,_f1,this.treeLevel,this.treeNodes);
$(_f0).append(_f6.join(""));
}
function _f7(_f8,_f9,_fa){
var _fb=$(_ef).treegrid("getParent",_fa[0][_f2.idField]);
var _fc=(_fb?_fb.children.length:$(_ef).treegrid("getRoots").length)-_fa.length;
var _fd=["<table class=\"datagrid-btable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tbody>"];
for(var i=0;i<_fa.length;i++){
var row=_fa[i];
if(row.state!="open"&&row.state!="closed"){
row.state="open";
}
var css=_f2.rowStyler?_f2.rowStyler.call(_ef,row):"";
var cs=this.getStyleValue(css);
var cls="class=\"datagrid-row "+(_fc++%2&&_f2.striped?"datagrid-row-alt ":" ")+cs.c+"\"";
var _fe=cs.s?"style=\""+cs.s+"\"":"";
var _ff=_f4+"-"+(_f8?1:2)+"-"+row[_f2.idField];
_fd.push("<tr id=\""+_ff+"\" node-id=\""+row[_f2.idField]+"\" "+cls+" "+_fe+">");
_fd=_fd.concat(_f5.renderRow.call(_f5,_ef,_f3,_f8,_f9,row));
_fd.push("</tr>");
if(row.children&&row.children.length){
var tt=_f7.call(this,_f8,_f9+1,row.children);
var v=row.state=="closed"?"none":"block";
_fd.push("<tr class=\"treegrid-tr-tree\"><td style=\"border:0px\" colspan="+(_f3.length+(_f2.rownumbers?1:0))+"><div style=\"display:"+v+"\">");
_fd=_fd.concat(tt);
_fd.push("</div></td></tr>");
}
}
_fd.push("</tbody></table>");
return _fd;
};
},renderFooter:function(_100,_101,_102){
var opts=$.data(_100,"treegrid").options;
var rows=$.data(_100,"treegrid").footer||[];
var _103=$(_100).datagrid("getColumnFields",_102);
var _104=["<table class=\"datagrid-ftable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tbody>"];
for(var i=0;i<rows.length;i++){
var row=rows[i];
row[opts.idField]=row[opts.idField]||("foot-row-id"+i);
_104.push("<tr class=\"datagrid-row\" node-id=\""+row[opts.idField]+"\">");
_104.push(this.renderRow.call(this,_100,_103,_102,0,row));
_104.push("</tr>");
}
_104.push("</tbody></table>");
$(_101).html(_104.join(""));
},renderRow:function(_105,_106,_107,_108,row){
var _109=$.data(_105,"treegrid");
var opts=_109.options;
var cc=[];
if(_107&&opts.rownumbers){
cc.push("<td class=\"datagrid-td-rownumber\"><div class=\"datagrid-cell-rownumber\">0</div></td>");
}
for(var i=0;i<_106.length;i++){
var _10a=_106[i];
var col=$(_105).datagrid("getColumnOption",_10a);
if(col){
var css=col.styler?(col.styler(row[_10a],row)||""):"";
var cs=this.getStyleValue(css);
var cls=cs.c?"class=\""+cs.c+"\"":"";
var _10b=col.hidden?"style=\"display:none;"+cs.s+"\"":(cs.s?"style=\""+cs.s+"\"":"");
cc.push("<td field=\""+_10a+"\" "+cls+" "+_10b+">");
var _10b="";
if(!col.checkbox){
if(col.align){
_10b+="text-align:"+col.align+";";
}
if(!opts.nowrap){
_10b+="white-space:normal;height:auto;";
}else{
if(opts.autoRowHeight){
_10b+="height:auto;";
}
}
}
cc.push("<div style=\""+_10b+"\" ");
if(col.checkbox){
cc.push("class=\"datagrid-cell-check ");
}else{
cc.push("class=\"datagrid-cell "+col.cellClass);
}
if(_10a==opts.treeField){
cc.push(" tree-node");
}
cc.push("\">");
if(col.checkbox){
if(row.checked){
cc.push("<input type=\"checkbox\" checked=\"checked\"");
}else{
cc.push("<input type=\"checkbox\"");
}
cc.push(" name=\""+_10a+"\" value=\""+(row[_10a]!=undefined?row[_10a]:"")+"\">");
}else{
var val=null;
if(col.formatter){
val=col.formatter(row[_10a],row);
}else{
val=row[_10a];
}
if(_10a==opts.treeField){
for(var j=0;j<_108;j++){
cc.push("<span class=\"tree-indent\"></span>");
}
if(row.state=="closed"){
cc.push("<span class=\"tree-hit tree-collapsed\"></span>");
cc.push("<span class=\"tree-icon tree-folder "+(row.iconCls?row.iconCls:"")+"\"></span>");
}else{
if(row.children&&row.children.length){
cc.push("<span class=\"tree-hit tree-expanded\"></span>");
cc.push("<span class=\"tree-icon tree-folder tree-folder-open "+(row.iconCls?row.iconCls:"")+"\"></span>");
}else{
cc.push("<span class=\"tree-indent\"></span>");
cc.push("<span class=\"tree-icon tree-file "+(row.iconCls?row.iconCls:"")+"\"></span>");
}
}
if(this.hasCheckbox(_105,row)){
var flag=0;
var crow=$.easyui.getArrayItem(_109.checkedRows,opts.idField,row[opts.idField]);
if(crow){
flag=crow.checkState=="checked"?1:2;
row.checkState=crow.checkState;
row.checked=crow.checked;
$.easyui.addArrayItem(_109.checkedRows,opts.idField,row);
}else{
var prow=$.easyui.getArrayItem(_109.checkedRows,opts.idField,row._parentId);
if(prow&&prow.checkState=="checked"&&opts.cascadeCheck){
flag=1;
row.checked=true;
$.easyui.addArrayItem(_109.checkedRows,opts.idField,row);
}else{
if(row.checked){
$.easyui.addArrayItem(_109.tmpIds,row[opts.idField]);
}
}
row.checkState=flag?"checked":"unchecked";
}
cc.push("<span class=\"tree-checkbox tree-checkbox"+flag+"\"></span>");
}else{
row.checkState=undefined;
row.checked=undefined;
}
cc.push("<span class=\"tree-title\">"+val+"</span>");
}else{
cc.push(val);
}
}
cc.push("</div>");
cc.push("</td>");
}
}
return cc.join("");
},hasCheckbox:function(_10c,row){
var opts=$.data(_10c,"treegrid").options;
if(opts.checkbox){
if($.isFunction(opts.checkbox)){
if(opts.checkbox.call(_10c,row)){
return true;
}else{
return false;
}
}else{
if(opts.onlyLeafCheck){
if(row.state=="open"&&!(row.children&&row.children.length)){
return true;
}
}else{
return true;
}
}
}
return false;
},refreshRow:function(_10d,id){
this.updateRow.call(this,_10d,id,{});
},updateRow:function(_10e,id,row){
var opts=$.data(_10e,"treegrid").options;
var _10f=$(_10e).treegrid("find",id);
$.extend(_10f,row);
var _110=$(_10e).treegrid("getLevel",id)-1;
var _111=opts.rowStyler?opts.rowStyler.call(_10e,_10f):"";
var _112=$.data(_10e,"datagrid").rowIdPrefix;
var _113=_10f[opts.idField];
function _114(_115){
var _116=$(_10e).treegrid("getColumnFields",_115);
var tr=opts.finder.getTr(_10e,id,"body",(_115?1:2));
var _117=tr.find("div.datagrid-cell-rownumber").html();
var _118=tr.find("div.datagrid-cell-check input[type=checkbox]").is(":checked");
tr.html(this.renderRow(_10e,_116,_115,_110,_10f));
tr.attr("style",_111||"");
tr.find("div.datagrid-cell-rownumber").html(_117);
if(_118){
tr.find("div.datagrid-cell-check input[type=checkbox]")._propAttr("checked",true);
}
if(_113!=id){
tr.attr("id",_112+"-"+(_115?1:2)+"-"+_113);
tr.attr("node-id",_113);
}
};
_114.call(this,true);
_114.call(this,false);
$(_10e).treegrid("fixRowHeight",id);
},deleteRow:function(_119,id){
var opts=$.data(_119,"treegrid").options;
var tr=opts.finder.getTr(_119,id);
tr.next("tr.treegrid-tr-tree").remove();
tr.remove();
var _11a=del(id);
if(_11a){
if(_11a.children.length==0){
tr=opts.finder.getTr(_119,_11a[opts.idField]);
tr.next("tr.treegrid-tr-tree").remove();
var cell=tr.children("td[field=\""+opts.treeField+"\"]").children("div.datagrid-cell");
cell.find(".tree-icon").removeClass("tree-folder").addClass("tree-file");
cell.find(".tree-hit").remove();
$("<span class=\"tree-indent\"></span>").prependTo(cell);
}
}
this.setEmptyMsg(_119);
function del(id){
var cc;
var _11b=$(_119).treegrid("getParent",id);
if(_11b){
cc=_11b.children;
}else{
cc=$(_119).treegrid("getData");
}
for(var i=0;i<cc.length;i++){
if(cc[i][opts.idField]==id){
cc.splice(i,1);
break;
}
}
return _11b;
};
},onBeforeRender:function(_11c,_11d,data){
if($.isArray(_11d)){
data={total:_11d.length,rows:_11d};
_11d=null;
}
if(!data){
return false;
}
var _11e=$.data(_11c,"treegrid");
var opts=_11e.options;
if(data.length==undefined){
if(data.footer){
_11e.footer=data.footer;
}
if(data.total){
_11e.total=data.total;
}
data=this.transfer(_11c,_11d,data.rows);
}else{
function _11f(_120,_121){
for(var i=0;i<_120.length;i++){
var row=_120[i];
row._parentId=_121;
if(row.children&&row.children.length){
_11f(row.children,row[opts.idField]);
}
}
};
_11f(data,_11d);
}
this.sort(_11c,data);
this.treeNodes=data;
this.treeLevel=$(_11c).treegrid("getLevel",_11d);
var node=_37(_11c,_11d);
if(node){
if(node.children){
node.children=node.children.concat(data);
}else{
node.children=data;
}
}else{
_11e.data=_11e.data.concat(data);
}
},sort:function(_122,data){
var opts=$.data(_122,"treegrid").options;
if(!opts.remoteSort&&opts.sortName){
var _123=opts.sortName.split(",");
var _124=opts.sortOrder.split(",");
_125(data);
}
function _125(rows){
rows.sort(function(r1,r2){
var r=0;
for(var i=0;i<_123.length;i++){
var sn=_123[i];
var so=_124[i];
var col=$(_122).treegrid("getColumnOption",sn);
var _126=col.sorter||function(a,b){
return a==b?0:(a>b?1:-1);
};
r=_126(r1[sn],r2[sn])*(so=="asc"?1:-1);
if(r!=0){
return r;
}
}
return r;
});
for(var i=0;i<rows.length;i++){
var _127=rows[i].children;
if(_127&&_127.length){
_125(_127);
}
}
};
},transfer:function(_128,_129,data){
var opts=$.data(_128,"treegrid").options;
var rows=$.extend([],data);
var _12a=_12b(_129,rows);
var toDo=$.extend([],_12a);
while(toDo.length){
var node=toDo.shift();
var _12c=_12b(node[opts.idField],rows);
if(_12c.length){
if(node.children){
node.children=node.children.concat(_12c);
}else{
node.children=_12c;
}
toDo=toDo.concat(_12c);
}
}
return _12a;
function _12b(_12d,rows){
var rr=[];
for(var i=0;i<rows.length;i++){
var row=rows[i];
if(row._parentId==_12d){
rr.push(row);
rows.splice(i,1);
i--;
}
}
return rr;
};
}});
$.fn.treegrid.defaults=$.extend({},$.fn.datagrid.defaults,{treeField:null,checkbox:false,cascadeCheck:true,onlyLeafCheck:false,lines:false,animate:false,singleSelect:true,view:_ee,rowEvents:$.extend({},$.fn.datagrid.defaults.rowEvents,{mouseover:_22(true),mouseout:_22(false),click:_24}),loader:function(_12e,_12f,_130){
var opts=$(this).treegrid("options");
if(!opts.url){
return false;
}
$.ajax({type:opts.method,url:opts.url,data:_12e,dataType:"json",success:function(data){
_12f(data);
},error:function(){
_130.apply(this,arguments);
}});
},loadFilter:function(data,_131){
return data;
},finder:{getTr:function(_132,id,type,_133){
type=type||"body";
_133=_133||0;
var dc=$.data(_132,"datagrid").dc;
if(_133==0){
var opts=$.data(_132,"treegrid").options;
var tr1=opts.finder.getTr(_132,id,type,1);
var tr2=opts.finder.getTr(_132,id,type,2);
return tr1.add(tr2);
}else{
if(type=="body"){
var tr=$("#"+$.data(_132,"datagrid").rowIdPrefix+"-"+_133+"-"+id);
if(!tr.length){
tr=(_133==1?dc.body1:dc.body2).find("tr[node-id=\""+id+"\"]");
}
return tr;
}else{
if(type=="footer"){
return (_133==1?dc.footer1:dc.footer2).find("tr[node-id=\""+id+"\"]");
}else{
if(type=="selected"){
return (_133==1?dc.body1:dc.body2).find("tr.datagrid-row-selected");
}else{
if(type=="highlight"){
return (_133==1?dc.body1:dc.body2).find("tr.datagrid-row-over");
}else{
if(type=="checked"){
return (_133==1?dc.body1:dc.body2).find("tr.datagrid-row-checked");
}else{
if(type=="last"){
return (_133==1?dc.body1:dc.body2).find("tr:last[node-id]");
}else{
if(type=="allbody"){
return (_133==1?dc.body1:dc.body2).find("tr[node-id]");
}else{
if(type=="allfooter"){
return (_133==1?dc.footer1:dc.footer2).find("tr[node-id]");
}
}
}
}
}
}
}
}
}
},getRow:function(_134,p){
var id=(typeof p=="object")?p.attr("node-id"):p;
return $(_134).treegrid("find",id);
},getRows:function(_135){
return $(_135).treegrid("getChildren");
}},onBeforeLoad:function(row,_136){
},onLoadSuccess:function(row,data){
},onLoadError:function(){
},onBeforeCollapse:function(row){
},onCollapse:function(row){
},onBeforeExpand:function(row){
},onExpand:function(row){
},onClickRow:function(row){
},onDblClickRow:function(row){
},onClickCell:function(_137,row){
},onDblClickCell:function(_138,row){
},onContextMenu:function(e,row){
},onBeforeEdit:function(row){
},onAfterEdit:function(row,_139){
},onCancelEdit:function(row){
},onBeforeCheckNode:function(row,_13a){
},onCheckNode:function(row,_13b){
}});
})(jQuery);

