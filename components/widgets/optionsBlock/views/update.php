<?php
    Yii::app()->clientScript->registerScript('appManager-update', '
        $("textarea").tabby({
            "tabString" : "    "
        });
    ');
?>
<fieldset>
    <?php if ($this->title): ?>
        <legend><?php echo AppManagerModule::t($this->title); ?></legend>
    <?php endif; ?>
    <?php 
        $split = ceil($options->count / 2); 
        $index = 0;
    ?>
    <div class="block first">
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