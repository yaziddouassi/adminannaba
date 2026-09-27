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
            'buttonLabel' => 'Choisir un fichier',
        ],
    ];

    $accept = $config[$type]['accept'] ?? null;
    $buttonLabel = $config[$type]['buttonLabel'] ?? 'Choisir un fichier';
@endphp

<div class="w-full"
    x-data="{
        isUploading: false,
        progress: 0,
        form: '{{ $form }}',
        field: '{{ $field }}',
        type: '{{ $type }}'
    }"
    x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">

    <div class="w-full mb-[5px]">
      <span class="text-black font-bold">
        {{ $annabaFormList[$form]['fields'][$field]['options']['label'] }}</span>
    </div>

    <div class="w-[100%] flex items-center justify-center">
        <label class="w-[100%]">
            <input type="file"
                wire:model="annabaFormList.{{ $form }}.fields.{{ $field }}.value"
                @if($accept) accept="{{ $accept }}" @endif
                hidden
                x-ref="fileInput"
                @change="if ($refs.fileInput.files.length) {
                    $wire.annabaFormList[form]['fields'][field]['options']['tempUrl'] =
                        URL.createObjectURL($refs.fileInput.files[0]);
                }"
            />
            <div class="flex w-[100%] h-[50px] px-2 flex-col border-[1px] border-black rounded-full
                shadow text-black text-[14px] font-semibold leading-4 items-center
                justify-center cursor-pointer focus:outline-none">{{ $buttonLabel }}</div>
        </label>
    </div>

     @if ($annabaFormList[$form]['fields'][$field]['value'])
    <div class="pt-[10px]">
      <button type="button" 
      class="text-red-600 border-red-600 border-[1px] rounded-[4px] px-[5px]"
      wire:click="resetInput('{{$form}}','{{$field}}')"> 
       FERMER  </button>
    </div>
   @endif
   

     @if (!$annabaFormList[$form]['fields'][$field]['value'])
        <template x-if="$wire.annabaFormList[form]['fields'][field]['options']['urlRecord']">
            <div class="mt-4 w-full">
                @if($type === 'image')
                    <img :src="$wire.annabaFormList[form]['fields'][field]['options']['urlRecord']" alt="Aperçu"
                        class="mt-2 rounded w-[150px] max-h-[50vh] border shadow">
                @elseif($type === 'audio')
                    <audio class="mt-2 w-full" controls :src="$wire.annabaFormList[form]['fields'][field]['options']['urlRecord']"></audio>
                @elseif($type === 'video')
                    <video class="mt-2 w-full max-w-[500px] max-h-[50vh] rounded shadow border" controls :src="$wire.annabaFormList[form]['fields'][field]['options']['urlRecord']"></video>
                @endif
            </div>
        </template>
      @endif

    @if ($annabaFormList[$form]['fields'][$field]['value'])
        <template x-if="$wire.annabaFormList[form]['fields'][field]['options']['tempUrl']">
            <div class="mt-4 w-full">
                @if($type === 'image')
                    <img :src="$wire.annabaFormList[form]['fields'][field]['options']['tempUrl']" alt="Aperçu"
                        class="mt-2 rounded w-[150px] max-h-[50vh] border shadow">
                @elseif($type === 'audio')
                    <audio class="mt-2 w-full" controls :src="$wire.annabaFormList[form]['fields'][field]['options']['tempUrl']"></audio>
                @elseif($type === 'video')
                    <video class="mt-2 w-full max-w-[500px] max-h-[50vh] rounded shadow border" controls :src="$wire.annabaFormList[form]['fields'][field]['options']['tempUrl']"></video>
                @endif
            </div>
        </template>
    @endif

    <!-- Progress Bar -->
    <div x-show="isUploading">
        <progress max="100" x-bind:value="progress" class="h-[10px] bg-[blue] rounded-[5px]"></progress>
    </div>

     @if ($type === 'file')
        <div class="pt-[5px]" id="robert">
           @if ($annabaFormList[$form]['fields'][$field]['value'])
            <div class="bg-[#DDD] text-black border-[2px] border-white mt-[10px]
                p-[10px] pb-[20px] pt-[20px] rounded-[5px]">
                {{ $annabaFormList[$form]['fields'][$field]['value']->getClientOriginalName() }}
            </div>
           @endif
       </div>
     @endif

    
     @if ($type === 'file')
        <div class="pt-[5px]" id="robert">
           @if (!$annabaFormList[$form]['fields'][$field]['value'])
            <div class="bg-[#DDD] text-black border-[2px] border-white mt-[10px]
                p-[10px] pb-[20px] pt-[20px] rounded-[5px]">
                {{ $annabaFormList[$form]['fields'][$field]['options']['urlRecord'] }}
            </div>
           @endif
       </div>
     @endif 
    


    @error("annabaFormList.$form.fields.$field.value")
        <div class="text-[red] pt-[5px]">
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>