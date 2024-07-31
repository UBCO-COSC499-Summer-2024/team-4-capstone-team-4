@props(['showModal'])

<div id="add-user-modal" tabindex="-1" aria-hidden="true" class="{{ $showModal ? '' : 'hidden' }} overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="relative p-4 w-full max-w-lg">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <p class="text-base font-semibold text-[#3b4779] dark:text-white">
                    Add New User Account
                </p>
                <button wire:click="$set('showModal', false)" type="button" id="close-modal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>            
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="firstname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Firstname: </label>
                        <input wire:model="firstname" type="text" name="firstname" id="firstname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="ex. John">
                        <x-input-error for="firstname" class="mt-2"/>
                        <br>

                        <label for="lastname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lastname: </label>
                        <input wire:model="lastname" type="text" name="lastname" id="lastname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="ex. Doe">
                        <x-input-error for="lastname" class="mt-2"/>
                        <br>

                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email: </label>
                        <input wire:model="email" type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="ex. johndoe@example.com">
                        <x-input-error for="email" class="mt-2"/>
                        <br>

                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password: </label>
                        <input wire:model="password" type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="ex. Password123">
                        <x-input-error for="password" class="mt-2"/>
                        <br>

                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Confirmation: </label>
                        <input wire:model="password_confirmation" type="password" name="password_confirmation" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="ex. Password123">
                        <x-input-error for="password_confirmation" class="mt-2"/>
                        <br>

                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Roles: </label>
                        <div class="flex space-x-10">
                            <div class="flex flex-col space-y-2">
                                <div>
                                    <x-checkbox wire:model="user_roles" name="user_roles[]" value="instructor" id="role_instructor" />
                                    <label for="role_instructor" class="text-sm text-gray-900 dark:text-white">Instructor</label>
                                </div>
                                <div>
                                    <x-checkbox wire:model="user_roles" name="user_roles[]" value="dept_head" id="role_dept_head" />
                                    <label for="role_dept_head" class="text-sm text-gray-900 dark:text-white">Department Head</label>
                                </div>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <div>
                                    <x-checkbox wire:model="user_roles" name="user_roles[]" value="dept_staff" id="role_dept_staff" />
                                    <label for="role_dept_staff" class="text-sm text-gray-900 dark:text-white">Department Staff</label>
                                </div>
                                <div>
                                    <x-checkbox wire:model="user_roles" name="user_roles[]" value="admin" id="role_admin" />
                                    <label for="role_admin" class="text-sm text-gray-900 dark:text-white">Admin</label>
                                </div>
                            </div>
                        </div>                        
                        <x-input-error for="user_roles" class="mt-2"/>
                    </div>
                </div>
                <x-staff-button wire:click="addUser" type="submit">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Add User
                </x-staff-button>
            </div>
        </div>
    </div>
</div>
