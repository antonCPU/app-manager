<?php
$this->breadcrumbs=array(
    $entity->title => array('view', 'id' => $entity->id),
    AppManagerModule::t('Update'),
); 
?>
<h1><?php echo $entity->title; ?></h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'settings-form',
	'enableAjaxValidation'=>false,
    'enableClientValidation' => false,
)); ?>
    <?php $this->widget('AmWidgets.optionsBlock.AmOptionsBlock', array(
        'form'      => $form,
        'options'   => $entity->options,
        'title'     => null,
    )); ?>
    <div class="row buttons">
		<?php echo CHtml::submitButton(AppManagerModule::t('Save')); ?>
        <?php if ($entity->canRestore()): ?>
            <?php echo CHtml::submitButton(AppManagerModule::t('Restore'), array(
                'name' => 'restore',
                'onclick' => 'if(!confirm(' 
                             . CJavaScript::encode(AppManagerModule::t('Are you sure?'))
                             . ')) return false;',
            )); ?>
        <?php endif; ?>
	</div>
<?php $this->endWidget(); ?>
</div>