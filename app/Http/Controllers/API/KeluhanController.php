<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KeluhanController extends Controller
{
    public function createKeluhan(Request $request)
    {
        $request->validate([
            'room_id' => 'sometimes|exists:rooms,id',
            'tanggal' => 'sometimes|string|max:255',
            'kendala' => 'sometimes|string',
            'photo_kendala' => 'sometimes|image|mimes:png,jpeg,jpg,gif,svg|max:5048',
            'status' => '',
            'user_id' => 'sometimes|exists:users,id',
            'name_teknisi' => 'sometimes|nullable|exists:perbaikans,id'
        ]);

        $keluhan = Keluhan::create([
            'room_id' => $request->room_id,
            'tanggal' => $request->tanggal,
            'kendala' => $request->kendala,
            'photo_kendala' => $request->photo_kendala,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'name_teknisi' => $request->name_teknisi
        ]);


        $keluhan = Keluhan::with(['room', 'user'])->find($keluhan->id);

        if($request->file('photo_kendala')->isValid()) {
            $photoKendala = $request->file('photo_kendala');
            $extensionsKendala = $photoKendala->getClientOriginalExtension();
            $kendalaUpload = "kendala/".date('YmdHis').".".$extensionsKendala;
            $kendalaPath = env('UPLOAD_PATH')."/kendala";
            $request->file('photo_kendala')->move($kendalaPath, $kendalaUpload);
            $keluhan['photo_kendala'] = $kendalaUpload;
        }

        try {
            $keluhan->save();
            return ResponseFormmater::success(
                $keluhan,
                'Keluhan Berhasil di buat'
            );
        }
        catch(\Exception $error) {
            return ResponseFormmater::error(
                null,
                $error->getMessage(),
                'Keluhan gagal di buat'
            );
        }
    }

    public function getKeluhan(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $name_teknisi = $request->input('name_teknisi');

        if($id) {
            $keluhan = Keluhan::with(['room', 'user'])->find($id);

            if($keluhan) {
                return ResponseFormmater::success(
                    $keluhan,
                    'Data Keluhan berhasil diambil'
                );
            }
            else {
                return ResponseFormmater::error(
                    null,
                    'Data Keluhan gagal diambil',
                    404
                );
            }
        }

        $keluhan = Keluhan::with(['room', 'user']);

        if($status) {

            $keluhan->where('status', 'like', '%' . $status . '%');
        }

        if($name_teknisi) {

            $keluhan->where('name_teknisi', 'like', '%' . $name_teknisi . '%');
        }

        return ResponseFormmater::success(
            $keluhan->get(),
            'Data List keluhan berhasil diambil'
        );
    }

    public function updateKeluhan(Request $request, $id) {
        $keluhan = Keluhan::with(['room', 'user'])->findOrFail($id);
        $data = $request->all();

        if($request->hasFile('photo_kendala')) {
            if($request->file('photo_kendala')->isValid()) {
                Storage::disk('upload')->delete($request->photo_kendala);
                $photoKendala = $request->file('photo_kendala');
                $extensionsKendala = $photoKendala->getClientOriginalExtension();
                $kendalaUpload = "kendala/".date('YmdHis').".".$extensionsKendala;
                $kendalaPath = env('UPLOAD_PATH')."/kendala";
                $request->file('photo_kendala')->move($kendalaPath, $kendalaUpload);
                $data['photo_kendala'] = $kendalaUpload;
            }
        }

        $keluhan->update($data);
        return ResponseFormmater::success(
            $keluhan,
            'Data keluhan berhasil di update'
        );
    }

    public function deleteKeluhan(Request $request, $id) {
        $keluhan = Keluhan::with(['room', 'user'])->findOrFail($id);
        Storage::disk('upload')->delete($request->photo_kendala);
        $keluhan->delete($request->all());
        return ResponseFormmater::success(
            $keluhan,
            'Data keluhan berhasil di delete'
        );
    }
}
