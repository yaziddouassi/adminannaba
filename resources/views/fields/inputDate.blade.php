<div>
   <div class="w-full mb-[5px]">
      <span class="text-black font-bold">
        {{$annabaFormList[$form]['fields'][$field]['options']['label']}}</span>
   </div>
   <div>
      <input type="date" wire:model="annabaFormList.{{$form}}.fields.{{$field}}.value"
       class="w-full rounded-[4px] h-[50px] border-gray-800 border-[1px]"
       step="{{$annabaFormList[$form]['fields'][$field]['options']['step']}}"
         @if($annabaFormList[$form]['fields'][$field]['options']['min'] != 'infinite')
            min="{{$annabaFormList[$form]['fields'][$field]['options']['min']}}"
         @endif
         @if($annabaFormList[$form]['fields'][$field]['options']['max'] != 'infinite')
           max="{{$annabaFormList[$form]['fields'][$field]['options']['max']}}"
         @endif>
   </div>

   @error("annabaFormList.$form.fields.$field.value")
   <div class="text-[red] pt-[5px]">
        <span class="error">{{ $message }}</span> 
   </div>
   @enderror

</div>