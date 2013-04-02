<div class="form">
    <?php $form=$this->beginWidget('CActiveForm'); ?>
        <input type="hidden" name="id" value="<?php echo $entity->id; ?>" />
        <?php $this->widget('AmWidgets.optionsBlock.AmOptionsBlock', array(
            'form'      => $form,
            'options'   => $entity->getOptions(),
        )); ?>
        
        <div class="row buttons">
            <?php echo CHtml::submitButton(AppManagerModule::t('Save')); ?>
        </div>   
    <?php $this->endWidget(); ?>
</div>