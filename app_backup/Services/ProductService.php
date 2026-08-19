<?php
namespace App\Services;
use App\Models\ActivityLog;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
class ProductService { public function __construct(private ProductRepositoryInterface $products) {} public function create(array $data, User $user) { $p=$this->products->create($data); $this->log($user,'Membuat produk',"Produk {$p->name} dibuat."); return $p; } public function update(int $id,array $data,User $user) { $p=$this->products->update($this->products->find($id),$data); $this->log($user,'Memperbarui produk',"Produk {$p->name} diperbarui."); return $p; } public function delete(int $id,User $user):void { $p=$this->products->find($id); $name=$p->name; $this->products->delete($p); $this->log($user,'Menghapus produk',"Produk {$name} dihapus."); } private function log(User $user,string $action,string $description):void { ActivityLog::create(compact('action','description')+['user_id'=>$user->id]); } }
