<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Perbaikan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PerbaikanController extends Controller
{
    public function createPerbaikan(Request $request)
    {
        $request->validate([
            'perbaikan' => 'sometimes|string',
            'tanggal' => 'sometimes|string|max:255',
            'photo_perbaikan' => 'sometimes|image|mimes:png,jpeg,jpg,gif,svg|max:5048',
            'supply_id' => 'sometimes|exists:supplies,id',
            'user_id' => 'sometimes|exists:users,id',
            'keluhan_id' => 'sometimes|exists:keluhans,id',
            'rating' => '',
            'komentar' => '',
            'quantity' => '',
            'total' => ''
        ]);

        $perbaikan = Perbaikan::create([
            'perbaikan' => $request->perbaikan,
            'tanggal' => $request->tanggal,
            'photo_perbaikan' => $request->photo_perbaikan,
            'supply_id' => $request->supply_id,
            'user_id' => $request->user_id,
            'keluhan_id' => $request->keluhan_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'quantity' => $request->quantity,
            'total' => $request->total
        ]);

        $perbaikan = Perbaikan::with(['supply', 'user', 'keluhan'])->find($perbaikan->id);

        if($request->file('photo_perbaikan')->isValid()) {
            $photoPerbaikan = $request->file('photo_perbaikan');
            $extensionsPerbaikan = $photoPerbaikan->getClientOriginalExtension();
            $perbaikanUpload = "perbaikan/".date('YmdHis').".".$extensionsPerbaikan;
            $perbaikanPath = env('UPLOAD_PATH')."/perbaikan";
            $request->file('photo_perbaikan')->move($perbaikanPath, $perbaikanUpload);
            $perbaikan['photo_perbaikan'] = $perbaikanUpload;
        }

        try {
            $perbaikan->save();
            return ResponseFormmater::success(
                $perbaikan,
                'Data perbaikan berhasil ditambahkan'
            );
        }catch(\Exception $error) {
            return ResponseFormmater::error(
                null,
                $error->getMessage(),
                404
            );
        }
    }

    public function getPerbaikan(Request $request)
    {
        $id = $request->input('id');
        $user_id = $request->input('user_id');
        $supply_id = $request->input('supply_id');
        $keluhan_id = $request->input('keluhan_id');
        $tanggal = $request->input('tanggal');

        if($id) {

            $perbaikan = Perbaikan::with(['supply', 'user', 'keluhan'])->find($id);

            if($perbaikan) {
                return ResponseFormmater::success(
                    $perbaikan,
                    'Data Perbaikan berhasil diambil'
                );
            }
            else {
                return ResponseFormmater::error(
                    null,
                    'Data perbaikan gagal diambil',
                    404
                );
            }
        }

        $perbaikan = Perbaikan::with(['supply', 'user', 'keluhan']);

        if($user_id)
        {
            $perbaikan->where('user_id',$user_id);
        }

        if($supply_id)
        {
            $perbaikan->where('supply_id',$supply_id);
        }

        if($keluhan_id)
        {
            $perbaikan->where('keluhan_id',$keluhan_id);
        }

        if($tanggal) {
            $perbaikan->where('tanggal', 'like', '%' . $tanggal . '%');
        }

        return ResponseFormmater::success(
            $perbaikan->get(),
            'Data List Perbaikan berhasil diambil'
        );
    }

    public function updatePerbaikan (Request $request, $id)
    {
        $perbaikan = Perbaikan::with(['supply', 'user', 'keluhan'])->findOrFail($id);
        $data = $request->all();

        if($request->hasFile('photo_perbaikan')) {
            if($request->file('photo_perbaikan')->isValid()) {
                Storage::disk('upload')->delete($request->photo_perbaikan);
                $photoPerbaikan = $request->file('photo_perbaikan');
                $extensionsPerbaikan = $photoPerbaikan->getClientOriginalExtension();
                $perbaikanUpload = "perbaikan/".date('YmdHis').".".$extensionsPerbaikan;
                $perbaikanPath = env('UPLOAD_PATH')."/perbaikan";
                $request->file('photo_perbaikan')->move($perbaikanPath, $perbaikanUpload);
                $data['photo_perbaikan'] = $perbaikanUpload;
            }
        }

        $perbaikan->update($data);
        return ResponseFormmater::success(
            $perbaikan,
            'Data Perbaikan berhasil di update'
        );
    }

    public function deletePerbaikan (Request $request, $id)
    {
        $perbaikan = Perbaikan::with(['supply', 'user', 'keluhan'])->findOrFail($id);
        Storage::disk('upload')->delete($request->photo_perbaikan);
        $perbaikan->delete($request->all());

        return ResponseFormmater::success(
            $perbaikan,
            'Data Perbaikan Berhasil di delete'
        );
    }

    public function generatePerbaikanPdf(Request $request, $id)
    {
        $perbaikan = Perbaikan::with(['supply', 'user', 'keluhan'])->find($id);

        if($perbaikan) {

            
            $title = 'public/pdf/perbaikan/'.'perbaikan-'.strtotime('now').'.pdf';
            $qrcode = base64_encode(QrCode::format('png')->size(100)->errorCorrection('H')->generate(asset(Storage::url($title))));
            
            $images = [
                'qrcode'=>$qrcode,
                'logo'=> base64_encode(file_get_contents(public_path('assets/img/logo.png'))),
                'logo-rs'=> base64_encode(file_get_contents(public_path('assets/img/rs.png'))),
            ];
            // return view('pdf.perbaikan', compact(['title','perbaikan','images']));
            $pdf = Pdf::loadView('pdf.perbaikan', compact(['title','perbaikan','images']));
            
            Storage::put($title, $pdf->output());
            
            return ResponseFormmater::success(
                asset(Storage::url($title)),
                'Data Perbaikan berhasil dibuat'
            );
        }
        
        return ResponseFormmater::error(
            null,
            'Data perbaikan gagal diambil',
            404
        );
    }
}
