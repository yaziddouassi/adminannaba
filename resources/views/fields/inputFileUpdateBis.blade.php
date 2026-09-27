@php
    $type = $annabaFormList[$form]['fields'][$field]['typeBis'] ?? 'file';

    $config = [
        'image' => [
            'accept' => 'image/png,image/jpeg',
            'buttonLabel' => 'Choisir une image',
        ],
        'audio' => [
            'accept' => 'audio/*',
            'buttonLabel' => 'Choisir un audio',
        ],
        'video' => [
            'accept' => 'video/*',
            'buttonLabel' => 'Choisir une vidéo',
        ],
        'file' => [
            'accept' => null,
            'buttonLabel' => 'Choose File',
        ],
    ];

    $accept = $config[$type]['accept'] ?? null;
    $buttonLabel = $config[$type]['buttonLabel'] ?? 'Choose File';
@endphp

<div class="w-full"
    x-data="{
        isUploading: false,
        progress: 0,
        hasNewUpload: false,
        form: '{{ $form }}',
        field: '{{ $field }}',
        type: '{{ $type }}',
        oldFileUrl: '',

        init() {
            this.updateOldFileUrl();

            this.$watch('$wire.annabaRecordBis', () => {
                this.updateOldFileUrl();
            });

            this.$watch('$wire.annabaUrlStorage', () => {
                this.updateOldFileUrl();
            });
        },

        updateOldFileUrl() {
            if ($wire.annabaRecordBis && $wire.annabaRecordBis[this.cle]) {
                this.oldFileUrl = $wire.annabaUrlStorage + $wire.annabaRecordBis[this.cle];
            }
        }
    }"
    x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false; $wire.annabaHasNewUpload[cle] = true"
    x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">

    <div class="mb-[5px]">
        <span class="text-[darkblue] font-bold">{{ $label }}</span><span class="text-[red]">@if($required == true)*@endif</span>
    </div>

    @if ($type === 'file')
        {{-- Bouton d'upload, masqué quand le bloc "fermé" est affiché --}}
        <div class="w-[100%] flex items-center justify-center" x-show="$wire.annabaFile0pens.{{ $file }}">
            <label class="w-[100%]">
                <input type="file" wire:model="annabaFiles.{{ $file }}" hidden />
                <div class="flex w-[100%] h-[50px] px-2 flex-col bg-[blue] rounded-full shadow text-[white] text-[14px] font-semibold leading-4 items-center justify-center cursor-pointer focus:outline-none">{{ $buttonLabel }}</div>
            </label>
        </div>
    @else
        <div class="w-[100%] flex items-center justify-center">
            <label class="w-[100%]">
                <input type="file"
                    wire:model="annabaFiles.{{ $file }}"
                    @if($accept) accept="{{ $accept }}" @endif
                    hidden
                    x-ref="fileInput"
                    @change="if ($refs.fileInput.files.length) {
                        $wire.annabaPreviewUrl[cle] = URL.createObjectURL($refs.fileInput.files[0]);
                        $wire.annabaHasNewUpload[cle] = true;
                    }"
                />
                <div class="flex w-[100%] h-[50px] px-2 flex-col border-[1px] border-black rounded-full
                    shadow text-black text-[14px] font-semibold leading-4 items-center justify-center
                    cursor-pointer focus:outline-none">{{ $buttonLabel }}</div>
            </label>
        </div>
    @endif

    @if ($type !== 'file' && $annabaFiles[$file])
        {{-- Aperçu du nouveau fichier --}}
        <template x-if="$wire.annabaPreviewUrl[cle] && $wire.annabaHasNewUpload[cle]">
            <div class="mt-4 w-full">
                @if($type === 'image')
                    <img class="mt-2 w-full max-w-[500px] max-h-[50vh] rounded shadow border" :src="$wire.annabaPreviewUrl[cle]" />
                @elseif($type === 'audio')
                    <audio class="mt-2 w-full" controls :src="$wire.annabaPreviewUrl[cle]"></audio>
                @elseif($type === 'video')
                    <video class="mt-2 w-full max-w-[500px] max-h-[50vh] rounded shadow border" controls :src="$wire.annabaPreviewUrl[cle]"></video>
                @endif
            </div>
        </template>
    @endif

    <!-- Progress Bar -->
    <div x-show="isUploading" class="mt-2">
        <progress max="100" x-bind:value="progress" class="w-full h-[10px] bg-[blue] rounded-[5px]"></progress>
    </div>

    @if ($type !== 'file')
        {{-- Aperçu de l'ancien fichier (si pas de nouvel upload) --}}
        @if ($annabaRecord != null && isset($annabaRecord[$file]) && !$annabaFiles[$file])
            <template x-if="!$wire.annabaPreviewUrl[cle] && !$wire.annabaHasNewUpload[cle] && oldFileUrl">
                <div class="mt-[5px]">
                    @if($type === 'image')
                        <img x-bind:src="oldFileUrl" class="mt-2 w-full max-w-[500px] max-h-[50vh] rounded shadow border" />
                    @elseif($type === 'audio')
                        <figure>
                            <audio controls class="pt-[8px] w-full" x-bind:src="oldFileUrl"></audio>
                        </figure>
                    @elseif($type === 'video')
                        <video class="w-full max-h-[50vh]" controls x-bind:src="oldFileUrl">
                            <source x-bind:src="oldFileUrl" type="video/mp4" />
                        </video>
                    @endif
                </div>
            </template>
        @endif

        {{-- Nom du fichier uploadé --}}
        <div class="pt-[5px]" x-show="$wire.annabaPreviewUrl[cle]">
            @if ($annabaFiles[$file])
                <div class="bg-[#DDD] text-black border-[2px] border-white mt-[10px] p-[10px] pb-[20px] pt-[20px] rounded-[5px]">
                    {{ $annabaFiles[$file]->getClientOriginalName() }}
                </div>
            @endif
        </div>
    @else
        {{-- Bloc "fermé" : affiche le nom de l'ancien fichier enregistré --}}
        <div class="pb-[20px] pt-[0px]" x-show="!$wire.annabaFile0pens.{{ $file }}">
            <div class="text-center bg-[#CFCFCF] h-[50px] text-[32px] font-[arial] pr-[5px] border-[BLACK] border-[1px]"
                @click="$wire.annabaFile0pens.{{ $file }} = true">
                Close
            </div>
            <div class="bg-[#DDD] text-black border-[2px] border-white mt-[10px] p-[10px] pb-[20px] pt-[20px] rounded-[5px]">
                @if ($annabaRecord != null)
                    {{ $annabaRecord[$file] }}
                @endif
            </div>
        </div>


    <div class="pt-[5px]">
        @if ($annabaFormList[$form]['fields'][$field]['value'])
            <div class="bg-[#DDD] text-black border-[2px] border-white mt-[10px]
                p-[10px] pb-[20px] pt-[20px] rounded-[5px]">
                {{ $annabaFormList[$form]['fields'][$field]['value']->getClientOriginalName() }}
            </div>
        @endif
    </div>

    @error("annabaFormList.$form.fields.$field.value")
        <div class="text-[red] pt-[5px]">
            <span class="error">{{ $message }}</span>
        </div>
    @enderror  

</div>