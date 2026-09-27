<div x-data="{
    
    quill: null,
    toolbarOptions: [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'header': 1 }, { 'header': 2 }],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'script': 'sub' }, { 'script': 'super' }],
        [{ 'indent': '-1' }, { 'indent': '+1' }],
        [{ 'direction': 'rtl' }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        ['link', 'image', 'video', 'formula'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'font': [] }],
        [{ 'align': [] }],
        ['clean']
    ],
    content: $wire.entangle('annabaFormList.{{$form}}.fields.{{$field}}.value'),
    
    init() {
        const that = this;

        function isQuillEmpty(quill) {
            return quill.getText().replace(/\u00A0/g, ' ').trim() === '';
        }

        this.quill = new Quill(this.$refs.editor, {
            modules: {
                toolbar: that.toolbarOptions
            },
            theme: 'snow'
        });

        // 1. Initialisation de la valeur PHP / Livewire
        const initialValue = this.content ?? '';
        if (initialValue && initialValue.trim() !== '') {
            this.quill.root.innerHTML = initialValue;
        } else {
            this.content = '';
            this.quill.root.innerHTML = '';
        }

        // 2. Watch Livewire → Quill (quand la valeur change côté serveur)
        this.$watch('content', (value) => {
            // Éviter les boucles infinies
            if (that.quill.root.innerHTML === value) return;

            if (value == null || value === '' || value.trim() === '') {
                if (!isQuillEmpty(that.quill)) {
                    that.quill.setText('');
                }
                return;
            }

            // On met à jour seulement si le contenu est vraiment différent
            const currentText = that.quill.getText().replace(/\u00A0/g, ' ').trim();
            const newText = document.createElement('div');
            newText.innerHTML = value;
            const cleanNewText = newText.innerText.replace(/\u00A0/g, ' ').trim();

            if (currentText !== cleanNewText) {
                that.quill.root.innerHTML = value;
            }
        });

        // 3. Quill → Livewire
        this.quill.on('text-change', (delta, oldDelta, source) => {
            if (source === 'api') return; // Ignore les changements faits par code

            if (isQuillEmpty(that.quill)) {
                that.content = '';
                return;
            }

            that.content = that.quill.root.innerHTML;
        });
    }
}">
    <div class="w-full mb-[5px]">
        <span class="text-black font-bold">
            {{ $annabaFormList[$form]['fields'][$field]['options']['label'] }}
        </span>
    </div>

    <div wire:ignore>
        <div
            x-ref="editor"
            class="bg-white min-h-[200px]"
        ></div>
    </div>

    @error("annabaFormList.$form.fields.$field.value")
        <div class="text-[red] pt-[5px]">
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>