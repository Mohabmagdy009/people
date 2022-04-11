<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillCreateRequest;
use App\Http\Requests\SkillUpdateRequest;
use App\Skill;
use App\UserSkill;
use Datatables;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __construct()
    {
    }

    public function getList()
    {
        return view('skill/list');
    }

    public function listOfSkills()
    {
        $skillList = Skill::select('id', 'domain', 'subdomain', 'technology', 'skill', 'certification','created_at');
        $data = Datatables::of($skillList)->make(true);

        return $data;
    }

    public function show($id)
    {
        $skill = Skill::find($id);

        return view('skill/show', compact('skill'));
    }

    public function getFormCreate()
    {
        return view('skill/create_update')->with('action', 'create');
    }

    public function getFormUpdate($id)
    {
        $skill = Skill::find($id);

        return view('skill/create_update', compact('skill'))->with('action', 'update');
    }

    public function postFormCreate(skillCreateRequest $request)
    {
        $inputs = $request->only('domain', 'subdomain', 'technology', 'skill', 'certification');
        $date=time();
        $inputs += ['created_at' => date("Y-m-d h:i:sa", $date)];
        Skill::create($inputs);

        return redirect('skillList')->with('success', 'Record created successfully');
    }

    public function postFormUpdate(skillUpdateRequest $request, $id)
    {
        $inputs = $request->only('domain', 'subdomain', 'technology', 'skill', 'certification');
        $skill = Skill::find($id);
        $skill->update($inputs);

        return redirect('skillList')->with('success', 'Record updated successfully');
    }

    public function delete($id)
    {
        // When using stdClass(), we need to prepend with \ so that Laravel won't get confused...
        $result = new \stdClass();

        // Include a check to see if a user has this skill or not and cannot delete in case there is a link with a user !!!
        if (count(UserSkill::where('skill_id', $id)->get()) > 0) {
            $result->result = 'error';
            $result->msg = 'Record cannot be deleted because some users are associated to this skill';

            return json_encode($result);
        } else {
            $skill = Skill::destroy($id);
            $result->result = 'success';
            $result->msg = 'Record deleted successfully';

            return json_encode($result);
        }
    }
}
