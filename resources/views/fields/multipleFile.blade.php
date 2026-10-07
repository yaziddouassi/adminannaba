@php
    $type = $annabaFormList[$form]['fields'][$field]['typeBis'] ?? 'file';

    $config = [
        'image' => [
            'accept' => 'image/png,image/jpeg',
            'buttonLabel' => 'Add Image',
            'previewLabel' => 'Dernière image ajoutée :',
        ],
        'audio' => [
            'accept' => 'audio/*',
            'buttonLabel' => 'Add Audio',
            'previewLabel' => 'Dernier audio ajouté :',
        ],
        'video' => [
            'accept' => 'video/*',
            'buttonLabel' => 'Add Video',
            'previewLabel' => 'Dernière vidéo ajoutée :',
        ],
        'file' => [
            'accept' => null,
            'buttonLabel' => 'Add File',
            'previewLabel' => null,
        ],
    ];

    $accept = $config[$type]['accept'] ?? null;
    $buttonLabel = $config[$type]['buttonLabel'] ?? 'Choisir un fichier'; 
@endphp

<div class="w-full"
    x-data="{
        isUploading: false,
        progress: 0,
        lastFileUrl: null,
        type: '{{ $type }}'
    }"
    x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">

    <div class="w-full mb-[5px]">
      <span class="text-black font-bold">
        {{$annabaFormList[$form]['fields'][$field]['options']['label']}}</span>
    </div>

    <div class="w-full flex items-center justify-center">
        <label class="w-full">
            <input
                type="file"
                wire:model="annabaFormList.{{$form}}.fields.{{$field}}.value"
                @if($accept) accept="{{ $accept }}" @endif
                hidden
                x-ref="fileInput"
                @if($type !== 'file')
                    @change="if ($refs.fileInput.files.length) {
                        lastFileUrl = URL.createObjectURL($refs.fileInput.files[$refs.fileInput.files.length - 1]);
                    }"
                    @foo.window="lastFileUrl = null"
                @endif
            />
            <div class="flex w-full h-[50px] px-2 flex-col bg-[blue] rounded-full shadow text-white text-[14px] font-semibold leading-4 items-center justify-center cursor-pointer focus:outline-none">
                {{ $buttonLabel }}
            </div>
        </label>
    </div>

    @if($type !== 'file' && !empty($annabaFormList[$form]['fields'][$field]['value']))
        <!-- Aperçu du dernier fichier sélectionné -->
        <template x-if="lastFileUrl">
            <div class="mt-4 w-full">
                @if($type === 'image')
                    <img :src="lastFileUrl" alt="Dernière image" class="mt-2 rounded w-[150px] h-auto border shadow">
                @elseif($type === 'audio')
                    <audio class="mt-2 w-full" controls :src="lastFileUrl"></audio>
                @elseif($type === 'video')
                    <video class="mt-2 rounded w-full max-w-[400px] border shadow" controls :src="lastFileUrl"></video>
                @endif
            </div>
        </template>
    @endif

    @foreach ($annabaFormList[$form]['fields'][$field]['value'] as $key => $item)
        <div class="flex bg-[#DDD] text-black border-[2px] border-white mt-[10px] p-[10px] pb-[20px] pt-[20px] rounded-[5px]">
            <div class="w-full">
                @if ($item)
                    {{$item->getClientOriginalName()}}
                @endif
            </div>
            <div>
                <span class="material-icons text-[red] text-[30px] cursor-pointer"
                  wire:click="annabaDeleteFileByKey('{{$form}}','{{$field}}','{{$key}}')"  >
                    delete_forever
                </span>
            </div>
        </div>
    @endforeach

    

    @error("annabaFormList.$form.fields.$field.value")
        <div class="text-[red] pt-[5px]">
            <span class="error">{{ $message }}</span>
        </div>
    @enderror

    <!-- Barre de progression -->
    <div x-show="isUploading">
        <progress max="100" x-bind:value="progress" class="h-[10px] bg-[blue] rounded-[5px] w-full"></progress>
    </div>

</div>