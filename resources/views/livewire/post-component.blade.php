<div>

    <div class="flex justify-end">
        <x-jet-button wire:click='showCreateModal'>
            create post
        </x-jet-button>
    </div>

    <table class="w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 border-b-2 border-gray-200  text-blue-500 tracking-wider">{{ __('ID') }}
                </th>

                <th class="px-6 py-3 border-b-2 border-gray-200  text-blue-500 tracking-wider">{{ __('Image') }}
                </th>

                <th class="px-6 py-3 border-b-2 border-gray-200  text-blue-500 tracking-wider">{{ __('Title') }}
                </th>

                <th class="px-6 py-3 border-b-2 border-gray-200  text-blue-500 tracking-wider">{{ __('Action') }}
                </th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200 ">
            @forelse($posts as $post)
                <tr>
                    <td class="  px-6 py-3 ">{{ $post->id }}</td>

                    <td class="px-6 py-3 "><img src="{{ asset('images/' . $post->image) }}" alt="{{ $post->title }}"
                            width="200"></td>

                    <td class="px-6 py-3 ">
                        <a class="text-indigo-600 hover:text-indigo-900">
                            {{ $post->title }}
                        </a>
                    </td>

                    <td class="px-6 py-3 ">
                        <x-jet-button wire:click='showUpdateModal({{ $post->id }})'>
                            edit post
                        </x-jet-button>

                        <x-jet-danger-button>
                            delete post
                        </x-jet-danger-button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-6 py-3 border-b border-gray-200" colspan="4">No posts found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pt-4">
        {{ $posts->links() }}
    </div>

    <x-jet-dialog-modal wire:model='modalFormVisible'>
        <x-slot name='title'>
            create post
        </x-slot>

        <x-slot name='content'>
            <x-jet-label for='title' value='title'></x-jet-label>
            <x-jet-input type='text' id="title" wire:model.debounce.1000ms='title' class="block mt-1 w-full">
            </x-jet-input>

            @error('title')
                <div class="text-red-600">{{ $message }}</div>
            @enderror

            <div class="mt-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}"></x-jet-label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        {{ config('app.url') . '/' }}
                    </span>
                    <x-jet-input type="text" wire:model.defer="slug"
                        class="form-input flex-1 block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        placeholder="url slug">
                    </x-jet-input>
                </div>
                @error('slug')
                    <span class="text-red-600 text-sm font-extrabold">{{ $message }}</span>
                @enderror
            </div>



            <div class="mt-4">
                <x-jet-label for="body" value="{{ __('Content') }}"></x-jet-label>

                <div wire:ignore wire:key="myId">
                    <div id="body" class="block mt-1 w-full">
                        {!! $body !!}
                    </div>
                </div>

                <textarea id="body" class="hidden body-content" wire:model.defer="body">
                    {!! $body !!}
                </textarea>

                @error('body')
                    <span class="text-red-900 text-sm font-extrabold">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="image" value="{{ __('Image') }}"></x-jet-label>

                <div class="flex py-3">
                    @if ($image)
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span
                                class="inline-flex items-center p-3 rounded border border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <img src="{{ $image->temporaryUrl() }}" width="200">
                            </span>
                        </div>
                    @endif

                    @if ($image_name)
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span
                                class="inline-flex items-center p-3 rounded border border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <img src="{{ asset('/images/' . $image_name) }}" width="200">
                            </span>
                        </div>
                    @endif
                </div>

                <x-jet-input type="file" wire:model="image" name="image" id="image"
                    class=" flex-1 block w-full "></x-jet-input>
                @error('image')
                    <span class="text-red-900 text-sm font-extrabold">{{ $message }}</span>
                @enderror
            </div>
        </x-slot>

        <x-slot name='footer'>
            <x-jet-secondary-button wire:click='showCreateModal'>
                cancel
            </x-jet-secondary-button>

            <x-jet-button wire:click='store'>
                create post
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-livewire-alert::scripts />

    <script>
        window.onload = function() {
            if (document.querySelector('#body')) {
                ClassicEditor.create(document.querySelector('#body'), {})
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            document.querySelector('#body').value = editor.getData();
                            @this.set('body', document.querySelector('#body').value);
                        });

                        Livewire.on('updatePostEmit', function() {
                            editor.setData(document.querySelector('.body-content').value)
                        });
                    })
                    .catch(error => {
                        console.log(error.stack);
                    });
            }
        }
    </script>
@endpush
