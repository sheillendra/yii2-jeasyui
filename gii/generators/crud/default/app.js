yii.app = (function($) {
    var generateIdPrefix = 'w'
    , generateIdCounter = 0
    , generateId = function() {
        var e, res;
        do {
            res = generateIdPrefix + generateIdCounter;
            e = document.getElementById(res);
            generateIdCounter++;
        } while (e);
        return res;
    }
    ,maintab;
    
    return {
        isActive: false,
        westContent:'',
        northContent:'',
        centerContent:'',
        southContent:'',
        init: function() {
            
            $.ajaxSetup({
                error:function(e){
                    yii.app.showError(e);
                }
            });
            using(['accordion','layout','menu','menubutton','linkbutton','tabs','messager'],function(){
                $('body').layout({
                    fit: true,
                    border: !1
                }).layout('add', {
                    region: 'north',
                    content: yii.app.northContent,
                    height:40
                }).layout('add', {
                    title : 'Navigation',
                    region: 'west',
                    split:!0,
                    width: 200,
                    content: yii.app.westContent
                }).layout('add', {
                    region: 'south',
                    content: yii.app.southContent
                }).layout('add', {
                    region: 'center',
                    content: yii.app.centerContent
                    
                });
                
                delete yii.app.northContent;
                delete yii.app.westContent;
                delete yii.app.southContent;
                delete yii.app.centerContent;
                
                $('#north-user-menu-item').menu({
                    
                }).menu('appendItem',{
                    separator:!0
                }).menu('appendItem',{
                    text:'Logout',
                    iconCls:'pma-icon-logout',
                    onclick:function(e){
                        yii.handleAction($('<a href="'+yii.app.logoutUrl+'" data-method="post"></a>'));
                        e.stopPropagation();
                    }
                });
                
                $('#north-user-menu').menubutton({
                    menu:'#north-user-menu-item'
                });
                
                var navSelected;
                yii.app.selectedNav = yii.app.selectedNav || 'Dashboard';
                $('#navigation').accordion({
                    border:!1,
                    fit:!0
                });
                
                $.each(yii.app.navItem,function(k,v){
                    v.selected = v.selected || false;
                    $('#navigation').accordion('add',v);
                });
                
                $.each($('.nav-btn'),function(k,v){
                    var selected=!1;
                    if(yii.app.selectedNav === v.id){
                        navSelected = v;
                        selected = !0;
                    }
                    $(v).linkbutton({
                        toggle:!0,
                        group:'g1',
                        iconCls:v.dataset.icon,
                        selected:selected,
                        onClick:function(){
                            yii.app.createTab(v.dataset.tabtitle,v.dataset.url,v.dataset.icon,v.id);
                        }
                    }); 
                });
                
                maintab = $('#maintab');
                maintab.tabs({
                    fit:true,
                    border:!1,
                    onSelect:function(t,i){
                        var tab = maintab.tabs('getSelected'),
                            options = tab.panel('options'),
                            nav = document.getElementById(options.data.nav);
                            window.history.pushState('','',options.data.url);
                            document.title = t;
                            if(typeof nav !=='undefined' && !nav.classList.contains('l-btn-selected')){
                                $('#navigation .l-btn-selected').removeClass('l-btn-selected');
                                nav.classList.add('l-btn-selected');
                            }
                    }
                });
                
                if(typeof yii.app.tabOptions !=='undefined' ){
                    yii.app.createTab(yii.app.tabOptions.tabtitle,yii.app.tabOptions.url,yii.app.tabOptions.icon,yii.app.selectedNav);
                    delete yii.app.tabOptions;
                }else{
                    if(typeof navSelected!=='undefined'){
                        yii.app.createTab(navSelected.dataset.tabtitle,navSelected.dataset.url,navSelected.dataset.icon,yii.app.selectedNav);
                    }else{
                        $.messager.alert('Error','Navigation not found','error');
                    }
                }
                
                delete yii.app.selectedNav;
                navSelected = null;
            });
        },
        createTab:function(title,url,icon,nav){
            if(maintab.tabs('exists',title)){
                maintab.tabs('select',title);
            }else{
                maintab.tabs('add',{
                    title:title,
                    href:url,
                    closable:true,
                    iconCls:icon,
                    data:{nav:nav,url:url}
                });
            }
        },
        showGridMsg:function(target,data){
            var vc = $(target).datagrid('getPanel').children('div.datagrid-view');
            if (!data.rows.length){
                var d = $('<div class="datagrid-empty"></div>').html('No Records Found').appendTo(vc);
                d.css({
                    position:'absolute',
                    left:0,
                    top:50,
                    width:'100%',
                    textAlign:'center'
                });
            }else{
                vc.children('div.datagrid-empty').remove();
            }
        },
        showError:function(e){
            using(['dialog'],function(){
                $('#global-error').dialog({
                    title:'Error',
                    width:window.innerWidth - 50,
                    height:window.innerHeight - 50,
                    content:e.responseText
                }); 
            });
        }
    };
})(jQuery);