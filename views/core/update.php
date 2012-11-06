<div class="view-title">
    <h1><?php echo $entity->title; ?></h1>
    <?php $this->widget('zii.widgets.CMenu',array(
            'items'=>array(
                array(
                    'label'  => AppManagerModule::t('View'), 
                    'url'    => array('view', 'id' => $entity->id), 
                    'visible' => $entity->canView(),
                ),
                array(
                    'label'  => AppManagerModule::t('Deactivate'), 
                    'url'    => array('deactivate', 'id' => $entity->id), 
                    'visible' => $entity->canDeactivate(),
                    'itemOptions' => array('class' => 'confirm'),
                ),
            )
    )); ?>
</div>
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
    <?php if ($entity->canUpdate()): ?>
        <div class="row buttons">
            <?php echo CHtml::submitButton(AppManagerModule::t('Save')); ?>
            <?php if ($entity->canRestore()): ?>
                <?php echo CHtml::submitButton(AppManagerModule::t('Restore'), array(
                    'name' => 'restore',
                    'class' => 'confirm',
                )); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php $this->endWidget(); ?>
</div>