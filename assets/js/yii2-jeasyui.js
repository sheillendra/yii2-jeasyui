/* 
 * Implement jQuery EasyUI GPL Edition on Yii2
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : dodeso.com
 */

yii.jeasyui = (function($) {
    var generateIdPrefix='w'
    ,generateIdCounter=0
    ,generateId = function(){
        var e,res;
        do {
            res=generateIdPrefix+generateIdCounter;
            e=document.getElementById(res);
            generateIdCounter++;
        } while (e);
        return res;
    }
    ,addContentMethod={
        accordion:'add',
        layout:'add',
        linkbutton:'',
        propertygrid:'appendRow',
        tabs:'add'
    }
    ,appendTargetToParent = function(parent, id) {
        parent.append('<div id="' + id + '"></div>');
    }
    ,Easy = function(options){
        if(typeof options.parent !=='undefined'){
            appendTargetToParent(options.parent, options.clientOptions.id);
        }
        if(typeof options.target ==='undefined'){
            options.target = $('#' + options.clientOptions.id);
        }
        options.clientOptions = eventNormalizer(options.clientOptions);
        using(options.plugin, function() {
            if(typeof options.method !=='undefined'){
                options.target[options.plugin](options.method,options.clientOptions);
            }else{
                options.target[options.plugin](options.clientOptions);
            }
            if(typeof options.contents !=='undefined'){
                var keys=Object.keys(options.contents),
                    i;
                for(i=0; i < keys.length; i++){
                    options.contents[keys[i]]['clientOptions']['id']=keys[i];
                    if(typeof options.contents[keys[i]]['plugin'] === 'undefined'){
                        options.contents[keys[i]]['plugin']=options.plugin;
                        options.contents[keys[i]]['target']=options.target;
                    }
                    
                    if(options.contents[keys[i]]['plugin']===options.plugin){
                        options.contents[keys[i]]['method']=addContentMethod[options.plugin];
                    }else{
                        options.contents[keys[i]]['parent']=$('#'+options.clientOptions.id);
                    }
                    Easy(options.contents[keys[i]]);
                }
            }
        });
    }
    ,eventNormalizer = function(a){
        if(typeof a ==='object'){
            var keys=Object.keys(a),
                i;
            for(i=0; i < keys.length; i++){
                if(typeof a[keys[i]] ==='string' && a[keys[i]].indexOf('function')===0){
                    a[keys[i]]=new Function('return '+a[keys[i]])();
                }
            };
        }
        return a;
    },
    pub = {
        isActive: true
        , init: function() {

        }
        , parser: function(parent, id, title, url) {
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(r) {
                    var keys=Object.keys(r);
                    for(var i=0;i < keys.length;i++) {
                        if(document.getElementById(keys[i])){
                            r[keys[i]].clientOptions.id = generateId();
                        }else{
                            r[keys[i]].clientOptions.id = keys[i];
                        }
                        r[keys[i]].parent=parent;
                        Easy(r[keys[i]]);
                    };
                }
            });
        }
    };
    return pub;
})(jQuery);


