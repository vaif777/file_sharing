<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DownloadController extends Controller
{
    public function getFile(Request $request, $id)
    {
        $file = File::query()->find($id);
        if (!$file) abort(404);

        if (Auth::user()->role->tag != 'administrator')
            if ($file->user_id != Auth::user()->id && !count($file->users->where('id', Auth::user()->id)) && !($file->access->tag == 'all' || $file->access->tag == 'authorized')) abort(404);
        
        if ($file->user_id != Auth::user()->id) {
            if ($file->pin){
               if ($this->pinCodeVerification($file, $request->pin)){
                    return $this->pinCodeVerification($file, $request->pin);
                }
            }    
        }

        $this->fileDownload('storage/' . $file->href, $file->original_name);
    }

     public function getSharedAccessFile(Request $request, $id)
    {
        $file = File::query()->find($id);
        if (!$file) abort(404);
        if ($file->access->tag != 'all') abort(404);

        $userId = isset(Auth::user()->id) ? Auth::user()->id : false;
        if ($file->user_id != $userId) {
            if ($file->pin){
                if ($this->pinCodeVerification($file, $request->pin)){
                    return $this->pinCodeVerification($file, $request->pin);
                }
            }  
        }

        $this->fileDownload('storage/' . $file->href, $file->original_name);
    }
    public function pinCodeVerification($file, $pin)
    {
        $pins = $file->pins->where('pin', $pin)->first();
          
        if ($pins != null) {
            if (!$pins->reusable) $pins->delete();    
            $pins = $file->pins->where('pin', '<>', $pin);

            if(!count($pins)){
                $file->update([
                    'access_id' => 1,
                    'pin' => 0,
                ]);
                DB::table('file_user')->where('file_id', $file->id)->delete();
            }   
        } else {
            
            $userRole = isset(Auth::user()->role->tag) ? Auth::user()->role->tag : false;
            
            if ($file->access->tag == 'all') {
                $page = 'file.sharedAccess';
            } else {
                $page = 'file';
            }
            
            return redirect()->route($page, ['file' => $file->id])->with('danger', 'Неверный пин код');
        }
    }

    public function fileDownload($file, $originalName)
    {
        if (ob_get_contents()) ob_end_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $originalName);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
