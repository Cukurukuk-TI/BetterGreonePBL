<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductPage extends Component
{
    use WithPagination;

    public $sort = 'created_at-desc';
    public $minPrice;
    public $maxPrice;
    public $showFilterModal = false;

    // Temporary filter values for modal
    public $tempSort = 'created_at-desc';
    public $tempMinPrice;
    public $tempMaxPrice;

    public function mount()
    {
        $this->tempSort = $this->sort;
        $this->tempMinPrice = $this->minPrice;
        $this->tempMaxPrice = $this->maxPrice;
    }

    public function openFilterModal()
    {
        $this->tempSort = $this->sort;
        $this->tempMinPrice = $this->minPrice;
        $this->tempMaxPrice = $this->maxPrice;
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function applyFilters()
    {
        $this->sort = $this->tempSort;
        $this->minPrice = $this->tempMinPrice;
        $this->maxPrice = $this->tempMaxPrice;
        $this->showFilterModal = false;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->sort = 'created_at-desc';
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->tempSort = 'created_at-desc';
        $this->tempMinPrice = null;
        $this->tempMaxPrice = null;
        $this->showFilterModal = false;
        $this->resetPage();
    }

    public function hasActiveFilters()
    {
        return $this->sort !== 'created_at-desc' ||
               !empty($this->minPrice) ||
               !empty($this->maxPrice);
    }

    public function render()
    {
        $query = Product::query();

        // Filter berdasarkan harga
        $query->when($this->minPrice, function ($q) {
            $q->where('price', '>=', $this->minPrice);
        });

        $query->when($this->maxPrice, function ($q) {
            $q->where('price', '<=', $this->maxPrice);
        });

        // Logika untuk sorting
        if ($this->sort) {
            $parts = explode('-', $this->sort);
            $column = $parts[0];
            $direction = $parts[1] ?? 'asc';
            $query->orderBy($column, $direction);
        }

        $products = $query->paginate(12);

        return view('livewire.product-page', [
            'products' => $products,
        ])->layout('layouts.app');
    }
    
}
