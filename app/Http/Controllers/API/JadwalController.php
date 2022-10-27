<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function getJadwal (Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        if($id)
        {
            $jadwal = Jadwal::find($id);

            if($jadwal)
            {
                return ResponseFormmater::success(
                    $jadwal,
                    'Data jadwal ada'
                );
            }else {
                return ResponseFormmater::error(
                    null,
                    'Tidak ada jadwal hari ini'
                );
            }
        }

        $jadwal = Jadwal::query();

        if($status)
        {
            $jadwal->where('status', 'like', '%' .$status. '%');
        }

        return ResponseFormmater::success(
            $jadwal->get(),
            'Jadwal list berhasil diambil'
        );
    }

    public function updateJadwal (Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $data = $request->all();
        $jadwal->update($data);

        return ResponseFormmater::success(
            $jadwal,
            'Jadwal berhasil update'
        );
    }

    public function deleteJadwal (Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $data = $request->all();
        $jadwal->delete($data);

        return ResponseFormmater::success(
            $jadwal,
            'Jadwal berhasil Delete'
        );
    }
}
