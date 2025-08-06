<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\All\Grade\GradeInterface;
use App\Repositories\All\Grade\GradeRepository;
use App\Repositories\All\GradeClass\GradeClassInterface;
use App\Repositories\All\GradeClass\GradeClassRepository;
use App\Repositories\All\Marks\MarksInterface;
use App\Repositories\All\Marks\MarksRepository;
use App\Repositories\All\Medium\MediumInterface;
use App\Repositories\All\Medium\MediumRepository;
use App\Repositories\All\Relation\RelationInterface;
use App\Repositories\All\Relation\RelationRepository;
use App\Repositories\All\School\SchoolInterface;
use App\Repositories\All\School\SchoolRepository;
use App\Repositories\All\Subject\SubjectInterface;
use App\Repositories\All\Subject\SubjectRepository;
use App\Repositories\All\User\UserInterface;
use App\Repositories\All\User\UserRepository;
use App\Repositories\All\UserAccess\UserAccessInterface;
use App\Repositories\All\UserAccess\UserAccessRepository;
use App\Repositories\All\UserParent\UserParentInterface;
use App\Repositories\All\UserParent\UserParentRepository;
use App\Repositories\All\UserRole\UserRoleInterface;
use App\Repositories\All\UserRole\UserRoleRepository;
use App\Repositories\All\UserStudent\UserStudentInterface;
use App\Repositories\All\UserStudent\UserStudentRepository;
use App\Repositories\All\UserTeacher\UserTeacherInterface;
use App\Repositories\All\UserTeacher\UserTeacherRepository;
use App\Repositories\All\UserType\UserTypeInterface;
use App\Repositories\All\UserType\UserTypeRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(UserRoleInterface::class, UserRoleRepository::class);
        $this->app->bind(UserTypeInterface::class, UserTypeRepository::class);
        $this->app->bind(UserAccessInterface::class, UserAccessRepository::class);
        $this->app->bind(SubjectInterface::class, SubjectRepository::class);
        $this->app->bind(MediumInterface::class, MediumRepository::class);
        $this->app->bind(RelationInterface::class, RelationRepository::class);
        $this->app->bind(SchoolInterface::class, SchoolRepository::class);
        $this->app->bind(GradeInterface::class, GradeRepository::class);
        $this->app->bind(GradeClassInterface::class, GradeClassRepository::class);
        $this->app->bind(UserTeacherInterface::class, UserTeacherRepository::class);
        $this->app->bind(UserStudentInterface::class, UserStudentRepository::class);
        $this->app->bind(UserParentInterface::class, UserParentRepository::class);
        $this->app->bind(MarksInterface::class, MarksRepository::class);
    }
}
