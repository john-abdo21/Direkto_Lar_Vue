<template>
  <div class="flex items-center justify-between mb-4 sm:mb-6">
    <ul class="text-[#8A9CC9] items-center flex text-xs">
      <li
        v-for="(path, i) in paths"
        :key="i"
        class="last:text-xs flex cursor-pointer text-[8px]"
        :class="{'sm:hidden': i <= paths.length - 3}"
        @click="handleRedirect(urls[i])"
      >
        <img
          v-if="i !== 0"
          src="../../assets/arrow-right.svg"
          alt=""
          class="mx-[5px]"
          :class="{'sm:hidden': i== paths.length - 2}"
        />
        {{ path }}
      </li>
    </ul>
    <span class="text-orange flex text-tinysm items-center" v-if="settingFlag">
      <span class="mr-1 sm:hidden"> Configurar reporte </span>
      <img src="../../assets/setting.svg" alt="" />
    </span>
  </div>
</template>

<script>
export default {
  name: "breadcrumb-component",
  props: {
    paths: Array,
    settingFlag: Boolean,
    urls:Array
  },
  methods: {
    handleRedirect(path) {
      console.log(path+"redirigiendoooo")
      this.$router.push(path);
    },
  }
};
</script>
