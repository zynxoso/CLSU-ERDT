<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ScholarProfile;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;

class DeleteConfirmationModal extends Component
{
    public $id;
    public $name;
    
    #[Rule('required')]
    public $confirmText = '';
    
    public function mount($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
    
    public function delete()
    {
        // Validate confirmation text
        if ($this->confirmText !== 'delete') {
            $this->addError('confirmText', 'You must type "delete" to confirm');
            return;
        }
        
        try {
            DB::beginTransaction();
            
            $scholar = ScholarProfile::findOrFail($this->id);
            $scholar->delete();
            
            DB::commit();
            
            session()->flash('success', "Scholar <strong>{$this->name}</strong> has been deleted successfully.");
            $this->dispatch('closeModal');
            $this->dispatch('scholarDeleted');
            
            // Redirect to refresh the page
            return redirect()->route('admin.scholars.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete scholar. Please try again. Details: ' . $e->getMessage());
            $this->dispatch('closeModal');
            
            // Redirect to refresh the page even on error
            return redirect()->route('admin.scholars.index');
        }
    }
    
    public function render()
    {
        return view('livewire.admin.delete-confirmation-modal');
    }
}
