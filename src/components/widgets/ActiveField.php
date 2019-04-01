<?php

namespace sheillendra\jeasyui\components\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ActiveField extends \yii\widgets\ActiveField {

    public $options = ['style' => 'margin-bottom: 5px'];
    public $template = "{input}";

    /**
     * 
     * @inheritdoc
     */
    public function begin() {
        if ($this->form->enableClientScript) {
            $clientOptions = $this->getClientOptions();
            if (!empty($clientOptions)) {
                $this->form->attributes[] = $clientOptions;
            }
        }

        $inputID = $this->getInputId();
        $attribute = Html::getAttributeName($this->attribute);
        $options = $this->options;
        $class = isset($options['class']) ? (array) $options['class'] : [];
        $class[] = "field-$inputID";
        
        if ($this->model->hasErrors($attribute)) {
            $class[] = $this->form->errorCssClass;
        }

        $options['class'] = implode(' ', $class);
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        return Html::beginTag($tag, $options);
    }

    public function textInput($options = array()) {
        $options['class'] = 'easyui-textbox';
        return parent::textInput($this->easyuiOptions($options));
    }

    public function textarea($options = array()) {
        $options['class'] = 'easyui-textbox';
        $options['data']['options']['multiline'] = true;
        return parent::textarea($this->easyuiOptions($options));
    }
    
    public function dropDownList($items, $options = array()) {
        $options['class'] = 'easyui-combobox';
        return parent::dropDownList($items, $this->easyuiOptions($options));
    }

    public function easyuiOptions($options){
        $attribute = Html::getAttributeName($this->attribute);
        $options['data']['options']['label'] = $this->model->getAttributeLabel($attribute);
        $options['data']['options']['width'] = '100%';
        if ($this->model->isAttributeRequired($attribute)) {
            $options['data']['options']['required'] = true;
        }
        return $options;
    }
}
