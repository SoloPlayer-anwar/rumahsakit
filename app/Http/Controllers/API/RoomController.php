<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function createRoom (Request $request)
    {
        $request->validate([
            'kode_room' => 'required|integer',
            'name_room' => 'required|string|max:255',
            'photo_room' => 'sometimes|image|mimes:png,jpeg,jpg,gif,svg|max:5048'
        ]);

        $room = Room::create([
            'kode_room' => $request->kode_room,
            'name_room' => $request->name_room,
            'photo_room' => $request->photo_room
        ]);

        if($request->file('photo_room')->isValid()) {
            $photoRoom = $request->file('photo_room');
            $extensRoom = $photoRoom->getClientOriginalExtension();
            $roomUpload = "room/".date('YmdHis').".".$extensRoom;
            $roomPath = env('UPLOAD_PATH')."/room";
            $request->file('photo_room')->move($roomPath, $roomUpload);
            $room['photo_room'] = $roomUpload;
        }

        try {
            $room->save();
            return ResponseFormmater::success(
                $room,
                'Data Room berhasil di tambahkan'
            );
        }
        catch(Exception $error)
        {
            return ResponseFormmater::error(
                $error->getMessage(),
                'Data Room Gagal ditambahkan',
                404
            );
        }
    }

    public function getRoom (Request $request)
    {
        $id = $request->input('id');
        $kode_room = $request->input('kode_room');
        $name_room = $request->input('name_room');

        if($id)
        {
            $room = Room::find($id);

            if($room)
            {
                return ResponseFormmater::success(
                    $room,
                    'Data Room Berhasil diambil'
                );
            }
            else {
                return ResponseFormmater::error(
                    null,
                    'Data Room Gagal diambil',
                    404
                );
            }
        }

        $room = Room::query();

        if($kode_room)
        {
            $room->where('kode_room', 'like', '%' . $kode_room . '%');
        }

        if($name_room)
        {
            $room->where('name_room', 'like', '%' .$name_room . '%');
        }

        return ResponseFormmater::success(
            $room->get(),
            'Data List Room Berhasil diambil'
        );
    }

    public function updateRoom (Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $data = $request->all();

        if($request->hasFile('photo_room')) {
            if($request->file('photo_room')->isValid()) {
                Storage::disk('upload')->delete($request->photo_room);
                $photoRoom = $request->file('photo_room');
                $extensRoom = $photoRoom->getClientOriginalExtension();
                $roomUpload = "room/".date('YmdHis').".".$extensRoom;
                $roomPath = env('UPLOAD_PATH')."/room";
                $request->file('photo_room')->move($roomPath, $roomUpload);
                $data['photo_room'] = $roomUpload;
            }
        }

        $room->update($data);
        return ResponseFormmater::success(
            $room,
            'Data Room Berhasil di update'
        );
    }

    public function deleteRoom (Request $request, $id)
    {
        $room = Room::findOrFail($id);
        Storage::disk('upload')->delete($request->photo_room);
        $room->delete($request->all());
        return ResponseFormmater::success(
            $room,
            'Room Berhasil di delete'
        );
    }


}
