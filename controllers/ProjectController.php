<?php
Yii::import('AmWidgets.AmProjectTree');

class ProjectController extends AmController
{
    public $defaultAction = 'view';
    protected $project;
    
    public function actionView()
    {
        $this->render('view');
    }
    
    public function actionChildren()
    {
        $id = Yii::app()->getRequest()->getQuery('root');
        $project = $this->getProject();
        $entity = ('source' === $id) ? $project : $project->getChild($id);
        
        echo AmProjectTree::saveEntityAsJson($entity);
    }
    
    public function actionEntity()
    {
        $entity = $this->getProject()->getChild($_POST['id']);
        $this->renderPartial('entity', array(
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