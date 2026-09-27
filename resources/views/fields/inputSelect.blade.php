<div>
    <div class="w-full mb-[5px]">
      <span class="text-black font-bold">
        {{$annabaFormList[$form]['fields'][$field]['options']['label']}}</span>
   </div>

    <div>
        <select type="text" wire:model="annabaFormList.{{$form}}.fields.{{$field}}.value"
         class="w-full  h-[50px]
        border-[darkblue] border-[1px]">
            <option value=""></option>
            @foreach ($annabaFormList[$form]['fields'][$field]['options']['contents'] as $key => $item)
                <option value="{{$item}}">
                {{$annabaFormList[$form]['fields'][$field]['options']['labels'][$key]}}
                </option>
            @endforeach
        </select> 
    </div>


    @error("annabaFormList.$form.fields.$field.value")
   <div class="text-[red] pt-[5px]">
        <span class="error">{{ $message }}</span> 
   </div>
   @enderror


 </div>