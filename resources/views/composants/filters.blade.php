<div class="min-w-[130px] max-w-[130px] flex "
           x-data="{ openFilter : false}">
              <div class=" p-[6px] pt-[13px]">
                 <template x-if="$wire.filterActifs.length != 0">
                     <span  @click="$wire.set('filterActifs', [], true);openFilter=false"
                     class="cursor-pointer">@include('adminannaba::composants.svg3')</span>
                 </template>
              </div>
              <div class="pt-[8px] w-[70px] ">
                 @if(count($filterActifs) != 0)
                   {{ count($filterActifs)}}
                 @endif 

                 <template x-if="$wire.filterActifs.length != 0">
                      <span>Filtres</span>
                 </template> 
              </div>
              <div class=" h-[50px] w-[30px]" 
                  
                 @click.outside="openFilter = false">
                 <div class="pt-[8px] h-[50px] w-[30px] cursor-pointer"
                   @click="openFilter = !openFilter">
                   @include('adminannaba::composants.svg4')
                 </div>
                 <div class="p-[10px] w-[200px] ml-[-80px] bg-[white] relative z-[200]
                         border-[1px] border-gray-400 rounded"
                         x-show="openFilter">
                  
                    @foreach ($filters as $key => $item)
          
                        <div class="flex">
                          <div class="w-full flex">
                              <div class="pt-[5px] pr-[5px]">
                                  <template 
                                 x-if="!$wire.filterActifs.hasOwnProperty('{{$key}}')">
                                   <span @click="$wire.ajouterFilterActif('{{$key}}','asc')" class="cursor-pointer">
                                   @include('adminannaba::composants.svg1')</span>
                                 </template>

                                 <template 
                                 x-if="$wire.filterActifs.hasOwnProperty('{{$key}}')">
                                   <span @click="$wire.deleteFilterActif('{{$key}}')"
                                   class="cursor-pointer">
                                   @include('adminannaba::composants.svg2')</span>
                                 </template>
                                
                              </div>
                              <div>{{ $key }}</div>
                          </div>

                          
                          <div class="w-[50px]"> 

                                <!-- Flèche ASC -->
<template x-if="!$wire.filterActifs.hasOwnProperty('{{$key}}') || $wire.filterActifs['{{$key}}'] != 'asc'">
    <span class="text-[#ddd] cursor-pointer"
    @click="$wire.ajouterFilterActif('{{$key}}','asc')">↑↑</span>
</template>
<template x-if="$wire.filterActifs.hasOwnProperty('{{$key}}') && $wire.filterActifs['{{$key}}'] == 'asc'">
    <span class="font-bold text-[blue] cursor-pointer">↑↑</span>
</template>

<!-- Flèche DESC -->
<template x-if="!$wire.filterActifs.hasOwnProperty('{{$key}}') || $wire.filterActifs['{{$key}}'] != 'desc'">
    <span class="text-[#ddd] cursor-pointer"
    @click="$wire.ajouterFilterActif('{{$key}}','desc')">↓↓</span>
</template>
<template x-if="$wire.filterActifs.hasOwnProperty('{{$key}}') && $wire.filterActifs['{{$key}}'] == 'desc'">
    <span class="font-bold text-[blue] cursor-pointer">↓↓</span>
</template>
                                 
                             
                          </div>
                        </div>

                    @endforeach
                    
                 </div>

              </div>
</div>