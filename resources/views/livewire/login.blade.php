<div class="min-h-screen bg-black flex items-center justify-center px-4">
    <form wire:submit="login" class="w-full max-w-sm bg-zinc-900 p-8 rounded-xl space-y-4">

        <h1 class="text-white text-xl font-semibold mb-4 text-center">Connexion</h1>

        <div>
            <input wire:model="email" type="email" placeholder="Email"
                class="w-full px-4 py-3 rounded-[2px] bg-zinc-800 text-white 
                border border-gray-200 focus:outline-none focus:border-blue-600">
            @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <input wire:model="password" type="password" placeholder="Mot de passe"
                class="w-full px-4 py-3 rounded-[2px] bg-zinc-800 text-white
                 border border-gray-200 focus:outline-none focus:border-blue-600">
            @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-gray-400 text-sm">
            <input wire:model="remember" type="checkbox" class="rounded bg-zinc-800 border-white">
            Se souvenir de moi
        </label>

        <button type="submit" class="w-full bg-blue-600 text-white font-medium
                 py-3 rounded-[2px] hover:bg-blue-400">
            Se connecter
        </button>
    </form>
</div>