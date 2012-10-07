<?php
$this->breadcrumbs=array(
    $this->getTitle(),
); 
?>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'settings-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => false,
    )); ?>
        <?php $options = $model->options; ?>
        <?php foreach ($options as $attribute => $option): ?>
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
        <?php endforeach; ?>
    <?php $this->endWidget(); ?>
</div>