<template>
  <div class="w-10/12 flex justify-between sm:flex-col mb-9 sm:mb-6 m-auto">
    <div class="flex flex-col tracking-tight">
      <span class="text-base text-activeText mb-2">{{ header }}</span>
      <span class="text-xs leading-6 text-activeText font-medium sm:mb-4">
        {{ paragraph }}
      </span>
    </div>
    <div class="flex items-end">
      <button
        title=" Crea un nuevo proyecto , asigne personal y asigne reportes."
        class="h-8 sm:w-10/12 bg-orange text-white text-[0.7rem] leading-4 px-4 rounded"
        @click="$emit('indicadoresRestricciones')"
      >
        {{ buttonText }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "indicator-component",
  props: {
    header: String,
    paragraph: String,
    buttonText: String,

  },
};
</script>
