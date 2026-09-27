<div>
   <div class="w-full mb-[5px]">
      <span class="text-black font-bold">
        {{$annabaFormList[$form]['fields'][$field]['options']['label']}}</span>
   </div>

    
    @foreach ($annabaFormList[$form]['fields'][$field]['options']['contents'] as $key => $item)
    <div>
    <label>
        <input type="radio" wire:model="annabaFormList.{{$form}}.fields.{{$field}}.value"  value="{{$item}}">
        {{$annabaFormList[$form]['fields'][$field]['options']['labels'][$key]}}
    </label>
    </div>
    @endforeach
    
    

   

  @error("annabaFormList.$form.fields.$field.value")
   <div class="text-[red] pt-[5px]">
        <span class="error">{{ $message }}</span> 
   </div>
   @enderror
 
 </div>