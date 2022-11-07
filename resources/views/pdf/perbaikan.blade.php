@extends('pdf.layouts')
@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- HEADER --}}
        <div class="col-lg-12">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="20%">
                        <img src="data:image/png;base64, {!! $images['logo']!!}" alt="" class="img img-fluid" width="50px;">
                        
                    </td>
                    <td width="60%" class="text-center font-weight-bolder">
                        PEMERINTAH PROVINSI KALIMANTAN SELATAN
                        <br>
                        RUMAH SAKIT UMUM DAERAH ULIN
                        <br>
                        JI. Jend. A. Yani No.43 Telp:3257472/3252180 Fax:3252229
                        <br>
                        BANJARMASIN
                    </td>
                    <td width="20%">
                        <img src="data:image/png;base64, {!! $images['logo-rs']!!}" alt="" class="img img-fluid" width="50px;">
                    </td>
                </tr>

            </table>
            <div class="d-block" style="border-bottom: solid 4px black;"></div>
            <div class="text-center font-weight-bolder text-underline mb-3">
                <u>
                    LAPORAN DATA PERBAIKAN
                </u>
            </div>
        </div>
        
        {{-- BODY --}}
        <div class="col-lg-12">
            @php
                $detail_perbaikans = [
                    ['name'=>'Detail Perbaikan', 'value'=>$perbaikan->perbaikan],
                    ['name'=>'Nama Teknisi', 'value'=>$perbaikan->supply->name_toko ?? '-'],
                    ['name'=>'Phone', 'value'=>$perbaikan->supply->phone ?? '-'],
                    ['name'=>'Tanggal', 'value'=>$perbaikan->tanggal],
                    ['name'=>'Quantity', 'value'=>$perbaikan->quantity],
                    ['name'=>'Harga', 'value'=>$perbaikan->total],
                    ['name'=>'Pesan', 'value'=>$perbaikan->komentar],
                ];
    
                $detail_keluhans = [
                    ['name'=>'Detail Keluhan', 'value'=>$perbaikan->keluhan->kendala ?? '-'],
                    ['name'=>'Role', 'value'=>$perbaikan->keluhan->user->role ?? '-'],
                    ['name'=>'Phone Number', 'value'=>$perbaikan->keluhan->phone_teknisi ?? '-'],
                    ['name'=>'Tanggal', 'value'=>$perbaikan->keluhan->tanggal ?? '-'],
                    ['name'=>'Ruangan', 'value'=>$perbaikan->keluhan->kendala->room->name_room ?? '-'],
                    ['name'=>'Tempat', 'value'=>$perbaikan->keluhan->kendala->room->lantai ?? '-'],
                    ['name'=>'Kendala', 'value'=>$perbaikan->keluhan->kendala ?? '-'],
                ];
    
                
            @endphp
            {{-- PERBAIKAN --}}
            <table class="table table-sm table-borderless">
                <tbody>
                    @foreach ($detail_perbaikans as $row)
                    <tr>
                        <td width="30%">{{$row['name']}}</td>
                        <td width="70%">: {{$row['value']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-3"></div>
            {{-- KELUHAN --}}
            <table class="table table-sm table-borderless">
                <tbody>
                    @foreach ($detail_keluhans as $row)
                    <tr>
                        <td width="30%">{{$row['name']}}</td>
                        <td width="70%">: {{$row['value']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="col-lg-12">
            <table class="table table-sm table-borderless">
                <thead>
                    <tr>
                        <td width="60%">
                            <img src="data:image/png;base64, {!! $images['qrcode']!!}">
                        </td>
                        <td width="60%" class="text-center">
                            Barjarmasin, {{date('d-m-Y')}}
                            <br>
                            A.n. Direktur
                            <br>
                            Wadir SDM, Diklit serta hukum
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            Drs. Sulaiman
                            <br>
                            Pembina Utama Muda
                            <br>
                            NIP.19600722 199003 1 005
                        </td>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>
@endsection