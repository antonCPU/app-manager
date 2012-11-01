<?php
$this->breadcrumbs=array(
    AppManagerModule::t('Settings'),
); 
?>
<h1><?php echo AppManagerModule::t('Settings'); ?></h1>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'settings-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => false,
    )); ?>

    <?php $this->widget('AmWidgets.optionsBlock.AmOptionsBlock', array(
        'title'     => null,
        'form'      => $form,
        'options'   => $entity->options,
    )); ?>
    <?php if ($entity->canUpdate()): ?>
        <div class="row buttons">
            <?php echo CHtml::submitButton(AppManagerModule::t('Save')); ?>
        </div>
    <?php endif; ?>
</div>
<?php $this->endWidget(); ?>