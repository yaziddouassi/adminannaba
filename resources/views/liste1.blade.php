 <div class="w-full overflow-x-auto">
    <table class="w-full min-w-[640px] text-left text-sm text-gray-700">
      <thead class="">
        <tr>
          <th scope="col" class="px-4 py-3 text-center font-semibold border-b "></th>
          <th scope="col" class="px-4 py-3 text-center font-semibold border-b ">ID</th>
          <th scope="col" class="px-4 py-3 text-center font-semibold border-b ">Nom</th>
          <th scope="col" class="px-4 py-3 text-center font-semibold border-b ">Actions
             <span x-text="$wire.tabIds.length"></span>
              <span @click="$wire.tabIds = []">ss</span>
          </th>
        </tr>
      </thead>
      <tbody class="">
        @foreach($entitys as $entity)
        <tr class="hover:bg-gray-50 cursor-pointer">
          <td class="px-[10px] py-3 text-center">
            
           <span @click=" if ($wire.tabIds.includes({{ $entity->id }})) { $wire.tabIds = $wire.tabIds.filter( id => id !== {{ $entity->id }} ) } else { $wire.tabIds.push({{ $entity->id }}) } ">
           <template x-if="$wire.tabIds.includes({{ $entity->id }})">
             @include('adminannaba::composants.svg2')
           </template>
           <template x-if="!$wire.tabIds.includes({{ $entity->id }})">
              @include('adminannaba::composants.svg1')
           </template>
           </span>

          </td>
          <td class="px-[10px] py-3 text-center">{{ $entity->id }}</td>
          <td class="px-[10px] py-3 text-center">{{ $entity->name }}</td>
          <td class="px-[10px] py-3 text-center">
            <span wire:click="openModal1('create')">AA </span> 
            <span wire:click="openModal2('update1')">BB </span> 
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $entitys->links() }}
  </div>