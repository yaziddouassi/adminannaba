<div class="px-[10px]">

  @if(url()->current() === url($chemin))
    <a href="{{ $chemin }}" wire:navigate.hover id="robert">
      <div class="bg-[blue] text-white pl-[10px] rounded-[4px] mt-[10px] 
      text-[20px] flex w-full gap-[10px] py-[7px]">
        <div class="pt-[3px]">
          <span class="material-icons text-[18px]">{{ $icon }}</span>
        </div>
        <div>
          <span>{{ $label }}</span>
        </div>
      </div>
    </a>
  @else
    <a href="{{ $chemin }}" wire:navigate.hover id="robert2">
      <div class="text-white rounded-[4px] 
      text-[20px] flex w-full gap-[10px] py-[7px]">
        <div class="pt-[3px]">
          <span class="material-icons text-[18px]">{{ $icon }}</span>
        </div>
        <div>
          <span>{{ $label }}</span>
        </div>
      </div>
    </a>
  @endif

</div>