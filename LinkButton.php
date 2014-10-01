<?php
namespace sheillendra\jeasyui;

class LinkButton extends Widget
{
    public $parent;
    private $url='javascript:void(0);';
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if(isset($this->clientOptions['id'])){
            $this->target='#'.$this->clientOptions['id'];
            $this->beforeRegister='$("#'.$this->parent.'").append(\'<a id="'
                    .$this->clientOptions['id']
                    .'" href="'.$this->url.'">'
                    .$this->clientOptions['title']
                    .'</a>\');';
            
            unset($this->clientOptions['onClick']);
        }
    }
    
    public function run()
    {
        $this->registerScript('linkbutton');
    }
}