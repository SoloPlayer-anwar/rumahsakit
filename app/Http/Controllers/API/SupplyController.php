<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Supply;
use Exception;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function createSupply (Request $request)
    {
        $request->validate([
            'name_toko' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        $supply = Supply::create([
            'name_toko' => $request->name_toko,
            'phone' => $request->phone,
            'alamat' => $request->alamat
        ]);


        try {
            $supply->save();
            return ResponseFormmater::success(
                $supply,
                'Data Supplier Berhasil ditambahkan'
            );
        }

        catch (Exception $error)
        {
            return ResponseFormmater::error(
                $error,
                'Data Supplier Gagal ditambahkan'
            );
        }
    }

    public function getSupply (Request $request)
    {
        $id = $request->input('id');
        $name_toko = $request->input('name_toko');
        $phone = $request->input('phone');
        $alamat = $request->input('alamat');

        if($id)
        {
            $supply = Supply::find($id);

            if($supply)
            {
                return ResponseFormmater::success(
                    $supply,
                    'Data Supplier Berhasil diambil'
                );
            }

            else {
                return ResponseFormmater::error(
                    null,
                    'Data Supplier Gagal diambil',
                    404
                );
            }
        }

        $supply = Supply::query();

        if($name_toko)
        {
            $supply->where('name_toko', 'like', '%' . $name_toko . '%');
        }

        if($phone)
        {
            $supply->where('phone', 'like', '%' . $phone . '%');
        }

        if($alamat)
        {
            $supply->where('alamat', 'like', '%' . $alamat . '%');
        }

        return ResponseFormmater::success(
            $supply->get(),
            'List Data Supplier Berhasil diambil'
        );
    }

    public function updateSupply (Request $request, $id)
    {
        $supply = Supply::findOrFail($id);
        $data = $request->all();

        $supply->update($data);
        return ResponseFormmater::success(
            $supply,
            'Data Supplier Berhasil di update'
        );
    }

    public function deleteSupply (Request $request, $id)
    {
        $supply = Supply::findOrFail($id);
        $data = $request->all();

        $supply->delete($data);
        return ResponseFormmater::success(
            $supply,
            'Data Supply Berhasil di delete'
        );
    }

}
