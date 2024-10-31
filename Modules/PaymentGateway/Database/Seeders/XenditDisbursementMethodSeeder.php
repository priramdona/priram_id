<?php

namespace Modules\PaymentGateway\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PaymentGateway\Entities\XenditDisbursementChannel;
use Modules\PaymentGateway\Entities\XenditDisbursementMethod;

class XenditDisbursementMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emailData = ['munggi.priramdona@gmail.com','munggi.p@gmail.com','primaraharjamulia@gmail.com'];

        $dataDisMethods = [
            [
                'name' => 'Bank',
                'type' => 'BANK_ACCOUNT',
                'code' => 'BANK_ACCOUNT',
                'currency' => 'IDR',
                'receipt_notification' => true,
                'status' => true,
                'email_owner' =>  json_encode($emailData),
            ],
            [
                'name' => 'E-Wallet',
                'type' => 'MOBILE_NO',
                'code' => 'MOBILE_NO',
                'currency' => 'IDR',
                'receipt_notification' => true,
                'status' => true,
                'email_owner' => json_encode($emailData),
            ]
       ];

       foreach($dataDisMethods as $dataDisMethod){
        $resultDisMethods = XenditDisbursementMethod::create($dataDisMethod);

            if ($resultDisMethods['type'] == 'BANK_ACCOUNT') {

                $banksData = $this->dataBankSeeder($resultDisMethods->id);

                foreach($banksData as $bankData){
                    XenditDisbursementChannel::create($bankData);
                };
            }

            if ($resultDisMethods['type'] == 'MOBILE_NO') {

                $eWalletData = $this->dataEwalletSeeder($resultDisMethods->id);

                foreach($eWalletData as $eWallet){
                    XenditDisbursementChannel::create($eWallet);
                };
            }

       }



    }
    public function dataEwalletSeeder($idPaymentMethod): array
    {
        $dataEwallet = [
            ['xdm_id' => $idPaymentMethod,'name' => 'DANA','type' => 'E-Wallet','code' => 'ID_DANA', 'status' => true],
            ['xdm_id' => $idPaymentMethod,'name' => 'GoPay','type' => 'E-Wallet','code' => 'ID_GOPAY', 'status' => true],
            ['xdm_id' => $idPaymentMethod,'name' => 'LinkAja','type' => 'E-Wallet','code' => 'ID_LINKAJA', 'status' => true],
            ['xdm_id' => $idPaymentMethod,'name' => 'OVO','type' => 'E-Wallet','code' => 'ID_OVO', 'status' => true],
            ['xdm_id' => $idPaymentMethod,'name' => 'ShopeePay','type' => 'E-Wallet','code' => 'ID_SHOPEEPAY', 'status' => true],

            ];

            return $dataEwallet;
        }
public function dataBankSeeder($idPaymentMethod): array
{
    $dataBanks = [
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Aceh','type' => 'Bank','code' => 'ID_ACEH', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Aceh Syariah (UUS)','type' => 'Bank','code' => 'ID_ACEH_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Raya Indonesia (Bank BRI Agroniaga)','type' => 'Bank','code' => 'ID_AGRONIAGA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Aladin Syariah (Bank Maybank Syariah Indonesia)','type' => 'Bank','code' => 'ID_ALADIN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Allo Bank Indonesia (Bank Harda Internasional)','type' => 'Bank','code' => 'ID_ALLO', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Amar Indonesia (Anglomas International Bank)','type' => 'Bank','code' => 'ID_AMAR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank ANZ Indonesia','type' => 'Bank','code' => 'ID_ANZ', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Artha Graha International','type' => 'Bank','code' => 'ID_ARTHA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Bali','type' => 'Bank','code' => 'ID_BALI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank of America Merill-Lynch','type' => 'Bank','code' => 'ID_BAML', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Banten (Bank Pundi Indonesia)','type' => 'Bank','code' => 'ID_BANTEN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Central Asia (BCA)','type' => 'Bank','code' => 'ID_BCA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Central Asia Digital (BluBCA)','type' => 'Bank','code' => 'ID_BCA_DIGITAL', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Central Asia (BCA) Syariah','type' => 'Bank','code' => 'ID_BCA_SYR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Bengkulu','type' => 'Bank','code' => 'ID_BENGKULU', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Bisnis Internasional','type' => 'Bank','code' => 'ID_BISNIS_INTERNASIONAL', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank BJB','type' => 'Bank','code' => 'ID_BJB', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank BJB Syariah','type' => 'Bank','code' => 'ID_BJB_SYR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Neo Commerce (Bank Yudha Bhakti)','type' => 'Bank','code' => 'ID_BNC', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Negara Indonesia (BNI)','type' => 'Bank','code' => 'ID_BNI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank BNP Paribas','type' => 'Bank','code' => 'ID_BNP_PARIBAS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank of China (BOC)','type' => 'Bank','code' => 'ID_BOC', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Rakyat Indonesia (BRI)','type' => 'Bank','code' => 'ID_BRI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Syariah Indonesia (BSI)','type' => 'Bank','code' => 'ID_BSI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Tabungan Negara (BTN)','type' => 'Bank','code' => 'ID_BTN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Tabungan Negara Syariah (BTN UUS)','type' => 'Bank','code' => 'ID_BTN_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'BTPN Syariah (BTPN UUS and Bank Sahabat Purba Danarta)','type' => 'Bank','code' => 'ID_BTPN_SYARIAH', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Bukopin','type' => 'Bank','code' => 'ID_BUKOPIN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Syariah Bukopin','type' => 'Bank','code' => 'ID_BUKOPIN_SYR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Bumi Arta','type' => 'Bank','code' => 'ID_BUMI_ARTA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Capital Indonesia','type' => 'Bank','code' => 'ID_CAPITAL', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'China Construction Bank Indonesia (Bank Antar Daerah and Bank Windu Kentjana International)','type' => 'Bank','code' => 'ID_CCB', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Chinatrust Indonesia','type' => 'Bank','code' => 'ID_CHINATRUST', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank CIMB Niaga','type' => 'Bank','code' => 'ID_CIMB', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank CIMB Niaga Syariah (UUS)','type' => 'Bank','code' => 'ID_CIMB_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Citibank','type' => 'Bank','code' => 'ID_CITIBANK', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Commonwealth','type' => 'Bank','code' => 'ID_COMMONWEALTH', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Daerah Istimewa Yogyakarta (DIY)','type' => 'Bank','code' => 'ID_DAERAH_ISTIMEWA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Daerah Istimewa Yogyakarta Syariah (DIY UUS)','type' => 'Bank','code' => 'ID_DAERAH_ISTIMEWA_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Danamon','type' => 'Bank','code' => 'ID_DANAMON', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Danamon Syariah (UUS)','type' => 'Bank','code' => 'ID_DANAMON_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank DBS Indonesia','type' => 'Bank','code' => 'ID_DBS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Deutsche Bank','type' => 'Bank','code' => 'ID_DEUTSCHE', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Dinar Indonesia','type' => 'Bank','code' => 'ID_DINAR_INDONESIA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank DKI','type' => 'Bank','code' => 'ID_DKI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank DKI Syariah (UUS)','type' => 'Bank','code' => 'ID_DKI_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Fama International','type' => 'Bank','code' => 'ID_FAMA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Ganesha','type' => 'Bank','code' => 'ID_GANESHA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Hana','type' => 'Bank','code' => 'ID_HANA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'HSBC Indonesia (Bank Ekonomi Raharja)','type' => 'Bank','code' => 'ID_HSBC', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Hongkong and Shanghai Bank Corporation Syariah (HSBC UUS)','type' => 'Bank','code' => 'ID_HSBC_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank IBK Indonesia (Bank Agris)','type' => 'Bank','code' => 'ID_IBK', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank ICBC Indonesia','type' => 'Bank','code' => 'ID_ICBC', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Ina Perdania','type' => 'Bank','code' => 'ID_INA_PERDANA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Index Selindo','type' => 'Bank','code' => 'ID_INDEX_SELINDO', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank of India Indonesia','type' => 'Bank','code' => 'ID_INDIA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Jago (Bank Artos Indonesia)','type' => 'Bank','code' => 'ID_JAGO', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Jambi','type' => 'Bank','code' => 'ID_JAMBI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Jambi Syariah (UUS)','type' => 'Bank','code' => 'ID_JAMBI_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Jasa Jakarta','type' => 'Bank','code' => 'ID_JASA_JAKARTA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Jawa Tengah','type' => 'Bank','code' => 'ID_JAWA_TENGAH', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Jawa Tengah Syariah (UUS)','type' => 'Bank','code' => 'ID_JAWA_TENGAH_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Jawa Timur','type' => 'Bank','code' => 'ID_JAWA_TIMUR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Jawa Timur Syariah (UUS)','type' => 'Bank','code' => 'ID_JAWA_TIMUR_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'JP Morgan Chase Bank','type' => 'Bank','code' => 'ID_JPMORGAN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank JTrust Indonesia (Bank Mutiara)','type' => 'Bank','code' => 'ID_JTRUST', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Kalimantan Barat','type' => 'Bank','code' => 'ID_KALIMANTAN_BARAT', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Kalimantan Barat Syariah (UUS)','type' => 'Bank','code' => 'ID_KALIMANTAN_BARAT_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Kalimantan Selatan','type' => 'Bank','code' => 'ID_KALIMANTAN_SELATAN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Kalimantan Selatan Syariah (UUS)','type' => 'Bank','code' => 'ID_KALIMANTAN_SELATAN_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Kalimantan Tengah','type' => 'Bank','code' => 'ID_KALIMANTAN_TENGAH', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Kalimantan Timur','type' => 'Bank','code' => 'ID_KALIMANTAN_TIMUR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Kalimantan Timur Syariah (UUS)','type' => 'Bank','code' => 'ID_KALIMANTAN_TIMUR_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Lampung','type' => 'Bank','code' => 'ID_LAMPUNG', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Maluku','type' => 'Bank','code' => 'ID_MALUKU', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Mandiri','type' => 'Bank','code' => 'ID_MANDIRI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Mandiri Taspen Pos (Bank Sinar Harapan Bali)','type' => 'Bank','code' => 'ID_MANDIRI_TASPEN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Maspion Indonesia','type' => 'Bank','code' => 'ID_MASPION', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Mayapada International','type' => 'Bank','code' => 'ID_MAYAPADA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Maybank','type' => 'Bank','code' => 'ID_MAYBANK', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Mayora','type' => 'Bank','code' => 'ID_MAYORA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Mega','type' => 'Bank','code' => 'ID_MEGA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Syariah Mega','type' => 'Bank','code' => 'ID_MEGA_SYR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Mestika Dharma','type' => 'Bank','code' => 'ID_MESTIKA_DHARMA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Mizuho Indonesia','type' => 'Bank','code' => 'ID_MIZUHO', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank MNC Internasional','type' => 'Bank','code' => 'ID_MNC_INTERNASIONAL', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Muamalat Indonesia','type' => 'Bank','code' => 'ID_MUAMALAT', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Multi Arta Sentosa','type' => 'Bank','code' => 'ID_MULTI_ARTA_SENTOSA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Nationalnobu','type' => 'Bank','code' => 'ID_NATIONALNOBU', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Nusa Tenggara Barat','type' => 'Bank','code' => 'ID_NUSA_TENGGARA_BARAT', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Nusa Tenggara Timur','type' => 'Bank','code' => 'ID_NUSA_TENGGARA_TIMUR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank OCBC NISP','type' => 'Bank','code' => 'ID_OCBC', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank OCBC NISP Syariah (UUS)','type' => 'Bank','code' => 'ID_OCBC_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Oke Indonesia (Bank Andara)','type' => 'Bank','code' => 'ID_OKE', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Panin','type' => 'Bank','code' => 'ID_PANIN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Panin Syariah','type' => 'Bank','code' => 'ID_PANIN_SYR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Papua','type' => 'Bank','code' => 'ID_PAPUA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Permata','type' => 'Bank','code' => 'ID_PERMATA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Permata Syariah (UUS)','type' => 'Bank','code' => 'ID_PERMATA_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Prima Master Bank','type' => 'Bank','code' => 'ID_PRIMA_MASTER', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank QNB Indonesia (Bank QNB Kesawan)','type' => 'Bank','code' => 'ID_QNB_INDONESIA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Rabobank International Indonesia','type' => 'Bank','code' => 'ID_RABOBANK', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Resona Perdania','type' => 'Bank','code' => 'ID_RESONA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Riau Dan Kepri','type' => 'Bank','code' => 'ID_RIAU_DAN_KEPRI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Sahabat Sampoerna','type' => 'Bank','code' => 'ID_SAHABAT_SAMPOERNA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank SBI Indonesia','type' => 'Bank','code' => 'ID_SBI_INDONESIA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Seabank Indonesia (Bank Kesejahteraan Ekonomi)','type' => 'Bank','code' => 'ID_SEABANK', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Shinhan Indonesia (Bank Metro Express)','type' => 'Bank','code' => 'ID_SHINHAN', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Sinarmas','type' => 'Bank','code' => 'ID_SINARMAS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Sinarmas Syariah (UUS)','type' => 'Bank','code' => 'ID_SINARMAS_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Standard Charted Bank','type' => 'Bank','code' => 'ID_STANDARD_CHARTERED', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sulawesi Tengah','type' => 'Bank','code' => 'ID_SULAWESI', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sulawesi Tenggara','type' => 'Bank','code' => 'ID_SULAWESI_TENGGARA', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sulselbar','type' => 'Bank','code' => 'ID_SULSELBAR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sulselbar Syariah (UUS)','type' => 'Bank','code' => 'ID_SULSELBAR_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sulut','type' => 'Bank','code' => 'ID_SULUT', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sumatera Barat','type' => 'Bank','code' => 'ID_SUMATERA_BARAT', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sumatera Barat Syariah (UUS)','type' => 'Bank','code' => 'ID_SUMATERA_BARAT_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sumsel Dan Babel','type' => 'Bank','code' => 'ID_SUMSEL_DAN_BABEL', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sumsel Dan Babel Syariah (UUS)','type' => 'Bank','code' => 'ID_SUMSEL_DAN_BABEL_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sumut','type' => 'Bank','code' => 'ID_SUMUT', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Pembangunan Daerah Sumut Syariah (UUS)','type' => 'Bank','code' => 'ID_SUMUT_UUS', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Tabungan Pensiunan Nasional (BTPN)','type' => 'Bank','code' => 'ID_TABUNGAN_PENSIUNAN_NASIONAL', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank of Tokyo Mitsubishi UFJ (MUFJ)','type' => 'Bank','code' => 'ID_TOKYO', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank UOB Indonesia','type' => 'Bank','code' => 'ID_UOB', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Victoria Internasional','type' => 'Bank','code' => 'ID_VICTORIA_INTERNASIONAL', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Victoria Syariah','type' => 'Bank','code' => 'ID_VICTORIA_SYR', 'status' => true],
        ['xdm_id' => $idPaymentMethod,'name' => 'Bank Woori Indonesia','type' => 'Bank','code' => 'ID_WOORI', 'status' => true],
        ];

        return $dataBanks;
    }
}
