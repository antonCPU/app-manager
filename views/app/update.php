<?php
$this->breadcrumbs=array(
    $this->getTitle() => array('/appManager/app'),
    $this->getSectionTitle() => array($this->getSection()),
    $entity->title => array('view', 'id' => $entity->id),
    AppManagerModule::t('Edit')
); 

$this->module->registerJs('jquery.textarea.js');
Yii::app()->clientScript->registerScript('appManager-update', '
    $("textarea").tabby({
        "tabString" : "    "
    });
');
?>
<h1><?php echo $entity->title; ?></h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'settings-form',
	'enableAjaxValidation'=>false,
    'enableClientValidation' => false,
)); ?>
    <fieldset>
        <div class="row">
            <?php echo $form->labelEx($entity,'name'); ?>
            <?php echo $form->textField($entity,'name', array('class' => 'textfield')); ?>
            <?php echo $form->error($entity,'name'); ?>
        </div> 
    </fieldset>
    <fieldset>
        <legend><?php echo AppManagerModule::t('Options'); ?></legend>
        <?php 
            $split = ceil($entity->options->count / 2); 
            $index = 0;
        ?>
        <div class="block first">
        <?php $options = $entity->options; ?>
        <?php foreach ($options as $attribute => $option): ?>
            <?php if ($index == $split): ?>
                </div><div class="block last">
            <?php endif; ?>
            <div class="row">
                <?php echo $form->labelEx($options, $attribute); ?>
                <div class="hint"><?php echo $option->desc; ?></div>
                <div class="note">
                    <?php if (!$option->isDefault()): ?><span>*</span><?php endif; ?>
                    default
                    <?php if ($option->type): ?>
                        (<?php echo $option->type; ?>)
                    <?php endif; ?>
                    : 
                    <pre><?php echo $option->textDefault; ?></pre>
                </div>
                <?php if ($option->isArray || !$option->type): ?>
                    <?php echo $form->textArea($options, $attribute); ?>
                <?php else: ?>
                    <?php echo $form->textField($options, $attribute, array('class' => 'textfield')); ?>
                <?php endif; ?>
                <?php echo $form->error($options, $attribute); ?>
            </div> 
            <?php $index++; ?>
        <?php endforeach; ?>
        </div>
    </fieldset>
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