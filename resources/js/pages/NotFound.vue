<template>
<div class="h-screen w-screen bg-gray-100 flex items-center xl:container mx-auto">
	<div class="container flex flex-col md:flex-row items-center justify-center px-5 text-gray-700">
   	<div class="max-w-md">
      <div class="text-5xl font-dark font-bold">404</div>
        <p class="text-2xl md:text-3xl font-light leading-normal mb-8">Sorry we couldn't find this page. </p>          
      <router-link to="/" >
        <button class="px-4 inline py-2 text-sm font-medium leading-5 shadow text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none focus:shadow-outline-blue bg-blue-400 active:bg-blue-400 hover:bg-blue-600">Back to homepage</button>
      </router-link>
    </div> 
  </div>
</div>
</template>