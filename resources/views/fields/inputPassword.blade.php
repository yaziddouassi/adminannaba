<div x-data="{ showPassword: false }" class="w-full">
    <div class="w-full mb-[5px]">
      <span class="text-black font-bold">
        {{$annabaFormList[$form]['fields'][$field]['options']['label']}}</span>
    </div>
    

    <div class="flex w-full h-[50px] border-[1px] border-[darkblue]">
        <div class="w-full pt-[4px]">
             <input :type="showPassword ? 'text' : 'password'"  
             class="w-full h-[42px] border-[0px]
             border-transparent focus:border-transparent focus:ring-0" 
             wire:model="annabaFormList.{{$form}}.fields.{{$field}}.value" autocomplete="off"
             readonly
             onfocus="this.removeAttribute('readonly');" >
        </div>
        <div @click="showPassword = !showPassword" class="p-[6px] pt-[12px]">
            <span class="material-icons text-[darkblue] cursor-pointer">
                visibility
                </span>
        </div>
    </div>

     @error("annabaFormList.$form.fields.$field.value")
   <div class="text-[red] pt-[5px]">
        <span class="error">{{ $message }}</span> 
   </div>
   @enderror


 </div>