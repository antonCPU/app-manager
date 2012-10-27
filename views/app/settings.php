<?php
$this->breadcrumbs=array(
    $this->getTitle() => array('/appManager/app'),
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
   
    <div class="row buttons">
		<?php echo CHtml::submitButton(AppManagerModule::t('Save')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>