<?php
 /**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * version  : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : demo.dodeso.com
 */


namespace sheillendra\jeasyui;

class Easy extends Widget
{
    public static $appendToParent = [
        'accordion'=>'appendDivToParent'
        ,'datagrid'=>'appendDivToParent'
        ,'form'=>'appendFormToParent'
        ,'layout'=>'appendDivToParent'
        ,'linkbutton'=>'appendLinkToParent'
        ,'menu'=>'appendDivToParent'
        ,'menubutton'=>'appendLinkDivToParent'
        ,'panel'=>'appendDivToParent'
        ,'propertygrid'=>'appendDivToParent'
        ,'splitbutton'=>'appendLinkDivToParent'
        ,'tabs'=>'appendDivToParent'
        ,'tree'=>'appendUlToParent'
        ,'treegrid'=>'appendDivToParent'
    ];
    public static $addContentMethod = [
        'accordion'=>'add'
        //datagrid
        ,'layout'=>'add'
        //linkbutton
        ,'menu'=>'appendItem'
        //menubutton
        //panel
        ,'propertygrid'=>'appendRow'
        ,'tabs'=>'add'
        //tree 
    ];
    
    public function init()
    {
        parent::init();
        //if(isset(self::$appendToParent[$this->plugin])){
            $this->{self::$appendToParent[$this->plugin]}();
        //}else{
        //    $this->appendDivToParent();
        //}
    }
    
    public function run()
    {
        $this->registerScript();
        if(isset(self::$addContentMethod[$this->plugin])){
            foreach($this->contents as $id=>$options){

                if(is_string($id)){
                    $options['clientOptions']['id']=$id;
                }

                if(!isset($options['plugin'])){
                    $options['plugin']=$this->plugin;
                    $options['method'] = self::$addContentMethod[$this->plugin];
                }else if($options['plugin']===$this->plugin){
                    $options['method'] = self::$addContentMethod[$this->plugin];
                }else{
                    $options['parent'] = $this->clientOptions['id'];
                }

                $options['target']=$this->target;
                Easy::widget($options);
            }
        }
    }
}