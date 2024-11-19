<?php

namespace Modules\User\Http\Controllers;

use App\Helpers\PhoneHelper;
use Modules\User\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Upload\Entities\Upload;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class UsersController extends Controller
{
    public function index(UsersDataTable $dataTable) {
        abort_if(Gate::denies('access_user_management'), 403);

        return $dataTable->render('user::users.index');
    }


    public function create() {
        abort_if(Gate::denies('access_user_management'), 403);

        return view('user::users.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('access_user_management'), 403);

        // $request->validate([
        //     'name'     => 'required|string|max:255',
        //     'email'    => 'required|email|max:255|unique:users,email',
        //     'password' => 'required|string|min:8|max:255|confirmed'
        // ]);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'min:6', 'max:16', 'unique:users,phone_number'],
            'password' => 'required|string|min:8|max:255|confirmed'
        ]);

        $phoneNumber = $request['phone_number'];
        $formattedPhone = PhoneHelper::formatToE164Indonesia($phoneNumber);

        $validator->after(function ($validator) use ($formattedPhone) {
            if (blank($formattedPhone)) {
                $validator->errors()->add('phone_number', 'Invalid phone number');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone_number'    => $formattedPhone,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active,
            'business_id' => Auth::user()->business_id
        ]);

        $user->assignRole($request->role);

        if ($request->has('image')) {
            $tempFile = Upload::where('folder', $request->image)->first();

            if ($tempFile) {
                $user->addMedia(Storage::path('temp/' . $request->image . '/' . $tempFile->filename))->toMediaCollection('avatars');

                Storage::deleteDirectory('public/temp/' . $request->image);
                $tempFile->delete();
            }
        }

        toast(__('controller.created'), 'success');

        return redirect()->route('users.index');
    }


    public function edit(User $user) {
        abort_if(Gate::denies('access_user_management'), 403);

        return view('user::users.edit', compact('user'));
    }


    public function update(Request $request, User $user) {
        abort_if(Gate::denies('access_user_management'), 403);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'is_active' => $request->is_active
        ]);

        $user->syncRoles($request->role);

        if ($request->has('image')) {
            $tempFile = Upload::where('folder', $request->image)->first();

            if ($user->getFirstMedia('avatars')) {
                $user->getFirstMedia('avatars')->delete();
            }

            if ($tempFile) {
                $user->addMedia(Storage::path('temp/' . $request->image . '/' . $tempFile->filename))->toMediaCollection('avatars');

                Storage::deleteDirectory('public/temp/' . $request->image);
                $tempFile->delete();
            }
        }

        toast(__('controller.updated'), 'info');

        return redirect()->route('users.index');
    }


    public function destroy(User $user) {
        abort_if(Gate::denies('access_user_management'), 403);

        $user->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('users.index');
    }
}
