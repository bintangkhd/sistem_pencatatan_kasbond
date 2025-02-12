<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKasbond;
use App\Models\MasterData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KasbondKontroller extends Controller
{
    public function index(Request $request)
    {
        $data_kasbond = DataKasbond::with('MasterData')->latest();
        if ($request->tanggal) {
            $data_kasbond = $data_kasbond->where('created_at', $request->tanggal);
        }
        if (request('search')) {
            $data_kasbond->where('keterangan_transaksi', 'like', '%' . request('search') . '%')
                ->orWhere('nominal', 'like', '%' . request('search') . '%');
        }
        $data_kasbond = $data_kasbond->paginate(10);

        return view('index', compact('data_kasbond'), [
            'DataKasbond' => User::where('id', auth()->user()->id)->get()
        ]);
    }

    public function download($tanggal)
    {
        if ($tanggal != '') {
            $data_kasbond = DataKasbond::with('MasterData')
                ->latest()
                ->whereCreatedAt($tanggal)
                ->get();
        } else {
            $data_kasbond = DataKasbond::all();
        }
        return view('download', compact('data_kasbond'));
    }


    public function tambah(Request $request)
    {
        // $data_kasbond = DataKasbond::find($request->tanggal);
        $master_data = MasterData::all();
        return view('tambah', compact('master_data'));
    }

    public function store(Request $request)
    {
        $coa = MasterData::whereId($request->coa)->first();

        $request->validate([
            'nominal' => 'required',
            'coa' => 'required',
            'keterangan' => 'required'

        ]);

        $data = new DataKasbond();
        $data->id_master_data = $coa->id;
        $data->nominal = $request->nominal;
        $data->keterangan_transaksi = $request->keterangan;
        $data->created_at = now();

        if ($data->save()) {
            return redirect()->back()->with('status', 'Data Berhasil Disimpan');
        } else {
            return redirect()->back()->with('status', 'Data Gagal Disimpan');
        }
    }

    public function edit($id)
    {
        $data_kasbond = DataKasbond::find($id);
        $master_data = MasterData::all();
        return view('edit', compact('data_kasbond', 'master_data'));
    }

    public function update(Request $request)
    {
        $data = DataKasbond::where('id', $request->id)->first();
        $coa = MasterData::whereId($request->coa)->first();
        $data->id_master_data = $coa->id;
        $data->nominal = $request->nominal;
        $data->keterangan_transaksi = $request->keterangan_transaksi;
        $data->created_at = now();

        if ($data->save()) {
            return redirect('/data_kasbond')->with('status', 'Data Berhasil Diperbarui');
        } else {
            return redirect('/data_kasbond')->back()->with('status', 'Data Gagal Diperbarui');
        }
    }


    public function hapus($id)
    {
        // menghapus data pegawai berdasarkan id yang dipilih
        DB::table('data_kasbond')->where('id', $id)->delete();

        // alihkan halaman ke halaman pegawai
        return redirect('/data_kasbond')->with('status', 'Data Berhasil Dihapus');
    }
}
