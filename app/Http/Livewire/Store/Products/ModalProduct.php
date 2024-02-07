<?php

namespace App\Http\Livewire\Store\Products;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class ModalProduct extends ModalComponent
{
    use Actions;

    public Product $product;

    public ?int $quantity = 1;

    public ?string $observation = null;

    protected $listeners = ['incrementQuantity' => 'increment', 'decrementQuantity' => 'decrement'];

    protected array $rules = ['observation' => 'nullable|min:10|max:150'];

    public function mount(Product $product, int $quantity = 1): void
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function render(): View
    {
        return view('livewire.store.products.modal-product');
    }

    public function increment(): int
    {
        return $this->quantity++;
    }

    public function decrement(): int
    {
        if ($this->quantity > 1) {
            return $this->quantity--;
        } else {
            return $this->quantity;
        }
    }

    public function addToCart(Product $product, $quantity): void
    {

        \Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => $quantity,
            'options' => [
                'path_img' => $product->image->first()?->path ?? null,
                'description' => $product->description ?? null,
                'observation' => $this->observation ?? null,
            ],
        ])->associate('App\Models\Product');

        $this->notifications();
        $this->closeModal();
        $this->emit('cartItem::index::addToCart');

    }

    public function notifications(): void
    {
        $this->notification()->success(
            $title = 'Parabéns!',
            $description = 'Produto addicionado ao carrinho'
        );
    }
}
