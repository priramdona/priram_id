<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use FFI\Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Sale\Entities\SelforderBusiness;
use Modules\Sale\Entities\SelforderType;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $validator = ValidatorFacade::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required','string','min:6','max:16', 'unique:users'],
        ]);

        $phoneNumber = $data['phone_number'];
        $formattedPhone = PhoneHelper::formatToE164Indonesia($phoneNumber);

        $validator->after(function ($validator) use ($formattedPhone) {
            if (blank($formattedPhone)) {
                $validator->errors()->add('phone_number', 'Invalid phone number');
            }
            if (User::where('phone_number', $formattedPhone)->exists()) {
                $validator->errors()->add('phone_number', 'The phone number has already been taken.');
            }
        });

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $business = Business::create([
            'name' => $data['business_name'],
            'address' => $data['business_address'],
            'phone' => null,
            'prefix' => $this->generatePrefix($data['business_name']),
            'email' => null,
        ]);

        $phoneNumber = $data['phone_number'];
        $formattedPhone = PhoneHelper::formatToE164Indonesia($phoneNumber);

        SelforderBusiness::create([
            'selforder_type_id' => SelforderType::query()->where('code','mobileorder')->value('id'),
            'business_id' => $business->id,
            'subject' => 'Belanja Keliling dan Atur Mandiri!',
            'captions' => 'Silahkan Pindai Barcode dan Mulai berbelanja',
            'need_customers' => true,
            'need_customers' => true,

        ]);

        SelforderBusiness::create([
            'selforder_type_id' => SelforderType::query()->where('code','stayorder')->value('id'),
            'business_id' => $business->id,
            'subject' => 'Pesan ditempat sesuka anda!',
            'captions' => 'Anda tidak usah repot dapat langsung pesan ditempat.',
            'need_customers' => true,
            'need_customers' => true,

        ]);

        SelforderBusiness::create([
            'selforder_type_id' => SelforderType::query()->where('code','deliveryorder')->value('id'),
            'business_id' => $business->id,
            'subject' => 'Pesan sambil Rebahan? Bisa!!',
            'captions' => 'Pesan dimana saja.. dan akan kami antar...',
            'need_customers' => true,
            'need_customers' => true,

        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $formattedPhone,
            'password' => Hash::make($data['password']),
            'is_active' => 1,
            'business_id' => $business->id
        ]);

        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'business_id' => $business->id
        ]);

        $user->assignRole($superAdmin);

        return $user;
    }
    public function generatePrefix(string $name, ?string $excludeId = null): string
    {
        throw_if(blank($name), 'Tidak bisa membuat prefix jika nama bisnis kosong');

        $initial = $this->createPrefix($name);

        $counter = Business::withTrashed()
            ->where('prefix', 'like', $initial . "%")
            ->when($excludeId, fn ($query) => $query->whereNot('id', $excludeId))
            ->count() + 1;

        return $initial . $counter;
    }
    public function createPrefix(string $initialName): string
    {
        // try {
            $initialSplit = explode(' ', $initialName);
            $prefixInitial = '';

            foreach ($initialSplit as $w) {
                $prefixInitial .= strtoupper(mb_substr($w, 0, 1));
            }

            if (count($initialSplit) <= 1) {
                $prefixInitial = strtoupper(mb_substr($initialName, 0, 3));
            }

            if (count($initialSplit) <= 2 && count($initialSplit) > 1) {
                $prefixInitial = $prefixInitial.strtoupper(mb_substr($initialSplit[1], 1, 1));
            }
        // } catch (\Exception $exception) {
        //     Log::error($exception->getMessage());
        //     throw new FailedException(__('exception.prefixes.failed_show-'.'Failed generate prefixes-'.$exception->getMessage()));
        // }

        return mb_substr($prefixInitial, 0, 3);
    }
}
