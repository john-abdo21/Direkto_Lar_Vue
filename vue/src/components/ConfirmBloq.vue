<template>
  <Modal :header="header" :confirmHeader="confirmHeader" :modalType="'confirm'" :paragraphs="paragraphs">
    <div class="flex justify-between w-full mt-10 sm:flex-col sm:flex-col-reverse">
      <button
        :disabled="bloq"
        class="h-14 w-[48%] sm:w-full rounded border-2 text-base leading-4 "
        :class="{
          'border-2 border-orange text-orange': !bloq,
          'border-2 border-gray text-gray': bloq,
          'w-48': buttons[0].length > 11,
          'px-8': buttons[0].length < 12
        }"
        @click="$emit('confirmStatus',{param: true, bloq:true })"
      >
        {{ buttons[0] }}
      </button>
      <button
        class="h-14 w-[48%] sm:w-full rounded bg-orange text-base leading-4 text-white mb-4"
        :class="{ 'w-48': buttons[1].length > 11, 'px-8': buttons[1].length < 12 }"
        @click="$emit('closeModal', {param: false})"
      >
        {{ buttons[1] }}
      </button>
    </div>
  </Modal>
</template>

<script>
import Modal from "./Modal.vue";

export default {
  name: "success-component",
  props: {
    bloq:Boolean,
    header: String,
    confirmHeader: String,
    paragraphs: Array,
    buttons: Array,
    val: String,
  },
  components: {
    Modal,
  },
};
</script>
