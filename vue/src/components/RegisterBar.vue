<script setup>

const f_black = new URL('../assets/first-black.svg', import.meta.url)
const f = new URL('../assets/first.svg', import.meta.url)
const s_black = new URL('../assets/second-black.svg', import.meta.url)
const s = new URL('../assets/second.svg', import.meta.url)
const t_black = new URL('../assets/third-black.svg', import.meta.url)
const t = new URL('../assets/third.svg', import.meta.url)

</script>

<template>
  <div class="fixed top-0 left-0 right-0 flex-col z-10">
    <div class="cursor-pointer hidden h-14 bg-[#002B6B] sm:flex items-center " @click="handleRedirect('login')">
      <img src="../assets/mobile-logo.png" class="ml-8" alt="">
    </div>
    <div class="flex h-16 sm:h-14 bg-side relative items-center justify-center">
      <img src="../assets/logo-black.png" class="cursor-pointer absolute left-8 sm:hidden" alt="" @click="handleRedirect('login')">
      <div class="flex text-sm">
        <div class="flex mr-20 sm:mr-[25.5px]">
          <img :src="step === 1 ? f_black : f " class="mr-2" alt="">
          <span class="sm:hidden" :class="{'text-[#002B6B]': step === 1, 'text-[#8A9CC9]': step !== 1}">Registro de datos</span>
          <span class="hidden sm:flex" :class="{'text-[#002B6B]': step === 1, 'text-[#8A9CC9]': step !== 1}">Registro</span>
        </div>
        <div class="flex mr-20 sm:mr-[25.5px]">
          <img :src="step === 2 ? s_black : s" class="mr-2" alt="">
          <span class="sm:hidden" :class="{'text-[#002B6B]': step === 2, 'text-[#8A9CC9]': step !== 2}">Selecciona el plan</span>
          <span class="hidden sm:flex" :class="{'text-[#002B6B]': step === 2, 'text-[#8A9CC9]': step !== 2}">Plan</span>
        </div>
        <div class="flex">
          <img :src="step === 3 ? t_black: t" class="mr-2" alt="">
          <span class="sm:hidden" :class="{'text-[#002B6B]': step === 3, 'text-[#8A9CC9]': step !== 3}">Procede a la compra</span>
          <span class="hidden sm:flex" :class="{'text-[#002B6B]': step === 3, 'text-[#8A9CC9]': step !== 3}">Compra</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'register-bar-component',
  methods:{
    handleRedirect(path) {
      this.$router.push(path);
    },
  },
  computed: {
    step: function() {
      var stepNum = '';
      if (this.$route.name === 'registro') stepNum = 1;
      else if (this.$route.name === 'registro') stepNum = 2;
      else if (this.$route.name === 'registro') stepNum = 3;
      return stepNum;
    }
  }
}
</script>
