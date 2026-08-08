<div class="flex items-center justify-center min-h-[80vh]">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
        <h2 class="text-3xl font-black text-center text-slate-700 mb-8">Scan Resi Login</h2>
        
        <form wire:submit.prevent="login">
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email Address</label>
                <input type="email" id="email" wire:model="email" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 transition" required>
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" id="password" wire:model="password" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 transition" required>
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-start mb-5">
                <div class="flex items-center h-5">
                    <input id="remember" wire:model="remember" type="checkbox" value="" class="w-4 h-4 border border-slate-300 rounded bg-slate-50 focus:ring-3 focus:ring-sky-300">
                </div>
                <label for="remember" class="ml-2 text-sm font-medium text-gray-900">Ingat Saya</label>
            </div>
            
            <button type="submit" class="text-white bg-slate-800 hover:bg-slate-700 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm w-full px-5 py-3 text-center transition">Login Masuk</button>
        </form>

    </div>
</div>
