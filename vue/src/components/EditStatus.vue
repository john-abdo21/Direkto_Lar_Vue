<template>
  <Modal
    :header="header"
    :modalType="'editStatus'"
    :paragraphs="paragraphs"
  >
      <div v-html="subtitulo"></div>
      <div class="flex w-full flex-col mb-4">
        <span class="text-sm leading-7 mb-2">Estado actual:</span>
        <div class="relative">
          <input
            type="text"
            class="h-[52px] w-full px-4 rounded border border-[#8A9CC9] font-normal text-base text-activeText"
            placeholder="Estado actual"
            :value="estadoActual == 0 ? 'Abierto' : 'Cerrado'"
          />
          <!-- <img src="../assets/ic_arrow-down.svg" class="absolute top-1/2 -translate-y-1/2 right-3 sm:right-4" alt=""> -->
        </div>
      </div>
      <div class="flex w-full flex-col">
        <span class="text-sm leading-7 mb-2">Cambiar a:</span>
        <div class="relative">
          <input
            type="text"
            class="h-[52px] w-full px-4 rounded border border-[#8A9CC9] font-normal text-base text-activeText"
            placeholder="Estado actual"
            :value="estadoActual == 0 ? 'Cerrado' : 'Abierto'"
          />
          <!-- <img src="../assets/ic_arrow-down.svg" class="absolute top-1/2 -translate-y-1/2 right-3 sm:right-4" alt=""> -->
        </div>
      </div>

      <button
        class="h-14 sm:w-full rounded px-8 text-base leading-4 mt-10 bg-orange text-white"
        @click="$emit('editStatusSave', {nuevoestado : (estadoActual == 0 ? 1 : 0) })"
      >
        Guardar cambios
      </button>
  </Modal>
</template>

<script>
import Modal from "./Modal.vue";

export default {
  name: "edit-status-component",
  components: {
    Modal,
  },
  props: {
    header:String,
    subtitulo:String,
    estadoActual: Number,
    paragraphs:String
  },
  data: function () {
    return {
      paragraphs: [],
    };
  },
};
</script>
