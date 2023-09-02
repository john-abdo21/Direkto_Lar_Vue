<!-- <template>


    <input
      :id="id"
      :type="type"
      :value="modelValue"
      @input="emits('update:modelValue', $event.target.value)"
      class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
      :class="{ ...computedInputClass, 'border-red-500': errors[name], 'z-10': errors[email] }"
      :placeholder="placeholder"

    />
    <div class="text-sm text-red-600 mt-10" style="margin-top: 4px;" v-if="errors[name]"> {{errors[name][0]}} </div>

</template>

<script setup>
import { computed } from "@vue/reactivity";

const props = defineProps({
  type: {
    type: String,
    default: "text",
  },
  errors: Object,
  name: String,
  id: String,
  label: String,
  placeholder: String,
  modelValue: String,
  inputClass: [String, Array, Object],
});

const emits = defineEmits(["update:modelValue"]);

const id = computed(
  () => props.id || "id-" + Math.floor(Math.random() * 100000000)
);
const computedInputClass = computed(() => {
  if (typeof props.inputClass === 'string') {
    return {
      [props.inputClass]: true
    }
  } else if (typeof props.inputClass === 'object' && props.inputClass.length !== undefined) {
    return props.inputClass.reduce((accum, val) => {
      accum[val] = true;
      return accum;
    }, {})
  }

  return props.inputClass;
})
</script>

<style></style> -->


<template>
  <div >

    <input
    :id="id"
    :type="type"
    :value="modelValue"
    autocomplete="false"
    @input="$emit('update:modelValue', $event.target.value);"
    class="h-[90%] border border-[#8A9CC9] rounded px-4  w-full  placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
    :class="{ ...computedInputClass, 'border-red-500': errors[name], 'z-10': errors[name] }"
    :placeholder="placeholder"

  />
  <transition name="fade" >
    <nav
      :class="'h-[5%] text-sm text-red-600 mt-10'"
      style="margin-top: 4px;"
      v-if="errors[name]"

    >
      {{errors[name][0]}}
    </nav>
  </transition>

  </div>


</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "text",
    },
    errors: Object,
    name: String,
    id: String,
    label: String,
    placeholder: String,
    modelValue: String,
    inputClass: [String, Array, Object],
  },
  emits: ["update:modelValue"],
  computed: {
    idc() {
      return this.$props.id || "id-" + Math.floor(Math.random() * 100000000);
    },
    computedInputClass() {
      if (typeof this.$props.inputClass === "string") {
        return {
          [this.$props.inputClass]: true,
        };
      } else if (
        typeof this.$props.inputClass === "object" &&
        this.$props.inputClass.length !== undefined
      ) {
        return this.$props.inputClass.reduce((accum, val) => {
          accum[val] = true;
          return accum;
        }, {});
      }

      return this.$props.inputClass;
    },
  },
};
</script>
<style>

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease-in-out;
  }
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  .fade-enter-to,
  .fade-leave-from {
    opacity: 1;
  }
</style>
