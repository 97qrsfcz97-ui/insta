<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $all_users = $this->user->withTrashed()->latest()->paginate(5);
        // withtrashed - include soft deleted records in query results
        return view('admin.users.index')->with('all_users', $all_users);
    }

    public function deactivate($id)
    {
        $this->user->destroy($id);
        return redirect()->back();
    }

    public function activate($id)
    {
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        // onlyTrashed - retrieves only soft deleted data/records
        // restore - this will un-delete soft deleted model. Will set deleted_at to null
        return redirect()->back();
    }
}
