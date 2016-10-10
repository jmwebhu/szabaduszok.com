<?php

/**
 * Class Authorization_Role_Project
 *
 * Eroforrasok hozzaferesehez tartozo 'role' -ok kezelese
 *
 * Felelosseg: egy adott role hozzaferes szabalyozasa
 */

abstract class Authorization_Role_Project extends Authorization_Role
{
    /**
     * @return bool
     */
    public function canCreate()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canEdit()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canDelete()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function hasCancel()
    {
        return false;
    }
}