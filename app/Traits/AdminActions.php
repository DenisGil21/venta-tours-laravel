<?php 
namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

/**
 * 
 */
trait AdminActions
{
    protected function allowedAdminAction()
    {
        if (Gate::denies('admin-actions')) {
            throw new AuthorizationException('Esta accion no te es permitida');
        }
    }

}