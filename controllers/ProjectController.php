<?php
Yii::import('AmWidgets.AmProjectTree');

class ProjectController extends AmController
{
    public $defaultAction = 'view';
    protected $project;
    
    public function actionView()
    {
        $this->render('view', array(
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
        
        if ($options = $this->getPost('AmOptions')) {
            if (!$entity->canUpdate() && $entity->getParent()) {
                $this->setFlash('error', 'Unable to update entity.');
            }

            if ($name = $this->getPost('name')) {
                $entity->setName($name);
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

        $this->renderPartial('update', array(
            'entity' => $entity,
        ));
    }
    
    protected function getProject()
    {
        if (null === $this->project) {
            $this->project = new AmEntityProject();
        }
        return $this->project;
    }
}