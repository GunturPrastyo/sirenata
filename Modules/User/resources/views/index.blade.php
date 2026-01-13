<x-layouts.app title="Users">
    <div class="w-full max-w-5xl mx-auto">
        <h1 class="" style="color: red;">Hello World</h1>
        <p class="text-blue-500 my-2">Module: {!! config('user.name') !!} 
            @auth
                {{ Auth::user()->name }}
            @endauth
        </p>

        @auth
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
        @endauth
        <x-flash-message class="my-2" />
        
        <form action="{{ route('user.index') }}" method="GET" class="my-5">
            <div class="flex shadow-xs rounded-base -space-x-0.5">
                <label for="search-dropdown" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">
                    Search Users
                </label>

                <select id="order" name="orderBy" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                    <option value="" selected>Pilih Order By</option>
                    <option value="desc" @selected(request('orderBy')==='desc' )>Terbaru</option>
                    <option value="asc" @selected(request('orderBy')==='asc' )>Terlama</option>
                </select>

                <input type="search" name="search" value="{{ request('search') }}" id="search-dropdown" id="input-group-1" class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body" placeholder="Search for products" >

                <button type="submit" class="inline-flex items-center  text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-e-base text-sm px-4 py-2.5 focus:outline-none">
                    <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    Search
                </button>
            </div>
        </form>

        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default my-5">
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">No.</th>
                        <th scope="col" class="px-6 py-3 font-medium">Name</th>
                        <th scope="col" class="px-6 py-3 font-medium">email</th>
                        <th scope="col" class="px-6 py-3 font-medium">Role</th>
                        <th scope="col" class="px-6 py-3 font-medium">Permission</th>
                        <th scope="col" class="px-6 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $key => $user)
                        <tr class="bg-neutral-primary border-b border-default">
                            <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                {{ $key + $users->firstItem() }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->getRoleNames()->implode(', ') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->getPermissionNames()->implode(', ') ?? 'N/A'  }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('user.edit', $user->id) }}" class="text-blue-600">
                                        Edit
                                    </a>
                                    <x-modal-delete 
                                        :id="$user->id"
                                        message="Are you sure delete user"
                                        :item-name="$user->name"
                                        :route="route('user.destroy', $user->id)"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <td colspan="3" class="px-6 py-4 text-center">
                            No Data
                        </td>
                    @endforelse
                </tbody>
            </table>

        </div>
        <div class="">
            {{ $users->links() }}
        </div>
    </div>
</x-layouts.app>
