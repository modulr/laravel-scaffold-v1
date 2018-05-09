<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile\ProfileListContact;
use App\Models\Profile\ProfileListDepartment;
use App\Models\Profile\ProfileListGender;
use App\Models\Profile\ProfileListPosition;
use App\Models\Profile\ProfileListProfession;
use App\Models\Profile\ProfileListRelation;
use App\Models\Profile\ProfileListRelationship;

class ProfileListController extends Controller
{
    public function gender()
    {
        return ProfileListGender::get();
    }

    public function relationship()
    {
        return ProfileListRelationship::get();
    }

    public function relation()
    {
        return ProfileListRelation::get();
    }

    public function contact()
    {
        return ProfileListContact::get();
    }

    public function profession()
    {
        return ProfileListProfession::get();
    }

    public function position()
    {
        return ProfileListPosition::get();
    }

    public function department()
    {
        return ProfileListDepartment::get();
    }
}
