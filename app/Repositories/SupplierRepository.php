<?php
namespace App\Repositories;
use App\Models\Supplier; use App\Repositories\Contracts\SupplierRepositoryInterface;
class SupplierRepository implements SupplierRepositoryInterface { public function all(){return Supplier::latest()->get();} public function find(int $id){return Supplier::findOrFail($id);} public function create(array $data){return Supplier::create($data);} public function update(int $id,array $data){$item=$this->find($id);$item->update($data);return $item;} public function delete(int $id){return $this->find($id)->delete();} }
