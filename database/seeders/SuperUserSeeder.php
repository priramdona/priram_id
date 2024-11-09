<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Sale\Entities\SelforderBusiness;
use Modules\Sale\Entities\SelforderType;
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


        SelforderType::create([
            'name' => 'Pesan Keliling (Mobile Self Order)',
            'description' => 'Fitur ini biasanya digunakan di Supermarket atau Toko. Sebelum memulai pelanggan Scan/Memindai QRCode yang sudah disiapkan untuk masuk ke Area Pesan Keliling (Mobile Self Order). Pelanggan melakukan pemesanan barang dengan mencari dan Scan barcode di area etalase produk (Usahakan Barcode jelas karena ini mengandalkan Kamera Pelanggan yang sudah di integrasikan pada Aplikasi Kasir), lalu pelanggan menyelesaikan metode pembayaran yang akan dipilih. tahap akhir Admin Checker akan melakukan pengecekan Barang dan Aplikasi Pelanggan sesuai Pemesanan yang sudah dilakukan pelanggan pada Aplikasi Kasir.',
            'code' => 'mobileorder',
            'status' => 1,

        ]);

        SelforderType::create([
            'name' => 'Pesan Ditempat (Stay Self Order)',
            'description' => 'Fitur ini biasanya digunakan untuk pelayanan Saji atau antar ke tempat yang Penjual sediakan. Sebelum memulai pelanggan harus Scan/Pindai QRCode yang sudah disiapkan dimana pemesanan diantarkan ke Pos-pos atau tempat yang disediakan Penjual. Pelanggan melakukan pemesanan sendiri ditempat lalu Admin checker memantau aktivitas pelanggan serta meneruskan untuk bisa memenuhi pesanan pelanggan. Dalam proses ini terdapat 4 langkah atau Status, Langkah 1 (Pesan) Yang artinya Pelanggan telah mengajukan Pesanan, Langkah 2 (Sedang Diproses) yang artinya Admin Checker sudah memproses pesanan Pelanggan, Langkah 3 (Selesai) yang artinya pesanan sudah selesai disampaikan. Pada saat proses sudah sampai di Langkah 3, Pelanggan bisa mengajukan (Komplain) di Langkah 4 yang nantinya akan kembali ke Langkah 2 (Proses).',
            'code' => 'stayorder',
            'status' => 1,
        ]);

        SelforderType::create([
            'name' => 'Pesan Kirim (Delivery Order)',
            'description' => 'Fitur ini biasanya untuk Pesan Antar dari Penjual ke Pelanggan. Sebelum memulai pelanggan harus mempunyai Link atau QRCode yang sudah disiapkan. Pelanggan melakukan Pesanan serta menyelesaikan Metode pembayaran dimana Pelanggan dapat memesan barang dengan masuk ke Area Pesan Kirim (Delivery Self Order).  Dalam proses ini terdapat 4 langkah atau Status, Langkah 1 (Pesan) Yang artinya Pelanggan telah mengajukan Pesanan, Langkah 2 (Sedang Diproses) yang artinya Admin Checker sudah memproses pesanan Pelanggan, Langkah 3 (Selesai) yang artinya pesanan sudah selesai disampaikan. Pada saat proses sudah sampai di Langkah 3, Pelanggan bisa mengajukan (Komplain) di Langkah 4 yang nantinya akan kembali ke Langkah 2 (Proses).',
            'code' => 'deliveryorder',
            'status' => 1,
        ]);

        foreach ($this->getDataBusiness() as $dataBusiness){
        $business = Business::create($dataBusiness);

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
            'phone' => '+6281314569045',
            'prefix' => $this->generatePrefix('PT. Prima Raharja Mulia'),
            'email' => 'emailbusiness@'.$this->generatePrefix('PT. Prima Raharja Mulia').'.com'
        ]
        , [
            'name' => 'PT. Merdeka Santosa',
            'address' => 'Jl KH Ahmad Sanusi',
            'phone' => '+6281314569045',
            'prefix' => $this->generatePrefix('PT Merdeka Santosa Abadi'),
            'email' => 'emailbusiness@'.$this->generatePrefix('PT Merdeka Santosa Abadi').'.com'
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
