<?php
Yii::import('AmWidgets.AmProjectTree');

class ProjectController extends AmController
{
    protected $project;
    
    public function actionIndex()
    {
        $this->render('index', array(
            'entity' => $this->getProject(),
        ));
    }
    
    public function actionChildren()
    {
        $id = Yii::app()->getRequest()->getQuery('root');
        $project = $this->getProject();
        $entity = ('source' === $id) ? $project : $project->getChild($id);
        
        echo AmProjectTree::saveEntityAsJson($entity);
    }
    
    public function actionUpdate()
    {
        $entity = $this->getProject()->getChild($_REQUEST['id']);
        
        if ($name = $this->getPost('name')) {
            $entity->changeName($name);
        }
        
        if ($options = $this->getPost('AmOptions')) {
            if (!$entity->canUpdate() && $entity->getParent()) {
                $this->setFlash('error', 'Unable to update entity.');
            }    
            $entity->setOptions($options);

            if ($entity->update()) {
                $this->setFlash('success', 'entity has been updated.');
            } else {
                $this->setFlash('error', 'Unable to update. Incorrect input.');
            }
        } elseif ($action = $this->getQuery('action')) {
            $entity->$action();
        }

        $this->renderPartial('entity_update', array(
            'entity' => $entity,
        ));
    }
	
	public function actionEntity()
	{
		$this->renderPartial('entity', array(
			'entity' => $this->getProject()->getChild($_REQUEST['id']),
		), false, true);
	}
    
    protected function getProject()
    {
        if (null === $this->project) {
            $this->project = new AmEntityProject();
        }
        return $this->project;
    }
}