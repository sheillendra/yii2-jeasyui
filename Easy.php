<?php
namespace sheillendra\jeasyui;
use yii\helpers\Html;
class Easy extends Widget
{
    public $name;
    public $parent;
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        if($this->parent){
            $this->getView()->registerJs('jQuery("'.$this->parent.'").append("'.Html::tag('div',$this->options['id']).'");');
        }
    }
    
    public function run()
    {
        $this->registerAsset($this->name,$this->method);
    }
}