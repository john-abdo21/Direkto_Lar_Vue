<template>

  <div id="app" class="tracking-tight font-semibold text-activeText">
    <div v-if="layout === 'login' ">
      <div class="flex sm:pt-40 sm:pt-10 sm:pb-12 sm:px-8 sm:bg-[#F6F8FE] justify-center">
        <router-view />
      </div>
    </div>
    <div v-else-if="layout === 'registro' ">
      <RegisterBar />
      <div  class="flex pt-36 sm:pt-40 sm:pt-10 sm:pb-12 sm:px-8 sm:bg-[#F6F8FE] justify-center">
        <router-view />
      </div>
    </div>

  </div>
</template>

<script>
import RegisterBar from "../RegisterBar.vue";

export default {
  components: {
    RegisterBar,
  },
  computed: {
    layout: function () {
      return this.$route.meta.layout;
    },
  }
}
</script>
