<template>
<div class="dropdown relative mt-14  z-50 ">


    <ul class="
      dropdown-menu
      absolute
      w-60
      bg-white
      float-left
      py-2
      list-none
      text-left
      rounded-lg
      shadow-lg
      mt-1

      m-0
      bg-clip-padding
      border-none
      left-auto
      right-0
    " aria-labelledby="dropdownMenuButton1">
      <li>
        <a class="
          dropdown-item
          text-sm
          py-2
          px-4
          font-normal
          block
          w-full
          whitespace-nowrap
          bg-transparent
          text-gray-700
          hover:bg-gray-100"
         href="#"
         @click="handleClick('personData')"
        >Información personal</a>
      </li>
      <div class="py-1">
        <a href="#"
        class="text-sm hover:bg-gray-100 text-gray-700 block px-4 py-2"
        @click="handleClick('salir')"
        >Salir</a>
    </div>
    </ul>

  </div>
</template>

<script>
export default {
  name: "table-tooltip-person-component",
  props: {
    id: Number,
  },
  methods: {
    handleClick: function (param) {
      this.$emit("selecPersonInfo", { param: param });
    },
  },
};
</script>
