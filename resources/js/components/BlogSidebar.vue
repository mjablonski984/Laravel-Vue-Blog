<template>
    <aside class="w-full md:w-1/3 flex flex-col items-center px-3">
        <div class="w-full bg-white shadow flex flex-col my-4 p-6">
            <p class="text-xl font-semibold pb-5 border-b border-grey-300 uppercase mb-4">About Us</p>
            <p class="pb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
                reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui 
                officia deserunt mollit anim id est laborum.</p>
            <a href="#" class="w-full border border-blue-700 font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-4">
                Contact Us
            </a>
        </div>

        <div class="w-full bg-white shadow flex flex-col my-4 p-6">
            <p class="text-xl font-semibold pb-5 border-b border-grey-300 uppercase mb-4">Social media</p>
            <div class="grid grid-cols-3 gap-3">
                <Icon class="hover:opacity-75 cursor-pointer" color="#4267B2" size="40" type="logo-facebook" />
                <Icon class="hover:opacity-75 cursor-pointer" color="#1DA1F2" size="40" type="logo-twitter" />
                <Icon class="hover:opacity-75 cursor-pointer" color="#F56040" size="40" type="logo-instagram" />
                <Icon class="hover:opacity-75 cursor-pointer" color="#00AFF0" size="40" type="logo-skype" />
                <Icon class="hover:opacity-75 cursor-pointer" color="#DB4437" size="40" type="logo-googleplus" />   
                <Icon class="hover:opacity-75 cursor-pointer" color="#FF0000" size="40" type="logo-youtube" />
            </div>
        </div>       
    </aside>
</template>