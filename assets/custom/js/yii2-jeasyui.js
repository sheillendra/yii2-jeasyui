/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * version  : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : demo.dodeso.com
 */

yii.jeasyui = (function($) {
    var 
    addContentMethod = {}
    , appendToParents = {}
    , appendTagToParent = function(parent, id, tag) {
        tag = tag || 'div';
        parent.append('<' + tag + ' id="' + id + '"></' + tag + '>');
    }
    , eventNormalizer = function(a) {
        if (typeof a === 'object') {
            var keys = Object.keys(a),
                    i;
            for (i = 0; i < keys.length; i++) {
                if (typeof a[keys[i]] === 'string' && a[keys[i]].indexOf('function') === 0) {
                    a[keys[i]] = new Function('return ' + a[keys[i]])();
                }
            }
            ;
        }
        return a;
    },
    pub = {
        isActive: true
        ,Easy : function(options) {
            var depedencyPlugin=[];
            if (typeof options.parent !== 'undefined') {
                yii.jeasyui[options.appendToParent||appendToParents[options.plugin]](options.parent, options.clientOptions.id,options.clientOptions.title,options.clientOptions.menu);
            }
            if (typeof options.target === 'undefined') {
                options.target = $('#' + options.clientOptions.id);
            }
            options.clientOptions = eventNormalizer(options.clientOptions);

            if(typeof options.depedencyPlugin !=='undefined'){
                depedencyPlugin=options.depedencyPlugin;
            }
            depedencyPlugin.push(options.plugin);
            using(depedencyPlugin, function() {
                if (typeof options.method !== 'undefined') {
                    options.target[options.plugin](options.method, options.clientOptions);
                } else {
                    options.target[options.plugin](options.clientOptions);
                }
                if (typeof options.contents !== 'undefined') {
                    var keys = Object.keys(options.contents), i;
                    for (i = 0; i < keys.length; i++) {
                        options.contents[keys[i]]['clientOptions']['id'] = keys[i];
                        if (typeof options.contents[keys[i]]['plugin'] === 'undefined') {
                            options.contents[keys[i]]['plugin'] = options.plugin;
                            options.contents[keys[i]]['target'] = options.target;
                        }

                        if (options.contents[keys[i]]['plugin'] === options.plugin) {
                            if(typeof addContentMethod[options.plugin] === 'undefined'){
                                console.log(options.plugin);
                                continue;
                            }
                            options.contents[keys[i]]['method'] = addContentMethod[options.plugin];
                        } else {
                            options.contents[keys[i]]['parent'] = $('#' + options.clientOptions.id);
                        }

                        yii.jeasyui.Easy(options.contents[keys[i]]);
                    }
                }
            });
        }
        , addContentMethod: function(a) {
            addContentMethod = a;
        }
        , appendToParents: function(a) {
            appendToParents = a;
        }
        ,appendDivToParent : function(parent,id){appendTagToParent(parent,id);}
        , appendUlToParent : function(parent,id){appendTagToParent(parent,id,'ul');}
        , appendTableToParent : function(parent,id){appendTagToParent(parent,id,'table');}
        , appendFormToParent : function(parent,id){appendTagToParent(parent,id,'form');}
        , appendLinkToParent : function(parent,id,title){
            parent.append('<a id="'+id+'" href="javascript:void(0)">'+title+'</a>');
        }
        , appendLinkDivToParent : function(parent,id,title,id2){
            yii.jeasyui.appendLinkToParent(parent,id,title);
            appendTagToParent(parent,id2);
        }
        , appendInputToParent : function(parent,id){
            parent.append('<input id="'+id+'" />');
        }
    };
    return pub;
})(jQuery);


