<?php

class AmOptionsBlock extends CWidget
{
    public $options;
    public $form;
    
    public function init()
    {
        if ($this->options) {
            $this->initScripts();
        }
    }
    
    public function run()
    {
        if ($this->options) {
            $this->render('update', array(
                'form'    => $this->form,
                'options' => $this->options,
            ));
        }
    }
    
    protected function initScripts()
    {
        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
        Yii::app()->clientScript->registerScriptFile($assets . '/jquery.textarea.js');
    }
}
