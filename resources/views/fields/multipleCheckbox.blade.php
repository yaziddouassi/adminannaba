<div x-data="{content : $wire.entangle('annabaFormList.{{$form}}.fields.{{$field}}.value'),

init() {

if(this.content == null) {
       this.content = [] ;
       }

$watch('content', value => {
       if(this.content == null) {
       this.content = [] ;
       }

    });

}



}">
   <div class="w-full mb-[5px]">
      <span class="text-black font-bold">
        {{$annabaFormList[$form]['fields'][$field]['options']['label']}}</span>
   </div>
    
    @foreach ($annabaFormList[$form]['fields'][$field]['options']['contents'] as $key => $item)
    <div>
    <label>
        <input type="checkbox" wire:model="annabaFormList.{{$form}}.fields.{{$field}}.value"
         value="{{$item}}">
        <span class="text-white">
        {{$annabaFormList[$form]['fields'][$field]['options']['labels'][$key]}}</span>
    </label>
    </div>
    @endforeach

 
    @error("annabaFormList.$form.fields.$field.value")
   <div class="text-[red] pt-[5px]">
        <span class="error">{{ $message }}</span> 
   </div>
   @enderror

 
 </div>