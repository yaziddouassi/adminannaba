<div class="w-full overflow-x-auto">

    <div class="pb-[10px] text-right">
                @include('adminannaba::composants.btnModal',
               ['form' => 'create' , 'label' => 'Articles'])
     </div>  

    <div class=" h-[50px]  border-b-[1px] border-b-gray-300 flex">
        <div class="w-full ">
          @include('adminannaba::composants.groupActions')
        </div>
        <div class="min-w-[310px] max-w-[310px] flex">
           @include('adminannaba::composants.filters')
           @include('adminannaba::composants.search')
        </div>
    </div>

    <div>
    <table class="w-full min-w-[640px] text-left text-sm text-gray-700">
      <thead class="">
        <tr>
          <th scope="col" class="px-4 py-3 text-center font-semibold border-b "></th>
          <th scope="col" class="px-4 py-3 text-center font-semibold border-b ">ID</th>
          <th scope="col" class="px-4 py-3 text-center font-semibold border-b ">Nom</th>
          <th scope="col" class="px-4 py-3 text-center font-semibold border-b ">Actions
             
          </th>
        </tr>
      </thead>
      <tbody class="">
        @foreach($entitys as $entity)
        <tr class="hover:bg-gray-50 cursor-pointer">
          <td class="px-[5px] py-3 text-center">
            
           <span @click=" if ($wire.tabIds.includes({{ $entity->id }})) { $wire.tabIds = $wire.tabIds.filter( id => id !== {{ $entity->id }} ) } else { $wire.tabIds.push({{ $entity->id }}) } ">
           <template x-if="$wire.tabIds.includes({{ $entity->id }})">
             @include('adminannaba::composants.svg2')
           </template>
           <template x-if="!$wire.tabIds.includes({{ $entity->id }})">
              @include('adminannaba::composants.svg1')
           </template>
           </span>

          </td>
          <td class="px-[5px] py-3 text-center">{{ $entity->id }}</td>
          <td class="px-[5px] py-3 text-center">{{ $entity->name }}</td>
          <td class="px-[5px] py-3 text-center">
               <div class="flex gap-[5px] justify-center">
                @include('adminannaba::composants.btnModal2',
               ['form' => 'update1' , 'label' => 'Edit', 'icon' => 'edit',
                'record' => $entity ,
               'class' => 'bg-[red] text-white p-[11px] rounded-[6px]'])
             </div>


          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
     </div>
        <div>
          @include('adminannaba::modalForm')
        </div>
        <div>
          {{ $entitys->links() }}
        </div>

  </div>