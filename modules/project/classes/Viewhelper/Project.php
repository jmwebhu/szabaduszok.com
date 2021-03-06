<?php

/**
 * Class Viewhelper_Project
 *
 * Felelosseg: view -ban hasznalt segedfuggvenyek
 */

class Viewhelper_Project
{
    /**
     * @param string $action
     * @return string
     */
	public static function getPageTitle($action = 'create')
	{
	    $class = self::getClassByAction($action);
        return $class::getPageTitle();
	}

    /**
     * @param string $action
     * @return bool
     */
	public static function hasIdInput($action = 'create')
	{
        $class = self::getClassByAction($action);
        return $class::hasIdInput();
	}

    /**
     * @param string $action
     * @param Entity_Project|null $project
     * @return string
     */
	public static function getFormAction($action = 'create', Entity_Project $project = null)
	{
        $class = self::getClassByAction($action);
        return $class::getFormAction($project);
	}

    /**
     * @param Model_User|null $user
     * @param string $action
     * @param Entity_Project|null $project
     * @return string
     */
	public static function getEmail(Model_User $user = null, $action = 'create', Entity_Project $project = null)
	{
        $class = self::getClassByAction($action);
        return $class::getEmail($user, $project);
	}

    /**
     * @param Model_User|null $user
     * @param string $action
     * @param Entity_Project|null $project
     * @return string
     */
	public static function getPhonenumber(Model_User $user = null, $action = 'create', Entity_Project $project = null)
	{
        $class = self::getClassByAction($action);
        return $class::getPhonenumber($user, $project);
	}

    /**
     * @param Entity_Project $project
     * @return array ['salary', 'postfix']
     */
	public static function getSalary(Entity_Project $project)
	{
        $salary = self::getSalaryByLowHigh($project);
		$postfix = ' Ft';

		if ($project->getSalaryType() == 1) {
			$postfix = ' Ft /óra';
		}
		
		return [
			'salary'	=> $salary,
			'postfix'	=> $postfix
		];
	}

    /**
     * @param Entity_Project $project
     * @return string
     */
	protected static function getSalaryByLowHigh(Entity_Project $project)
    {
        if ($project->isSalariesEqual()) {
            $salary = number_format($project->getSalaryLow(), 0, '.', ' ');
        } else {
            $sb = SB::create(number_format($project->getSalaryLow(), 0, '.', ' '));
            $sb->append(' - ')->append(number_format($project->getSalaryHigh(), 0, '.', ' '));

            $salary = $sb->get();
        }

        return $salary;
    }

    /**
     * @param string $action
     * @return string
     */
	protected static function getClassByAction($action)
    {
        return 'Viewhelper_Project_' . ucfirst($action);
    }
}