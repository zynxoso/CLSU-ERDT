<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;

class ModalManager extends Component
{
    public $show = false;
    public $component = null;
    public $arguments = [];
    
    protected $listeners = ['closeModal']; // 'openModal' removed
    
    #[On('openModal')]
    public function openModal($component = null, $arguments = [])
    {
        if (is_array($component) && isset($component['component'])) {
            // Handle case when receiving a single array parameter
            $params = $component;
            $this->component = $params['component'] ?? null;
            $this->arguments = $params['arguments'] ?? [];
        } else {
            // Handle case when receiving individual parameters
            $this->component = $component;
            $this->arguments = $arguments;
        }
        $this->show = true;
    }
    
    public function closeModal()
    {
        $this->show = false;
        $this->component = null;
        $this->arguments = [];
    }
    
    public function render()
    {
        return view('livewire.admin.modal-manager');
    }
}
