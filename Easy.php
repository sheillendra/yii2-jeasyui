<?php
namespace sheillendra\jeasyui;

class Easy extends Widget
{
    protected $appendToParent = [
        'accordion'=>'appendTargetToParent'
        ,'datagrid'=>'appendTargetToParent'
        ,'layout'=>'appendTargetToParent'
        ,'linkbutton'=>'appendLinkToParent'
        ,'propertygrid'=>'appendTargetToParent'
        ,'tabs'=>'appendTargetToParent'
    ];
    protected $addContentMethod = [
        'accordion'=>'add',
        'layout'=>'add',
        'linkbutton'=>'',
        'propertygrid'=>'appendRow',
        'tabs'=>'add'
    ];
    
    public function init()
    {
        parent::init();
        $this->{$this->appendToParent[$this->plugin]}();
    }
    
    public function run()
    {
        $this->registerScript();
        foreach($this->contents as $id=>$options){
            if(is_string($id)){
                $options['clientOptions']['id']=$id;
            }
            if(!isset($options['plugin'])){
                $options['plugin']=$this->plugin;
                $options['method'] = $this->addContentMethod[$this->plugin];
            }else{
                if($options['plugin']===$this->plugin){
                    $options['method'] = $this->addContentMethod[$this->plugin];
                }else{
                    $options['parent'] = $this->clientOptions['id'];
                }
            }
            $options['target']=$this->target;
            Easy::widget($options);
        }
    }
}