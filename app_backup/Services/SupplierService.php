<?php
namespace App\Services;
use App\Repositories\Contracts\SupplierRepositoryInterface;
class SupplierService { public function __construct(private SupplierRepositoryInterface $suppliers){} public function getAllSuppliers(){return $this->suppliers->all();} public function getSupplierById(int $id){return $this->suppliers->find($id);} public function createSupplier(array $data){return $this->suppliers->create($data);} public function updateSupplier(int $id,array $data){return $this->suppliers->update($id,$data);} public function deleteSupplier(int $id){return $this->suppliers->delete($id);} }
