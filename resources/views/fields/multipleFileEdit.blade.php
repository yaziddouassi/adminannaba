@php
    $type = $type ?? 'file';

    $config = [
        'image' => [
            'accept' => 'image/png,image/jpeg',
            'buttonLabel' => 'Add Image',
            'previewLabel' => 'Dernière image ajoutée :',
            'recordIconColor' => 'text-[red]',
        ],
        'audio' => [
            'accept' => 'audio/*',
            'buttonLabel' => 'Add Audio',
            'previewLabel' => 'Dernier audio ajouté :',
            'recordIconColor' => 'text-[red]',
        ],
        'video' => [
            'accept' => 'video/*',
            'buttonLabel' => 'Add Video',
            'previewLabel' => 'Dernière vidéo ajoutée :',
            'recordIconColor' => 'text-[red]',
        ],
        'file' => [
            'accept' => null,
            'buttonLabel' => 'Add File',
            'previewLabel' => null,
            'recordIconColor' => 'text-[blue]',
        ],
    ];

    $accept = $config[$type]['accept'] ?? null;
    $buttonLabel = $config[$type]['buttonLabel'] ?? 'Add File';
    $previewLabel = $config[$type]['previewLabel'] ?? null;
    $recordIconColor = $config[$type]['recordIconColor'] ?? 'text-[red]';
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

    <div class="mb-[5px]">
        <span class="text-[darkblue] font-bold">{{ $label }}</span><span class="text-[red]">@if($required == true)*@endif</span>
    </div>

    <div class="w-[100%] flex items-center justify-center">
        <label class="w-[100%]">
            <input type="file"
                wire:model="annabaMultipleFiles.{{ $file }}"
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
            <div class="flex w-[100%] h-[50px] px-2 flex-col bg-[blue] rounded-full shadow text-[white] text-[14px] font-semibold leading-4 items-center justify-center cursor-pointer focus:outline-none">{{ $buttonLabel }}</div>
        </label>
    </div>

    @if($type !== 'file')
        <!-- Aperçu du dernier fichier sélectionné -->
        <template x-if="lastFileUrl">
            <div class="mt-4 w-full">
                <span class="text-green-600 font-semibold">{{ $previewLabel }}</span>
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

    {{-- Nouveaux fichiers uploadés (pas encore enregistrés) --}}
    @foreach ($annabaMultipleFiles[$file] as $key => $item)
        <div class="flex bg-[#DDD] text-black border-[2px] border-white mt-[10px] p-[10px] pb-[20px] pt-[20px] rounded-[5px]">
            <div class="w-full">
                @if ($item)
                    {{ $item->getClientOriginalName() }}
                @endif
            </div>
            <div>
                <span class="material-icons text-[red] text-[30px] cursor-pointer"
                    wire:click="annabaDeleteFileByKey('{{ $file }}','{{ $key }}')">
                    delete_forever
                </span>
            </div>
        </div>
    @endforeach

    {{-- Fichiers déjà enregistrés --}}
    @if ($annabaMultipleFileRecords != [])
        @foreach ($annabaMultipleFileRecords[$file] as $key => $item)
            <div class="pt-[5px] flex @if($type === 'file') bg-[#DDD] text-black border-[2px] border-white mt-[10px] p-[10px] pb-[20px] pt-[20px] rounded-[5px] @endif">
                <div class="w-full">
                    @if ($item)
                        @if($type === 'image')
                            <img src="{{ $annabaUrlStorage }}{{ $item }}" width="100%" class="max-h-[50vh]">
                        @elseif($type === 'audio')
                            <audio controls class="pt-[8px] w-full" src="{{ $annabaUrlStorage }}{{ $item }}"></audio>
                        @elseif($type === 'video')
                            <video width="100%" class="max-h-[50vh]" controls src="{{ $annabaUrlStorage }}{{ $item }}"></video>
                        @else
                            {{ $item }}
                        @endif
                    @endif
                </div>
                <div class="@if($type !== 'file') pt-[14px] @endif">
                    <span class="material-icons {{ $recordIconColor }} text-[30px] cursor-pointer"
                        wire:click="annabaDeleteFileRecordByKey('{{ $file }}','{{ $key }}')">
                        delete_forever
                    </span>
                </div>
            </div>
        @endforeach
    @endif

    @if($type === 'file')
        @error("annabaMultipleFileErrors.$file")
            <div class="text-[red] pt-[5px]">
                <span class="error">{{ $message }}</span>
            </div>
        @enderror
    @endif

    @error("annabaMultipleFiles.$file")
        <div class="text-[red] pt-[5px]">
            <span class="error">{{ $message }}</span>
        </div>
    @enderror

    <!-- Progress Bar -->
    <div x-show="isUploading">
        <progress max="100" x-bind:value="progress" class="h-[10px] bg-[blue] rounded-[5px]"></progress>
    </div>

</div>