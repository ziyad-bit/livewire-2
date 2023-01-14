<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;

class PostComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $modalFormVisible=false;
    public $title;
    public $body;
    public $image;
    public $image_name;
    public $modalId;
    public $slug;

    public function rules()
    {
        return [
            'body'  => 'required',
            'title' => 'required',
            'slug'  => 'required|unique:posts,slug',
            'image' => 'required|max:1024',
        ];
    }

    public function formReset()
    {
        $this->reset(['title','slug' ,'body' ,'image' ]);
    }

    public function returnForm()
    {
        return[
            'title'      => $this->title,
            'slug'       => $this->slug,
            'body'       => $this->body,
            'image'      => $this->image_name,
        ];
    }

    public function updatedTitle(string $value)
    {
        $this->slug=Str::slug($value);
    }

    public function showCreateModal()
    {
        $this->modalFormVisible=! $this->modalFormVisible;
    }

    public function store()
    {
        $this->validate();
        $this->image_name=md5($this->image . microtime()).'.'.$this->image->extension();
        $this->image->storeAs('/', $this->image_name, 'uploads');

        Post::create($this->returnForm()+['user_id'=>Auth::id()]);

        $this->formReset();
        $this->modalFormVisible=false;

        $this->alert('success', 'you created post successfully', [  
            'position'          => 'center',
            'timer'             => 3000,
            'toast'             => true,
            'showCancelButton'  => false,
            'showConfirmButton' => false
        ]);
    }

    public function showUpdateModal(int $id)
    {
        $this->emit('updatePostEmit');

        $this->modalId= $id;
        $this->modalFormVisible=! $this->modalFormVisible;

        $data = Post::find($id);

        $this->title      = $data->title;
        $this->slug       = $data->slug;
        $this->body       = $data->body;
        $this->image_name = $data->image;
    }

    public function all_posts()
    {
        return Post::orderBydesc('id')->paginate(4);
    }

    public function render()
    {
        return view('livewire.post-component', [
            'posts'=>$this->all_posts()
        ]);
    }
}
