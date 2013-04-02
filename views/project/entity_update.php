<div class="entity-update">
    <?php if ($entity->asa('config')): ?>
        <?php $this->renderPartial('entity_config', array('entity' => $entity)); ?>
    <?php endif; ?>
    <?php if ($entity->asa('options')): ?>
        <?php $this->renderPartial('entity_options', array('entity' => $entity)); ?>
    <?php endif; ?>
</div>
<script type="text/javascript">
jQuery(function($) {
   
   $('.entity-update form').on('submit', function(e) {
      var $form = $(this);
      $.post("<?php echo $this->createUrl('update'); ?>", $form.serialize(), function(ans) {
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