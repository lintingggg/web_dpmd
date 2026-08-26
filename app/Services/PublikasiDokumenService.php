<?php

namespace App\Services;

use App\Models\PublikasiDokumen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class PublikasiDokumenService
{
    public function storeDokumen(array $data, $request): PublikasiDokumen
    {
        DB::beginTransaction();
        try {
            if ($request->hasFile('file_dokumen')) {
                $path = $request->file('file_dokumen')->store('dokumen', 'public');
                $data['file_dokumen'] = $path;
            }
            
            $dok = PublikasiDokumen::create($data);
            ActivityLogger::log('Membuat Dokumen', "Mengunggah dokumen publikasi baru: {$dok->judul}", $dok, null, $dok->toArray());
            
            DB::commit();

            return $dok;
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    public function updateDokumen(PublikasiDokumen $dokumen, array $data, $request): PublikasiDokumen
    {
        DB::beginTransaction();
        try {
            if ($request->hasFile('file_dokumen')) {
                // Hapus file lama jika ada
                if ($dokumen->file_dokumen && Storage::disk('public')->exists($dokumen->file_dokumen)) {
                    Storage::disk('public')->delete($dokumen->file_dokumen);
                }
                
                // Simpan file baru
                $path = $request->file('file_dokumen')->store('dokumen', 'public');
                $data['file_dokumen'] = $path;
            } else {
                unset($data['file_dokumen']);
            }

            $oldValues = $dokumen->toArray();
            $dokumen->update($data);
            ActivityLogger::log('Mengubah Dokumen', "Mengubah data dokumen: {$dokumen->judul}", $dokumen, $oldValues, $dokumen->fresh()->toArray());
            
            DB::commit();

            return $dokumen;
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($path)) Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    public function deleteDokumen(PublikasiDokumen $dokumen): void
    {
        DB::beginTransaction();
        try {
            if ($dokumen->file_dokumen && Storage::disk('public')->exists($dokumen->file_dokumen)) {
                Storage::disk('public')->delete($dokumen->file_dokumen);
            }
            
            $oldValues = $dokumen->toArray();
            $dokumen->delete();
            ActivityLogger::log('Menghapus Dokumen', "Menghapus dokumen: {$dokumen->judul}", $dokumen, $oldValues, null);
            
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
