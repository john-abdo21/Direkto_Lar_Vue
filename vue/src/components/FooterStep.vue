<template>
  <div class="w-10/12 m-auto">
    <hr class="mt-10 h-[1px] border-[#D0D9F1] mb-10 sm:hidden">
    <div class="flex sm:flex-col items-center">
      <span class="font-medium text-[0.7rem]  shrink-0 sm:mb-8 sm:w-full" v-if="flag">{{ text }}</span>
      <div class="flex w-full justify-end sm:flex-col-reverse">
        <button class="h-6 px-2 border-2 border-orange rounded text-orange text-[0.6rem] leading-4 mr-4 sm:mr-0" @click="$emit('cancel')">{{ buttons[0] }}</button>
        <button class="h-6 px-2 rounded bg-orange text-white text-[0.6rem] leading-4 sm:mb-4" @click="$emit('next')">{{ buttons[1] }}</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'footer-step-component',
  props: {
    buttons: Array,
    flag: Boolean,
    text: String,
  }
}
</script>
