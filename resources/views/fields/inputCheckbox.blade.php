<div x-data="{content : $wire.entangle('annabaFormList.{{$form}}.fields.{{$field}}.value'),

init() {
    this.changerValeur();

    $watch('content', value => {
        this.changerValeur();
    });
},

changerValeur() {
    var nameField = this.$refs.checkbox;

    if ($wire.annabaFormList.{{$form}}.fields.{{$field}}.value == null) {
        this.content = 0;
    }

    if ($wire.annabaFormList.{{$form}}.fields.{{$field}}.value == false) {
        this.content = 0;
    }

    if ($wire.annabaFormList.{{$form}}.fields.{{$field}}.value == true) {
        this.content = 1;
    }

    if ($wire.annabaFormList.{{$form}}.fields.{{$field}}.value == 0) {
        nameField.checked = false;
    }

    if ($wire.annabaFormList.{{$form}}.fields.{{$field}}.value == 1) {
        nameField.checked = true;
    }
}

}">

    <div class="w-full mb-[5px]">
        <span class="text-black font-bold">
            {{$annabaFormList[$form]['fields'][$field]['options']['label']}}
        </span>
    </div>

    <div>
        <input type="checkbox"
               x-ref="checkbox"
               wire:model="annabaFormList.{{$form}}.fields.{{$field}}.value"
               >
    </div>

    @error("annabaFormList.$form.fields.$field.value")
        <div class="text-[red] pt-[5px]">
            <span class="error">{{ $message }}</span>
        </div>
    @enderror

</div>