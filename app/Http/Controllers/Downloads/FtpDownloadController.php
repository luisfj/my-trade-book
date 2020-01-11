<?php

namespace App\Http\Controllers\Downloads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;

class FtpDownloadController extends Controller
{
    public function historicoSincFtpMyFxBook(){
        $file = 'statement.htm';

        $fileName = basename($file);

         $ftp = Storage::createFtpDriver([
                        'host'     => 'ftp.myfxbook.com',
                        'username' => '3547363',
                        'password' => 'xpt467',
                        'port'     => '21', // your ftp port
                        'timeout'  => '30', // timeout setting
          ]);

           $filecontent = $ftp->get($file); // read file content

           // download file.
           return Response::make($filecontent, '200', array(
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="'.$fileName.'"'
            ));
    }
}
