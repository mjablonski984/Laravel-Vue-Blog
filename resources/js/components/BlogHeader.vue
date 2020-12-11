<template>
    <header class="w-full container mx-auto">
        <div class="flex flex-col items-center py-12">
            <span class="font-bold text-gray-700 text-2xl sm:text-5xl">
                {{$props.title ? $props.title : '&lt;&nbsp;DevBlog&nbsp;&gt;'}}
            </span>
            <p class="text-lg text-gray-600">
                {{$props.subtitle ? $props.subtitle : ''}}
            </p>
        </div>
    </header>
</template>

<script>
export default {
    props: ['title','subtitle'],
}
</script>