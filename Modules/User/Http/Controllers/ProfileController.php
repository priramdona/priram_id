<?php

namespace Modules\User\Http\Controllers;

use App\Helpers\PhoneHelper;
use App\Models\Business;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Upload\Entities\Upload;
use Modules\User\Rules\MatchCurrentPassword;
use Illuminate\Support\Facades\Validator;
use Modules\Currency\Entities\Currency;
use Modules\Sale\Entities\SelforderBusiness;
use Modules\Setting\Entities\Setting;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
class ProfileController extends Controller
{

    public function edit() {
        return view('user::profile');
    }


    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone_number' => ['required', 'string', 'min:6', 'max:16', 'unique:users,phone_number,' . auth()->id()],
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

        $user = auth()->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $formattedPhone,
        ]);

        // dd($request->all()); // Akan menampilkan detail file

        if ($request->has('image')) {
            $tempFile = Upload::where('folder', $request->image)->first();

            if ($tempFile) {
                if ($user->getFirstMedia('avatars')) {
                    $user->getFirstMedia('avatars')->delete();
                }

                $user->addMedia(Storage::path('temp/' . $request->image . '/' . $tempFile->filename))
                     ->toMediaCollection('avatars');

                Storage::deleteDirectory('temp/' . $request->image);
                $tempFile->delete();
            }
        }

        toast(__('controller.updated'), 'success');

        return back();
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'current_password'  => ['required', 'max:255', new MatchCurrentPassword()],
            'password' => 'required|min:8|max:255|confirmed'
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        toast(__('controller.updated'), 'success');

        return back();
    }

    public function delete(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                'password_delete' => ['required', 'max:255', new MatchCurrentPassword()],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'password_invalid',
                ]);
            }else{

                $roles = Auth::user()->roles;
                $isOwner = false;
                // Tampilkan nama role
                foreach ($roles as $role) {
                    if ($role->name == "Super Admin"){
                        $isOwner = true;
                    }
                }
                if ($isOwner){
                    Business::query()
                    ->where('id', Auth::user()->business_id)
                    ->delete();

                    Currency::query()
                    ->where('business_id', Auth::user()->business_id)
                    ->delete();

                    Setting::query()
                    ->where('business_id', Auth::user()->business_id)
                    ->delete();

                    SelforderBusiness::query()
                    ->where('business_id', Auth::user()->business_id)
                    ->delete();

                    Role::query()
                    ->where('business_id', Auth::user()->business_id)
                    ->delete();

                    User::query()
                    ->where('business_id', Auth::user()->business_id)
                    ->delete();
                }else{
                    User::query()
                    ->where('id', Auth::user()->id)
                    ->delete();
                }

                return response()->json([
                    'status' => 'success',
                ]);
            }
         } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
            ]);
         }


    }
}
