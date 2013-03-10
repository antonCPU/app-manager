<div class="entity-update">
    <div class="view-title">
        <h1><?php echo $entity->title; ?></h1>
        <?php $this->widget('zii.widgets.CMenu',array(
            'htmlOptions' => array(
                'class' => 'entity-actions',
            ),
            'items'=>array(
                array(
                    'label'  => AppManagerModule::t('Restore'), 
                    'url'    => array('update', 'action' => 'restore', 'id' => $entity->id), 
                    'visible' => $entity->asa('config') && $entity->canRestore(),
                ),
                array(
                    'label'  => AppManagerModule::t('Activate'), 
                    'url'    => array('update', 'action' => 'activate', 'id' => $entity->id), 
                    'visible' => $entity->asa('config') && $entity->canActivate(),
                ),
                array(
                    'label'  => AppManagerModule::t('Deactivate'), 
                    'url'    => array('update', 'action' => 'deactivate', 'id' => $entity->id),  
                    'visible' => $entity->asa('config') && $entity->canDeactivate(),
                ),
            ),
        )); ?>
    </div>
    <div class="entity-form form">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'entity-form',
            'enableAjaxValidation'=>false,
            'enableClientValidation' => false,
        )); ?>
            <input type="hidden" name="id" value="<?php echo $entity->id; ?>" />
            <?php if ($entity->asa('config') && $entity->defaultName): ?>
                <fieldset>
                    <div class="row">
                        <?php echo $form->labelEx($entity,'name'); ?>
                        <div class="hint"><?php echo AppManagerModule::t('This name is used in the config.'); ?></div>
                        <div class="note">
                            <?php if (!$entity->isDefaultName()): ?><span>*</span><?php endif; ?>
                            <?php echo AppManagerModule::t('default'); ?>: 
                            <pre><?php echo $entity->getDefaultName(); ?></pre>
                        </div>
                        <?php echo CHtml::textField('name', $entity->getName(), array('class' => 'textfield')); ?>
                        <?php echo $form->error($entity,'name'); ?>
                    </div> 
                </fieldset>
            <?php endif; ?>
            <?php $this->widget('AmWidgets.optionsBlock.AmOptionsBlock', array(
                'form'      => $form,
                'options'   => $entity->asa('config') ? $entity->getOptions() : null,
            )); ?>
            <?php if ($entity->asa('config') && $entity->canUpdate()): ?>
                <div class="row buttons">
                    <?php echo CHtml::submitButton(AppManagerModule::t('Save')); ?>
                </div>
            <?php endif; ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
jQuery(function($) {
   
   $('form').on('submit', function(e) {
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize(), function(ans) {
          updatePage(ans);
      });
      return false; 
   });
   
   $(".entity-actions a").click(function() {
        if (!confirm("<?php echo AppManagerModule::t('Are you sure?'); ?>")) {
            return false;
        }
        
        $.post($(this).attr('href'), null, function(ans) {
           updatePage(ans);
        });
        
        return false;
    });
   
   function updatePage(content) {
       $('.entity-update').replaceWith(content); 
   }
});
</script>