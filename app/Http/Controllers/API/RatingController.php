<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class RatingController extends Controller
{
    public function createRate(Request $request)
    {
        $request->validate([
            'name_teknisi' => 'sometimes|string|max:255',
            'phone_teknisi' => 'sometimes|string|max:255',
            'email_teknisi' => 'sometimes|string|max:255',
            'rate' => '',
            'komentar' => 'sometimes|string',
            'photo_teknisi' => 'sometimes|string',
            'nilai' => '',
            'user_id' => 'sometimes|exists:users,id',
            'perbaikan_id' => 'sometimes|exists:perbaikans,id',
            'room_id' => 'sometimes|exists:rooms,id',
            'keluhan_id' => 'sometimes|exists:keluhans,id',
            'tanggal' => 'sometimes|string|max:255'
        ]);

        $rating = Rating::create([
            'name_teknisi' => $request->name_teknisi,
            'phone_teknisi' => $request->phone_teknisi,
            'email_teknisi' => $request->email_teknisi,
            'rate' => $request->rate,
            'komentar' => $request->komentar,
            'photo_teknisi' => $request->photo_teknisi,
            'nilai' => $request->nilai,
            'user_id' => $request->user_id,
            'perbaikan_id' => $request->perbaikan_id,
            'room_id' => $request->room_id,
            'keluhan_id' => $request->keluhan_id,
            'tanggal' => $request->tanggal
        ]);

        $rating = Rating::with(['room', 'user', 'keluhan', 'perbaikan'])->find($rating->id);

        try {
            $rating->save();
            return ResponseFormmater::success(
                $rating,
                'Data Rating berhasil ditambahkan'
            );
        } catch(\Exception $error) {
            return ResponseFormmater::error(
                null,
                $error->getMessage(),
                404
            );
        }
    }

    public function getAllRate(Request $request)
    {
        $id = $request->input('id');
        $name_teknisi = $request->input('name_teknisi');
        $room_id = $request->input('room_id');
        $user_id = $request->input('user_id');
        $keluhan_id = $request->input('keluhan_id');
        $perbaikan_id = $request->input('perbaikan_id');


        if($id)
        {
            $rating = Rating::with(['room', 'user', 'keluhan', 'perbaikan'])->find($id);

            if($rating) {
                return ResponseFormmater::success(
                    $rating,
                    'Data Rating berhasil diambil'
                );
            }
            else {
                return ResponseFormmater::error(
                    null,
                    'Data Rating Tidak ada',
                    404
                );
            }
        }

        $rating = Rating::with(['room', 'user', 'keluhan_id', 'perbaikan_id']);

        if($name_teknisi)
        {
            $rating->where('name_teknisi', 'like', '%' . $name_teknisi . '%');
        }

        if($room_id)
        {
            $rating->where('room_id', $room_id);
        }

        if($user_id)
        {
            $rating->where('user_id', $user_id);
        }

        if($keluhan_id)
        {
            $rating->where('keluhan_id', $keluhan_id);
        }

        if($perbaikan_id)
        {
            $rating->where('perbaikan_id', $perbaikan_id);
        }

        return ResponseFormmater::success(
            $rating->get(),
            'Data List Perbaikan berhasil diambil'
        );
    }

    public function updateRate(Request $request, $id)
    {
        $rating = Rating::with(['room', 'user', 'keluhan', 'perbaikan'])->findOrFail($id);
        $data = $request->all();

        $rating->update($data);
        return ResponseFormmater::success(
            $rating,
            'Data Rating berhasil di update'
        );
    }

    public function deleteRate(Request $request, $id)
    {
        $rating = Rating::with(['room', 'user', 'keluhan', 'perbaikan'])->findOrFail($id);
        $data = $request->all();

        $rating->delete($data);

        return ResponseFormmater::success(
            $rating,
            'Data Rating berhasil di delete'
        );
    }
}
