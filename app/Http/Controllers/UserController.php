<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.manage_user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class.',username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'boolean'],
        ]);


        $user = new User();
        $user->username = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->admin = $request->role;
        $user->save();

        event(new Registered($user)); // e.g. for email verification, see EventServiceProvider

        return redirect(route('admin'))->with('success', __('admin.user_add_success', ['name' => $request->name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.manage_user', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // cast enable_desc to boolean (check if it exists)
        $request->merge([
            'visibility' => $request->has('visibility'),
        ]);


        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class.',username,'.$id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'boolean'],
            'visibility' => ['required', 'boolean'],
        ]);

        $user = User::findOrFail($id);

        // cache
        $username = $user->username;

        $wasChanged = false;

        // admins can edit all non s_admins, s_admins can only be edited by s_admins
        if (($user->s_admin && $request->user()->s_admin) || !$user->s_admin) {

            if ($username !== $request->name) {
                $user->username = $request->name;
                $wasChanged = true;
            }
            if ($user->email !== $request->email) {
                $user->email = $request->email;
                $wasChanged = true;
            }
            if ($request->password !== null) {
                $user->password = Hash::make($request->password);
                $wasChanged = true;
            }
            if ($user->admin != $request->role) {

                // check if user is last admin
                if ($user->admin && User::where('admin', true)->count() === 1) {
                    return redirect(route('admin'))->with('error', __('admin.user_last_admin', ['name' => $username]));
                }

                $user->admin = $request->role;
                $wasChanged = true;
            }
        }

        if ($user->display_enabled != $request->visibility) {
            $user->display_enabled = $request->visibility;
            $wasChanged = true;
        }

        if ($wasChanged) {
            $user->save();
            return redirect(route('admin'))->with('success',  __('admin.user_update_success', ['name' => $user->username]));
        }
        else {
            return redirect(route('admin'))->with('info',  __('admin.user_no_change', ['name' => $username]));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $userName = $user->username;

        // check if user is last admin
        if ($user->admin && User::where('admin', true)->count() === 1) {
            return redirect(route('admin'))->with('error', __('admin.user_last_admin', ['name' => $userName]));
        }

        // check if user is s_admin
        if ($user->s_admin && !auth()->user()->s_admin) {
            return redirect(route('admin'))->with('error', __('admin.user_super_admin_delete'));
        }

        // handle current user deletion
        if (Auth::id() === $user->id) {
            Auth::logout();
        }

        $user->delete();

        return redirect(route('admin'))->with('success',  __('admin.user_delete_success', ['name' => $userName]));
    }
}
