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
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
class ProfileController extends Controller
{

    public function edit() {
        return view('user::profile');
    }


    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore(auth()->id())
                    ->where('business_id', $request->user()->business_id)
                    ->whereNull('deleted_at')
            ],
            'phone_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'phone_number')
                    ->ignore(auth()->id())
                    ->where('business_id', $request->user()->business_id)
                    ->whereNull('deleted_at')
            ],
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


        if ($request->hasFile('image')) {

            if ($user->image) {
                $imagePath = 'images/' . $user->image; // Pastikan path sesuai dengan yang ada di storage
                // Hapus gambar jika ada
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $image = $request->file('image');
            $filename = $user->id . '.' . $image->getClientOriginalExtension(); // Nama file unik
            // $imagePath = $image->storeAs('products', $filename, 'public'); // Menyimpan file di public/products
            $request->image->move(public_path('images/users'), $filename);  // simpan ke folder 'images'

            $user->update([
                'image' => 'users/' . $filename, // Update path gambar
            ]);
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
