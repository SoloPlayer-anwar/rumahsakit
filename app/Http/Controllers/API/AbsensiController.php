<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function addAbsensi(Request $request)
    {
        $request->validate([
            'status' => 'required|string|max:255',
            'tanggal' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'npm' => 'required|string|max:255',
        ]);

        $absensi = Absensi::create([
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'user_id' => $request->user_id,
            'npm' => $request->npm,
        ]);

        try {
            $absensi->save();
            return ResponseFormmater::success(
                $absensi,
                'Data absensi berhasil dibuat'
            );
        }catch (\Exception $error) {
            return ResponseFormmater::error(
                $error->getMessage(),
                'Data absensi gagal dibuat'
            );
        }
    }

    public function getAbsensi (Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $tanggal = $request->input('tanggal');
        $npm = $request->input('npm');
        $user_id = $request->input('user_id');

        if($id)
        {
            $absensi = Absensi::with(['user'])->find($id);

            if($absensi)
            {
                return ResponseFormmater::success(
                    $absensi,
                    'Data absensi berhasil diambil'
                );
            }
            else {
                return ResponseFormmater::error(
                    null,
                    'Data absensi tidak ada',
                    404
                );
            }
        }

        $absensi = Absensi::with(['user']);

        if($status)
        {
            $absensi->where('status', 'like', '%' .$status. '%');
        }


        if($tanggal)
        {
            $absensi->where('tanggal', 'like', '%' .$tanggal. '%');
        }

        if($npm)
        {
            $absensi->where('npm', 'like', '%' .$npm. '%');
        }
        if($user_id)
        {
            $absensi->where('user_id', $user_id);
        }

        return ResponseFormmater::success(
            $absensi->get(),
            'Data List Absensi Berhasil di ambil'
        );

    }

    public function updateAbsensi (Request $request, $id)
    {
        $absensi = Absensi::with(['user'])->findOrFail($id);
        $data = $request->all();
        $absensi->update($data);

        return ResponseFormmater::success(
            $absensi,
            'Data absensi berhasil di update'
        );
    }

    public function deleteAbsensi (Request $request, $id)
    {
        $absensi = Absensi::with(['user'])->findOrFail($id);
        $data = $request->all();
        $absensi->delete($data);

        return ResponseFormmater::success(
            $absensi,
            'Data absensi berhasil di Delete'
        );
    }
}
