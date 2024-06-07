<?php

namespace App\Enums;

class UserRole
{
    const CLIENT = 'cliente';
    const COLLABORATOR = 'colaborador';

    public static function isValidRole(string $role): bool
    {
        return in_array($role, [
            self::CLIENT,
            self::COLLABORATOR
        ]);
    }
}
