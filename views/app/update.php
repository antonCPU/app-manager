<h1><?php echo $entity->title; ?></h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'settings-form',
	'enableAjaxValidation'=>false,
    'enableClientValidation' => false,
)); ?>
    <?php if ($entity->defaultName): ?>
        <fieldset>
            <div class="row">
                <?php echo $form->labelEx($entity,'name'); ?>
                <div class="hint"><?php echo AppManagerModule::t('This name is used in the configue.'); ?></div>
                <div class="note">
                    <?php if (!$entity->isDefaultName): ?><span>*</span><?php endif; ?>
                    <?php echo AppManagerModule::t('default'); ?>: 
                    <pre><?php echo $entity->defaultName; ?></pre>
                </div>
                <?php echo $form->textField($entity,'name', array('class' => 'textfield')); ?>
                <?php echo $form->error($entity,'name'); ?>
            </div> 
        </fieldset>
    <?php endif; ?>
    <?php $this->widget('AmWidgets.optionsBlock.AmOptionsBlock', array(
        'form'      => $form,
        'options'   => $entity->options,
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