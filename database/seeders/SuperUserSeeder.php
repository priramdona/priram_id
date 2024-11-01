<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach ($this->getDataBusiness() as $dataBusiness){
        $business = Business::create($dataBusiness);

        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@' . $business->prefix . '.com',
            'phone_number' => '+628131456904' . User::count('id') + 5,
            'password' => Hash::make(12345678),
            'is_active' => 1,
            'business_id' => $business->id
        ]);

        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'business_id' => $business->id
        ]);

        $user->assignRole($superAdmin);

        }


    }
    public function getDataBusiness(): array
    {
        return [
            [
            'name' => 'PT. Prima Raharja Mulia',
            'address' => 'Jl KH Ahmad Sanusi',
            'phone' => '6281314569045',
            'prefix' => $this->generatePrefix('PT. Prima Raharja Mulia')
        ]
        , [
            'name' => 'PT. Merdeka Santosa',
            'address' => 'Jl KH Ahmad Sanusi',
            'phone' => '6281314569045',
            'prefix' => $this->generatePrefix('PT Merdeka Santosa Abadi')
        ]
    ];
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
