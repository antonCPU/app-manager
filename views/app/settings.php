<?php
$this->breadcrumbs=array(
    $this->getTitle() => array('/appManager/app'),
    AppManagerModule::t('Settings') => array('settings'),
); 
?>
<h1><?php echo AppManagerModule::t('Settings'); ?></h1>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'settings-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => false,
    )); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'preload'); ?>
        <?php echo $form->textField($model, 'preload'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'import'); ?>
        <?php echo $form->textArea($model, 'import'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'params'); ?>
        <?php echo $form->textArea($model, 'params'); ?>
    </div>
    
    <div class="row buttons">
		<?php echo CHtml::submitButton(AppManagerModule::t('Save')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>