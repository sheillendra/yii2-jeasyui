<?php

/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * version  : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : demo.dodeso.com
 */

namespace sheillendra\jeasyui;

class Easy extends Widget {

    public $appendToParent;
    public static $appendToParents = [
        'accordion' => 'appendDivToParent'
        , 'datagrid' => 'appendDivToParent'
        , 'form' => 'appendFormToParent'
        , 'layout' => 'appendDivToParent'
        , 'linkbutton' => 'appendLinkToParent'
        , 'menu' => 'appendDivToParent'
        , 'menubutton' => 'appendLinkDivToParent'
        , 'panel' => 'appendDivToParent'
        , 'propertygrid' => 'appendDivToParent'
        , 'splitbutton' => 'appendLinkDivToParent'
        , 'tabs' => 'appendDivToParent'
        , 'textbox' => 'appendInputToParent'
        , 'tree' => 'appendUlToParent'
        , 'treegrid' => 'appendDivToParent'
        , 'validatebox' => 'appendInputToParent'
        , 'combo' => 'appendInputToParent'
        , 'combobox' => 'appendInputToParent'
        , 'combotree' => 'appendInputToParent'
        , 'combogrid' => 'appendInputToParent'
        , 'numberbox' => 'appendInputToParent'
        , 'datebox' => 'appendInputToParent'
        , 'datetimebox' => 'appendInputToParent'
        , 'datetimespinner' => 'appendInputToParent'
        , 'calendar' => 'appendDivToParent'
        , 'spinner' => 'appendInputToParent'
        , 'numberspinner' => 'appendInputToParent'
        , 'timespinner' => 'appendInputToParent'
        , 'slider' => 'appendDivToParent'
    ];
    public static $addContentMethod = [
        'accordion' => 'add'
        //datagrid
        , 'layout' => 'add'
        //linkbutton
        , 'menu' => 'appendItem'
        //menubutton
        //panel
        , 'propertygrid' => 'appendRow'
        , 'tabs' => 'add'
            //tree 
    ];

    public function init() {
        parent::init();
        if (!isset($this->appendToParent)) {
            $this->appendToParent = isset(self::$appendToParents[$this->plugin]) ?
                    self::$appendToParents[$this->plugin] : 'appendDivToParent';
        }
        $this->{$this->appendToParent}();
    }

    public function run() {
        $this->registerScript();
        foreach ($this->contents as $id => $options) {

            if (is_string($id)) {
                $options['clientOptions']['id'] = $id;
            }

            if (!isset($options['plugin'])) {
                $options['plugin'] = $this->plugin;
            }

            if ($options['plugin'] === $this->plugin) {
                if (!isset(self::$addContentMethod[$this->plugin])) {
                    continue;
                }
                $options['method'] = self::$addContentMethod[$this->plugin];
            } else {
                $options['parent'] = $this->clientOptions['id'];
            }

            $options['target'] = $this->target;
            Easy::widget($options);
        }
    }

}
