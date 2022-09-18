<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Supply;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplyController extends Controller
{
    public function createSupply (Request $request)
    {
        $request->validate([
            'name_toko' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string',
            'name_barang' => 'sometimes|string|max:255',
            'photo_product' => 'sometimes|image|mimes:png,jpeg,jpg,gif,svg|max:5048',
            'quantity' => 'sometimes|integer',
            'harga' => 'sometimes|integer',
            'total_harga' => 'sometimes|integer',
            'photo_barcode' => 'sometimes|image|mimes:png,jpeg,jpg,gif,svg|max:2048',
        ]);

        $supply = Supply::create([
            'name_toko' => $request->name_toko,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'name_barang' => $request->name_barang,
            'photo_product' => $request->photo_product,
            'quantity' => $request->quantity,
            'harga' => $request->harga,
            'total_harga' => $request->total_harga,
            'photo_barcode' => $request->photo_barcode
        ]);


        if($request->file('photo_product')->isValid()) {
            $photoProduct = $request->file('photo_product');
            $extensionProduct = $photoProduct->getClientOriginalExtension();
            $productUpload = "product/".date('YmdHis').".".$extensionProduct;
            $productPath = env('UPLOAD_PATH')."/product";
            $request->file('photo_product')->move($productPath, $productUpload);
            $supply['photo_product'] = $productUpload;
        }

        if($request->file('photo_barcode')->isValid()) {
            $photoBarcode = $request->file('photo_barcode');
            $extensionsBarcode = $photoBarcode->getClientOriginalExtension();
            $barcodeUpload = "barcode/".date('YmdHis').".".$extensionsBarcode;
            $barcodePath = env('UPLOAD_PATH')."/barcode";
            $request->file('photo_barcode')->move($barcodePath, $barcodeUpload);
            $supply['photo_barcode'] = $barcodeUpload;
        }

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
        $name_barang = $request->input('name_barang');

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

        if($name_barang)
        {
            $supply->where('name_barang', 'like', '%' . $name_barang . '%');
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

        if($request->hasFile('photo_product')) {
            if($request->file('photo_product')->isValid()) {
                Storage::disk('upload')->delete($request->photo_product);
                $photoProduct = $request->file('photo_product');
                $extensionProduct = $photoProduct->getClientOriginalExtension();
                $productUpload = "product/".date('YmdHis').".".$extensionProduct;
                $productPath = env('UPLOAD_PATH')."/product";
                $request->file('photo_product')->move($productPath, $productUpload);
                $data['photo_product'] = $productUpload;
            }
        }

        if($request->hasFile('photo_barcode')) {
            if($request->file('photo_barcode')->isValid()) {
                Storage::disk('upload')->delete($request->photo_barcode);
                $photoBarcode = $request->file('photo_barcode');
                $extensionsBarcode = $photoBarcode->getClientOriginalExtension();
                $barcodeUpload = "barcode/".date('YmdHis').".".$extensionsBarcode;
                $barcodePath = env('UPLOAD_PATH')."/barcode";
                $request->file('photo_barcode')->move($barcodePath, $barcodeUpload);
                $data['photo_barcode'] = $barcodeUpload;
            }
        }

        $supply->update($data);
        return ResponseFormmater::success(
            $supply,
            'Data Supply Berhasil di update'
        );
    }

    public function deleteSupply (Request $request, $id)
    {
        $supply = Supply::findOrFail($id);
        Storage::disk('upload')->delete($request->photo_product);
        Storage::disk('upload')->delete($request->photo_barcode);

        $supply->delete($request->all());
        return ResponseFormmater::success(
            $supply,
            'Data Supply Berhasil di delete'
        );

    }

}
