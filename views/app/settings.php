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
        <?php echo $form->textField($model, 'name', array('class' => 'textfield')); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'preload'); ?>
        <div class="hint"><?php echo AppManagerModule::t('Comma-separated list.'); ?></div>
        <?php echo $form->textField($model, 'preload', array('class' => 'textfield')); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'import'); ?>
        <div class="hint"><?php echo AppManagerModule::t('One path per line.'); ?></div>
        <?php echo $form->textArea($model, 'import'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'params'); ?>
        <div class="hint"><?php echo AppManagerModule::t('One option per line (name:value).'); ?></div>
        <?php echo $form->textArea($model, 'params'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'aliases'); ?>
        <div class="hint"><?php echo AppManagerModule::t('One alias per line (name:path.to.alias).'); ?></div>
        <?php echo $form->textArea($model, 'aliases'); ?>
    </div>
    
    <div class="row buttons">
		<?php echo CHtml::submitButton(AppManagerModule::t('Save')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>