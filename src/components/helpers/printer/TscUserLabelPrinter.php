<?php

namespace sheillendra\jeasyui\components\helpers\printer;

use Yii;

class TscUserLabelPrinter
{

    /**
     * @param \backend\models\User $user 
     */
    public function twoColumn($user)
    {
        

        $TSCObj = new \COM("TSCSDK.driver");
        $TSCObj->openport($_ENV['LABEL_PRINTER_NAME']);

        $TSCObj->sendcommand("SIZE 100 mm, 30 mm");
        $TSCObj->sendcommand("GAP 3 mm, 0 mm");
        $TSCObj->sendcommand("DIRECTION 0");
        $TSCObj->sendcommand("REFERENCE 0,0");
        $TSCObj->sendcommand("CLS");

        // Ukuran dalam dot (1 mm = 8 dots)
        function mm($mm)
        {
            return intval($mm * 8);
        }

        // ---------------- LABEL KIRI ----------------
        $xL = mm(0);   // margin kiri
        $yStart = mm(0); // turun sedikit

        $TSCObj->sendcommand("TEXT " . ($xL + mm(3)) . "," . ($yStart + mm(4)) . ",\"2\",0,1,1,\"{$user->username}\"");
        $TSCObj->sendcommand("BARCODE " . ($xL + mm(3)) . "," . ($yStart + mm(8)) . ",\"128\",30,0,0,2,4,\"" . $user->auth_key . "\"");

        // // ---------------- LABEL KANAN ----------------
        // $xR = mm(53); // mulai kanan
        // $TSCObj->sendcommand("TEXT " . ($xR + mm(3)) . "," . ($yStart + mm(4)) . ",\"2\",0,1,1,\"{$user->username}\"");
        // $TSCObj->sendcommand("BARCODE " . ($xR + mm(3)) . "," . ($yStart + mm(8)) . ",\"128\",80,1,0,2,4,\"" . $user->password_hash . "\"");
        
        $TSCObj->sendcommand("PRINT 1, 1");
        $TSCObj->closeport();

        
    }
}
