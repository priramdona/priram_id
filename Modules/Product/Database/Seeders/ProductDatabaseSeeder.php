<?php

namespace Modules\Product\Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Setting\Entities\Unit;
use Illuminate\Support\Facades\Storage;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $barcodeGenerator = new ProductController();

        foreach (Business::get() as $business){
            $categoryMaxId = Category::where('business_id',$business->id)->count('id') + 1;

            $categoryMaxId += $categoryMaxId + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'Obat',
                'business_id' => $business->id,
            ]);

            Unit::create([
                'name' => 'Satuan',
                'short_name' => 'Satuan',
                'operator' => '*',
                'operation_value' => 1,
                'business_id' => $business->id,
                'is_default' => true,
            ]);

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Paramex',
                'image' => 'seeder_images/paramex.jpg',
                'product_code' => $barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '10500',
                'product_price' => '12000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '1',
                'product_note' => 'Data Seeder',
                'business_id' => $business->id,
            ]);
             // $imagePath = public_path('images/seeder_images/paramex.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $categoryMaxId += $categoryMaxId + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'BASIC T-SHIRT',
                'business_id' => $business->id,
            ]);

            // $barcode = $barcodeGenerator->generateUniqueBarcode();

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness T-shirt New Olive',
                'product_code' => $barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '170000',
                'product_price' => '175000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Vneck CoolTech
                            - Berbahan 100% Cotton Combed
                            - Ketebalan (30S) 150 Gsm
                            - Permukaan Kain Yang Sangat Minim Bulu (Biowash)
                            - Treatment CoolTech Mampu Menjaga Tubuh Tetap Dingin Di Cuaca Panas
                            - Unisex Bisa Dipakai Pria / Wanita
                            - Produk Lokal Dengan Kualitas International',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/1.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness CoolTech Coca Mocha',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '80000',
                'product_price' => '86000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Basic Short Sleeve T-Shirt
                                - Berbahan 100% Cotton Combed
                                - Ketebalan (30S) 150 GSM
                                - Permukaan Kain Yang Sangat Minim Bulu (Biowash)
                                - Model Potongan REGULAR
                                - Unisex Bisa Dipakai Pria / Wanita
                                - Produk Lokal Dengan Kualitas International
                                - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/2.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Otter OS T-Shirt White HG',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '120000',
                'product_price' => '130000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Oversize T-Shirt
                    - Berbahan 68% Micro Fiber Polyester & 32% Cotton
                    - Ketebalan 290 Gsm
                    - Karakter Kain Permukaannya Lembut dan Tebal (Lebih Tebal Dari Heavy Cotton)
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/3.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Otter OS T-Shirt Steel Blue',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '120000',
                'product_price' => '130000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Oversize T-Shirt
                    - Berbahan 68% Micro Fiber Polyester & 32% Cotton
                    - Ketebalan 290 Gsm
                    - Karakter Kain Permukaannya Lembut dan Tebal (Lebih Tebal Dari Heavy Cotton)
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/4.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Otter OS T-Shirt Red',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '120000',
                'product_price' => '130000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Oversize T-Shirt
                    - Berbahan 68% Micro Fiber Polyester & 32% Cotton
                    - Ketebalan 290 Gsm
                    - Karakter Kain Permukaannya Lembut dan Tebal (Lebih Tebal Dari Heavy Cotton)
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/5.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Otter OS T-Shirt Black',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '120000',
                'product_price' => '130000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Oversize T-Shirt
                    - Berbahan 68% Micro Fiber Polyester & 32% Cotton
                    - Ketebalan 290 Gsm
                    - Karakter Kain Permukaannya Lembut dan Tebal (Lebih Tebal Dari Heavy Cotton)
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/6.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $categoryMaxId += $categoryMaxId + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'POLO SLIM',
                'business_id' => $business->id,
            ]);

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Polo Slim Olive',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '130000',
                'product_price' => '140000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Polo Shirt
                            - Berbahan Pique 55% Cotton & 45% Polyester
                            - Ketebalan 160 Gsm
                            - Corak Rajutan Yang Khas Pada Permukaan Kainnya
                            - Model Potongan Regular
                            - Unisex Bisa Dipakai Pria / Wanita
                            - Produk Lokal Dengan Kualitas International
                            - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/7.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Polo Slim Black',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '130000',
                'product_price' => '140000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Polo Shirt
                            - Berbahan Pique 55% Cotton & 45% Polyester
                            - Ketebalan 160 Gsm
                            - Corak Rajutan Yang Khas Pada Permukaan Kainnya
                            - Model Potongan Regular
                            - Unisex Bisa Dipakai Pria / Wanita
                            - Produk Lokal Dengan Kualitas International
                            - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/8.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Polo Slim Morning Green',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '130000',
                'product_price' => '140000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Polo Shirt
                            - Berbahan Pique 55% Cotton & 45% Polyester
                            - Ketebalan 160 Gsm
                            - Corak Rajutan Yang Khas Pada Permukaan Kainnya
                            - Model Potongan Regular
                            - Unisex Bisa Dipakai Pria / Wanita
                            - Produk Lokal Dengan Kualitas International
                            - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/9.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $categoryMaxId += $categoryMaxId + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'RUNNER SWEATER',
                'business_id' => $business->id,
            ]);


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Runner Sweater Olive',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '180000',
                'product_price' => '189000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Runner Sweater
                            - Berbahan 100% French Terry
                            - Ketebalan 345 Gsm
                            - Struktur Bagian Dalam Berbentuk Kumpulan "Loop"
                            - Model Potongan Regular
                            - Unisex Bisa Dipakai Pria / Wanita
                            - Produk Lokal Dengan Kualitas International
                            - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/10.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Runner Sweater Navy',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '180000',
                'product_price' => '189000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Runner Sweater
                            - Berbahan 100% French Terry
                            - Ketebalan 345 Gsm
                            - Struktur Bagian Dalam Berbentuk Kumpulan "Loop"
                            - Model Potongan Regular
                            - Unisex Bisa Dipakai Pria / Wanita
                            - Produk Lokal Dengan Kualitas International
                            - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/11.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Runner Sweater MIsty 68',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '180000',
                'product_price' => '189000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Runner Sweater
                            - Berbahan 100% French Terry
                            - Ketebalan 345 Gsm
                            - Struktur Bagian Dalam Berbentuk Kumpulan "Loop"
                            - Model Potongan Regular
                            - Unisex Bisa Dipakai Pria / Wanita
                            - Produk Lokal Dengan Kualitas International
                            - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/12.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Runner Sweater MIsty 71',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '180000',
                'product_price' => '189000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Runner Sweater
                            - Berbahan 100% French Terry
                            - Ketebalan 345 Gsm
                            - Struktur Bagian Dalam Berbentuk Kumpulan "Loop"
                            - Model Potongan Regular
                            - Unisex Bisa Dipakai Pria / Wanita
                            - Produk Lokal Dengan Kualitas International
                            - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/13.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $categoryMaxId += $categoryMaxId + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'BOXING HOOD',
                'business_id' => $business->id,
            ]);


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Boxing Hood Maroon',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '220000',
                'product_price' => '225000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Boxing Hood Sweater
                        - Berbahan 100% Cotton Fleece
                        - Ketebalan 280 Gsm
                        - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                        - Model Potongan Regular
                        - Unisex Bisa Dipakai Pria / Wanita
                        - Produk Lokal Dengan Kualitas International
                        - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/14.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Boxing Hood Misty',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '220000',
                'product_price' => '225000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Boxing Hood Sweater
                        - Berbahan 100% Cotton Fleece
                        - Ketebalan 280 Gsm
                        - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                        - Model Potongan Regular
                        - Unisex Bisa Dipakai Pria / Wanita
                        - Produk Lokal Dengan Kualitas International
                        - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/15.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Boxing Hood D Jon',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '220000',
                'product_price' => '225000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Boxing Hood Sweater
                        - Berbahan 100% Cotton Fleece
                        - Ketebalan 280 Gsm
                        - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                        - Model Potongan Regular
                        - Unisex Bisa Dipakai Pria / Wanita
                        - Produk Lokal Dengan Kualitas International
                        - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/16.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Boxing Hood Black',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '375000',
                'product_price' => '415000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Boxing Hood Sweater
                        - Berbahan 100% Cotton Fleece
                        - Ketebalan 280 Gsm
                        - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                        - Model Potongan Regular
                        - Unisex Bisa Dipakai Pria / Wanita
                        - Produk Lokal Dengan Kualitas International
                        - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/17.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $categoryMaxId += $categoryMaxId + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'HOODIE ZIPPER',
                'business_id' => $business->id,
            ]);

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Hoodie zipper Olive',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '275000',
                'product_price' => '315000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Zipper Hoodie Sweater
                    - Berbahan 100% Cotton Fleece
                    - Ketebalan 280 GSM
                    - Menggunakan Resleting YKK
                    - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                    - Model Potongan REGULAR
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal dengan Kualitas International',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/18.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Hoodie zipper Red',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '275000',
                'product_price' => '315000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Zipper Hoodie Sweater
                    - Berbahan 100% Cotton Fleece
                    - Ketebalan 280 GSM
                    - Menggunakan Resleting YKK
                    - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                    - Model Potongan REGULAR
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal dengan Kualitas International',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/19.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');



            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Hoodie zipper Beige',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '275000',
                'product_price' => '315000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Zipper Hoodie Sweater
                    - Berbahan 100% Cotton Fleece
                    - Ketebalan 280 GSM
                    - Menggunakan Resleting YKK
                    - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                    - Model Potongan REGULAR
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal dengan Kualitas International',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/20.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Hoodie zipper Rotten Yellow',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '275000',
                'product_price' => '315000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Zipper Hoodie Sweater
                    - Berbahan 100% Cotton Fleece
                    - Ketebalan 280 GSM
                    - Menggunakan Resleting YKK
                    - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                    - Model Potongan REGULAR
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal dengan Kualitas International',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/21.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Hoodie Camouflage zipperper M90 Camo',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '275000',
                'product_price' => '315000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Zipper Hoodie Sweater
                    - Berbahan 100% Cotton Fleece
                    - Ketebalan 280 GSM
                    - Menggunakan Resleting YKK
                    - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                    - Model Potongan REGULAR
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal dengan Kualitas International',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/22.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Hoodie Zipper Light Pink',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '275000',
                'product_price' => '315000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Zipper Hoodie Sweater
                    - Berbahan 100% Cotton Fleece
                    - Ketebalan 280 GSM
                    - Menggunakan Resleting YKK
                    - Permukaan Bagian Dalam Terdapat Bulu Halus Dan Tebal
                    - Model Potongan REGULAR
                    - Unisex Bisa Dipakai Pria / Wanita
                    - Produk Lokal dengan Kualitas International',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/23.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');



            $categoryMaxId += $categoryMaxId + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'DIAMOND',
                'business_id' => $business->id,
            ]);

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Diamond Olive',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '240000',
                'product_price' => '250000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Diamond Zipper
                    - Berbahan Quilt (Wajik) 100% Cotton
                    - Ketebalan 300 Gsm
                    - Permukaan Kain Yang Memiliki Motif dan Tekstur Khas Seperti Wajik
                    - Unisex Bisa Dipakai Pria Dan Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/24.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Diamond Navy',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '240000',
                'product_price' => '250000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Diamond Zipper
                    - Berbahan Quilt (Wajik) 100% Cotton
                    - Ketebalan 300 Gsm
                    - Permukaan Kain Yang Memiliki Motif dan Tekstur Khas Seperti Wajik
                    - Unisex Bisa Dipakai Pria Dan Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/25.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Diamond Dark Grey',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '240000',
                'product_price' => '250000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Diamond Zipper
                    - Berbahan Quilt (Wajik) 100% Cotton
                    - Ketebalan 300 Gsm
                    - Permukaan Kain Yang Memiliki Motif dan Tekstur Khas Seperti Wajik
                    - Unisex Bisa Dipakai Pria Dan Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/26.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Diamond Classic Blue',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '240000',
                'product_price' => '250000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Diamond Zipper
                    - Berbahan Quilt (Wajik) 100% Cotton
                    - Ketebalan 300 Gsm
                    - Permukaan Kain Yang Memiliki Motif dan Tekstur Khas Seperti Wajik
                    - Unisex Bisa Dipakai Pria Dan Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/27.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Diamond Black',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '240000',
                'product_price' => '250000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Diamond Zipper
                    - Berbahan Quilt (Wajik) 100% Cotton
                    - Ketebalan 300 Gsm
                    - Permukaan Kain Yang Memiliki Motif dan Tekstur Khas Seperti Wajik
                    - Unisex Bisa Dipakai Pria Dan Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/28.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Diamond Beige',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '240000',
                'product_price' => '250000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Diamond Zipper
                    - Berbahan Quilt (Wajik) 100% Cotton
                    - Ketebalan 300 Gsm
                    - Permukaan Kain Yang Memiliki Motif dan Tekstur Khas Seperti Wajik
                    - Unisex Bisa Dipakai Pria Dan Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/29.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Human Greatness Diamond Coca Mocha',
                'product_code' =>$barcodeGenerator->generateBarcode(),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '240000',
                'product_price' => '250000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Diamond Zipper
                    - Berbahan Quilt (Wajik) 100% Cotton
                    - Ketebalan 300 Gsm
                    - Permukaan Kain Yang Memiliki Motif dan Tekstur Khas Seperti Wajik
                    - Unisex Bisa Dipakai Pria Dan Wanita
                    - Produk Lokal Dengan Kualitas International
                    - Dibuat Di Indonesia',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/30.jpg'); // Path absolut

            //  $product->addMedia($imagePath)
            //->preservingOriginal()
           // ->toMediaCollection('images');


            $categoryMaxId += $categoryMaxId + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'Pembasmi Nyamuk',
                'business_id' => $business->id,
            ]);

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => 'Baygon spray orchid greentea 600ml & floral Garden 600+75ml',
                'product_code' => '8998899996080',
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '30000',
                'product_price' => '38000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '10',
                'product_note' => 'Jangkauan lebih luas
                        - Nyamuk mati dalam 3 detik
                        - Anti nyamuk, lalat dan kecoa
                        - Wangi flower garden

                        16 JAM perlindungan',
                'business_id' => $business->id,
            ]);

             // $imagePath = public_path('images/seeder_images/31.jpg'); // Path absolut


            // $product->addMedia()
            //      ->usingFileName('otherFileName1.jpg')
            //// ->toMediaCollection('images');

            // $defaultPulsa = Category::create([
            //     'category_code' => $business->prefix . str_pad($categoryMaxId + 1, 4, '0', STR_PAD_LEFT),
            //     'category_name' => 'Pulsa',
            //     'business_id' => $business->id,
            //     'is_showlist' => false,
            //     'is_default' => true,
            // ]);

            // Product::create([
            //     'category_id' => $defaultPulsa->id,
            //     'product_name' => 'Simpati 10Rb',
            //     'product_code' => str_pad(mt_rand(1,999999999999),12,'0',STR_PAD_LEFT),
            //     'product_barcode_symbology' => 'EAN13',
            //     'product_quantity' => '99999',
            //     'product_cost' => '10500',
            //     'product_price' => '12000',
            //     'product_unit' => 'Satuan',
            //     'product_stock_alert' => '1',
            //     'product_note' => 'Data Seeder',
            //     'business_id' => $business->id,
            //     'is_showlist' => false,
            //     'is_default' => true,
            //     'is_action' => true,
            //     'action' => 'phone_number',
            // ]);
        }

    }

}
