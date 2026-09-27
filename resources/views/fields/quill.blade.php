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

    isEmpty() {
        // Vide si aucun texte ET aucun média (image, vidéo, formule)
        const text = this.quill.getText().replace(/\u00A0/g, ' ').trim();
        const hasEmbed = this.quill.root.querySelector('img, iframe, video, .ql-formula') !== null;
        return text === '' && !hasEmbed;
    },

    // Remet l'éditeur dans son état initial (contenu + formatage)
    resetEditor() {
        this.quill.setContents([{ insert: '\n' }], 'silent');
        this.quill.removeFormat(0, this.quill.getLength(), 'silent');
        this.quill.setSelection(0, 0, 'silent');
    },

    init() {
        const that = this;

        this.quill = new Quill(this.$refs.editor, {
            modules: { toolbar: that.toolbarOptions },
            theme: 'snow'
        });

        // 1. Valeur initiale
        const initialValue = this.content ?? '';
        if (initialValue && initialValue.trim() !== '') {
            this.quill.root.innerHTML = initialValue;
        } else {
            this.content = '';
            this.resetEditor();
        }

        // 2. Livewire → Quill
        this.$watch('content', (value) => {
            if (that.quill.root.innerHTML === value) return;

            if (value == null || value.trim() === '') {
                if (!that.isEmpty() || that.quill.getFormat().header || that.quill.getFormat().list) {
                    that.resetEditor();
                }
                return;
            }

            const currentText = that.quill.getText().replace(/\u00A0/g, ' ').trim();
            const tmp = document.createElement('div');
            tmp.innerHTML = value;
            const cleanNewText = tmp.innerText.replace(/\u00A0/g, ' ').trim();

            if (currentText !== cleanNewText) {
                that.quill.root.innerHTML = value;
            }
        });

        // 3. Quill → Livewire
        this.quill.on('text-change', (delta, oldDelta, source) => {
            if (source === 'api') return;

            if (that.isEmpty()) {
                that.content = '';
                // On réinitialise le formatage (titre, liste, alignement, etc.)
                that.resetEditor();
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
        <div x-ref="editor" class="bg-white min-h-[200px]"></div>
    </div>

    @error("annabaFormList.$form.fields.$field.value")
        <div class="text-[red] pt-[5px]">
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>