<?php

namespace App\Services;

use App\Models\{ActivityLog,Product,StockIn,StockOpname,StockOut,User};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function incoming(array $data, User $user): StockIn
    {
        return DB::transaction(function () use ($data, $user) {
            $tx = StockIn::create($data + ['user_id' => $user->id, 'status' => 'pending']);
            $this->log($user, 'Barang masuk (menunggu konfirmasi)', "Menunggu konfirmasi: +{$data['quantity']} stok.");
            return $tx;
        });
    }

    public function confirmIncoming(int $id, User $user): StockIn
    {
        return DB::transaction(function () use ($id, $user) {
            $tx = StockIn::lockForUpdate()->findOrFail($id);
            if ($tx->status === 'confirmed') return $tx;

            $product = Product::lockForUpdate()->findOrFail($tx->product_id);
            $product->increment('stock', $tx->quantity);
            $tx->update(['status' => 'confirmed']);

            $this->log($user, 'Konfirmasi barang masuk', "{$product->name}: +{$tx->quantity} stok dikonfirmasi.");
            return $tx;
        });
    }

    public function outgoing(array $data, User $user): StockOut
    {
        return DB::transaction(function () use ($data, $user) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);
            if ($product->stock < $data['quantity']) {
                throw ValidationException::withMessages(['quantity' => 'Stok tidak mencukupi.']);
            }

            $tx = StockOut::create($data + ['user_id' => $user->id, 'status' => 'pending']);
            $this->log($user, 'Barang keluar (menunggu konfirmasi)', "Menunggu konfirmasi: -{$data['quantity']} stok.");
            return $tx;
        });
    }

    public function confirmOutgoing(int $id, User $user): StockOut
    {
        return DB::transaction(function () use ($id, $user) {
            $tx = StockOut::lockForUpdate()->findOrFail($id);
            if ($tx->status === 'confirmed') return $tx;

            $product = Product::lockForUpdate()->findOrFail($tx->product_id);
            if ($product->stock < $tx->quantity) {
                throw ValidationException::withMessages(['quantity' => 'Stok tidak mencukupi untuk konfirmasi.']);
            }
            $product->decrement('stock', $tx->quantity);
            $tx->update(['status' => 'confirmed']);

            $this->log($user, 'Konfirmasi barang keluar', "{$product->name}: -{$tx->quantity} stok dikonfirmasi.");
            return $tx;
        });
    }

    public function opname(array $data, User $user): StockOpname
    {
        return DB::transaction(function () use ($data, $user) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);
            $system = $product->stock;
            $physical = (int) $data['physical_stock'];
            $product->update(['stock' => $physical]);

            $tx = StockOpname::create($data + ['user_id' => $user->id, 'system_stock' => $system, 'difference' => $physical - $system]);
            $this->log($user, 'Stock opname', "{$product->name}: {$system} menjadi {$physical}.");
            return $tx;
        });
    }

    private function log(User $user, string $action, string $description): void
    {
        ActivityLog::create(['user_id' => $user->id, 'action' => $action, 'description' => $description]);
    }
}