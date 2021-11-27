<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\UserRepository;
use Auth;

class AuthUsersForDataView
{
    protected $userRepository;

    public $year_list;
    public $year_selected = '';
    public $year_select_disabled = 'true';
    public $manager_list;
    public $manager_selected = '';
    public $manager_select_disabled = 'true';
    public $user_list;
    public $user_selected = '';
    public $user_select_disabled = 'true';

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function userCanView($permissionCheck = '')
    {
        // This function will check the user permissions and correctly assign the selectable lists for the view
        // We get the list of years from the config file then the selected year is today's year by default
        $this->year_list = config('select.year');
        $this->year_selected = date('Y');
        $this->year_select_disabled = 'false';

        if (! empty($permissionCheck) && Auth::user()->can($permissionCheck)) {
            // Format of $manager_list is [ 1=> 'manager1', 2=>'manager2',...]
            $this->manager_list = $this->userRepository->getManagersList();
            $this->manager_selected = '';
            $this->manager_select_disabled = 'false';

            $this->user_list = $this->userRepository->getAllUsersList();
            $this->user_selected = '';
            $this->user_select_disabled = 'false';
        } elseif (Auth::user()->is_manager == 1) {
            $this->manager_list = [Auth::user()->id => Auth::user()->name];
            $this->user_list = Auth::user()->employees()->pluck('name', 'user_id');
            $this->user_list->prepend(Auth::user()->name, Auth::user()->id);
            $this->manager_selected = Auth::user()->id;
            $this->manager_select_disabled = 'true';
            $this->user_select_disabled = 'false';
        } else {
            $my_manager = Auth::user()->managers()->first();
            if ($my_manager) {
                $this->manager_list = [$my_manager->id => $my_manager->name];
                $this->manager_selected = $my_manager->id;
            } else {
                $this->manager_list = [0 => 'none'];
            }
            $this->user_list = [Auth::user()->id => Auth::user()->name];
            $this->user_selected = Auth::user()->id;
            $this->manager_select_disabled = 'true';
            $this->user_select_disabled = 'true';
        }
    }
}
