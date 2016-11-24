<?php

class Notification_User
{
    /**
     * @param Model_Project $project
     */
    public static function notifyProjectNew(Model_Project $project)
    {
        $entityProject = new Entity_Project($project);
        $search = Project_Notification_Search_Factory_User::makeSearch([
            'industries'    => $project->getRelationIds('industries'),
            'professions'   => $project->getRelationIds('professions'),
            'skills'        => $project->getRelationIds('skills'),
        ]);

        $users = $search->search();

        foreach ($users as $user) {
            $entityUser             = Entity_User::createUser($user->type, $user);
            $notification = Entity_Notification::createFor(Model_Event::TYPE_PROJECT_NEW, $entityProject, $entityUser);

            $entity = new Entity_User_Freelancer($user);
            $entity->setNotification($notification);
            $entity->sendNotification();
        }
    }
}