<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile\ListContact;
use App\Models\Profile\ListDepartment;
use App\Models\Profile\ListGender;
use App\Models\Profile\ListPosition;
use App\Models\Profile\ListProfession;
use App\Models\Profile\ListRelation;
use App\Models\Profile\ListRelationship;

class ProfileListController extends Controller
{
    public function gender()
    {
        return ListGender::get();
    }

    public function relationship()
    {
        return ListRelationship::get();
    }

    public function relation()
    {
        return ListRelation::get();
    }

    public function contact()
    {
        return ListContact::get();
    }

    public function profession()
    {
        return ListProfession::get();
    }

    public function position()
    {
        return ListPosition::get();
    }

    public function department()
    {
        return ListDepartment::get();
    }
}
