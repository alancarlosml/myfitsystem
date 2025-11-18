<?php

namespace App\Http\Traits;

use App\Models\Establishment;
use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use App\Services\AccessControlService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

trait HasEstablishmentContext
{
    /**
     * Get current establishment ID from session
     */
    protected function getEstablishmentId()
    {
        return Session::get('establishment_id');
    }

    protected function accessControlService(): AccessControlService
    {
        return app(AccessControlService::class);
    }

    /**
     * Get current authenticated user (user guard)
     */
    protected function getCurrentUser()
    {
        return Auth::guard('user')->user();
    }

    /**
     * Get current authenticated student (student guard)
     */
    protected function getCurrentStudent()
    {
        return Auth::guard('student')->user();
    }

    /**
     * Get user's role for current establishment
     */
    protected function getUserRole($establishmentId = null)
    {
        $establishmentId = $establishmentId ?? $this->getEstablishmentId();
        $user = $this->getCurrentUser();
        
        if (!$user) {
            return null;
        }

        // Check if user has the method (User model should have it)
        if (!method_exists($user, 'getRoleForEstablishment')) {
            return null;
        }

        if ($establishmentId) {
            /** @var \App\Models\User $user */
            return $user->getRoleForEstablishment($establishmentId);
        }

        // Check if user is superuser without establishment
        if ($this->checkIsSuperuser($user)) {
            return (object)['name' => 'superuser'];
        }

        return null;
    }

    /**
     * Check if user is superuser
     */
    protected function checkIsSuperuser($user = null)
    {
        $user = $user ?? $this->getCurrentUser();
        
        if (!$user) {
            return false;
        }

        $superuserRole = Role::where('name', 'superuser')->first();
        if (!$superuserRole) {
            return false;
        }

        return DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('role_id', $superuserRole->id)
            ->exists();
    }

    /**
     * Check if current user is superuser (returns role object or false)
     */
    protected function getSuperuserRole()
    {
        if ($this->checkIsSuperuser()) {
            return (object)['name' => 'superuser'];
        }
        return null;
    }

    /**
     * Check if user has a specific role
     */
    protected function hasRole($roleName, $establishmentId = null)
    {
        $role = $this->getUserRole($establishmentId);
        
        if (!$role) {
            // Check superuser separately
            if ($roleName === 'superuser') {
                return $this->checkIsSuperuser();
            }
            return false;
        }

        return $role->name === $roleName;
    }

    /**
     * Check if user has any of the specified roles
     */
    protected function hasAnyRole(array $roleNames, $establishmentId = null)
    {
        foreach ($roleNames as $roleName) {
            if ($this->hasRole($roleName, $establishmentId)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Apply establishment filter to query if user is not superuser
     */
    protected function applyEstablishmentFilter($query, $establishmentColumn = 'establishment_id', $establishmentId = null)
    {
        $establishmentId = $establishmentId ?? $this->getEstablishmentId();
        $role = $this->getUserRole($establishmentId);
        
        // Superuser can see all
        if ($this->hasAnyRole(['superuser'], $establishmentId)) {
            return $query;
        }

        // Filter by establishment or return empty if no establishment selected
        if ($establishmentId) {
            $establishment = Establishment::find($establishmentId);

            if (!$establishment || !$this->accessControlService()->establishmentHasActiveSystemContract($establishment)) {
                return $query->whereRaw('1 = 0');
            }

            return $query->where($establishmentColumn, $establishmentId);
        } else {
            return $query->whereRaw('1 = 0'); // Return empty
        }
    }

    /**
     * Get establishments available for current user
     */
    protected function getAvailableEstablishments()
    {
        $establishmentId = $this->getEstablishmentId();
        $role = $this->getUserRole($establishmentId);

        if ($this->hasAnyRole(['superuser'], $establishmentId)) {
            return Establishment::all();
        }

        if ($establishmentId) {
            $establishment = Establishment::find($establishmentId);

            if ($establishment && $this->accessControlService()->establishmentHasActiveSystemContract($establishment)) {
                return collect([$establishment]);
            }
        }

        return collect([]);
    }

    /**
     * Get users available for current user (filtered by establishment if not superuser)
     */
    protected function getAvailableUsers()
    {
        $establishmentId = $this->getEstablishmentId();
        
        if ($this->hasAnyRole(['superuser'], $establishmentId)) {
            return User::all();
        }

        if ($establishmentId) {
            $establishment = Establishment::find($establishmentId);

            if (!$establishment || !$this->accessControlService()->establishmentHasActiveSystemContract($establishment)) {
                return collect([]);
            }

            // Optimized: Use join instead of whereHas
            return User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->where('role_user.establishment_id', $establishmentId)
                ->select('users.*')
                ->distinct()
                ->get();
        }

        return collect([]);
    }

    /**
     * Get students available for current user (filtered by establishment if not superuser)
     */
    protected function getAvailableStudents()
    {
        $establishmentId = $this->getEstablishmentId();
        
        if ($this->hasAnyRole(['superuser'], $establishmentId)) {
            return Student::all();
        }

        if ($establishmentId) {
            $establishment = Establishment::find($establishmentId);

            if (!$establishment || !$this->accessControlService()->establishmentHasActiveSystemContract($establishment)) {
                return collect([]);
            }

            // Optimized: Use join instead of whereHas
            return Student::join('student_establishment', 'students.id', '=', 'student_establishment.student_id')
                ->where('student_establishment.establishment_id', $establishmentId)
                ->select('students.*')
                ->distinct()
                ->get();
        }

        return collect([]);
    }

    /**
     * Verify if resource belongs to current establishment (unless superuser)
     */
    protected function verifyResourceAccess($resource, $establishmentColumn = 'establishment_id')
    {
        $establishmentId = $this->getEstablishmentId();
        
        // Superuser can access any resource
        if ($this->hasAnyRole(['superuser'], $establishmentId)) {
            return true;
        }

        // Check if resource belongs to current establishment
        if ($establishmentId && isset($resource->$establishmentColumn)) {
            $establishment = Establishment::find($establishmentId);

            if (!$establishment || !$this->accessControlService()->establishmentHasActiveSystemContract($establishment)) {
                return false;
            }

            return $resource->$establishmentColumn == $establishmentId;
        }

        return false;
    }

    /**
     * Abort if user doesn't have access to resource
     */
    protected function authorizeResourceAccess($resource, $establishmentColumn = 'establishment_id', $message = 'Acesso negado')
    {
        if (!$this->verifyResourceAccess($resource, $establishmentColumn)) {
            abort(403, $message);
        }
    }
}

