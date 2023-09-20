<template>
  <div tabindex="0" @blur="handleBlur"  class="selectPer text-[0.6rem] w-full  border border-[#8A9CC9] h-full rounded relative" :class="['select-container', { 'is-disabled': disabled }]">
    <div class="selected-option" @click="!disabled && toggleOpciones()">
      <div class="icon-container">
        <FlagIcon :style="{ color: banderaSeleccionada.color, width: '20px', height: '20px' }"/>
      </div>
    </div>
    <div v-if="mostrarOpciones" class="opciones ">
      <div
      class="opcion"
    v-for="(opcion, index) in opciones"
    :key="opcion.id"
    :class="{ 'opcion-seleccionada': index === selectedIndex }"
    @click="!disabled && seleccionarBandera(opcion)"
      >
        <div class="icon-container">
          <FlagIcon :style="{ color: opcion.color, width: '20px', height: '20px' }"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, watch} from 'vue';
import { FlagIcon } from '@heroicons/vue/solid'

export default {
  components: {
    FlagIcon
  },
  props: {
    modelValue: {
      type: Number,
      default: "1"
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue'],  // Indicar que el componente emite este evento
  data() {
    return {
      selectedIndex: 0,
      mostrarOpciones: false,
      opciones: [
        { id: "1", color: '#ccc' },
        { id: "2", color: '#e56b37' },
        { id: "3", color: '#3ac189' },
      ],
      banderaSeleccionada: null
    };
  },
  created() {
    this.banderaSeleccionada = this.opciones.find(opcion => opcion.id === this.modelValue) || this.opciones[0];
  },
  watch: {
    modelValue: {
      immediate: true,
      handler(newValue) {
        this.banderaSeleccionada = this.opciones.find(opcion => opcion.id === newValue) || this.opciones[0];
      }
    }
  },
  methods: {
    handleBlur() {
      this.mostrarOpciones = false;
    },
    handleKeyDown(event) {
      if (this.disabled) return;

      switch (event.key) {
        case 'Enter':
          this.toggleOpciones();
          if (!this.mostrarOpciones) {
            this.seleccionarBandera(this.opciones[this.selectedIndex]);
          }
          break;
        case 'ArrowDown':
          event.preventDefault();
          if (this.selectedIndex < this.opciones.length - 1) {
            this.selectedIndex++;
          }
          break;
        case 'ArrowUp':
          event.preventDefault();
          if (this.selectedIndex > 0) {
            this.selectedIndex--;
          }
          break;
        default:
          return;
      }
    },

    handleClickOutside(event) {
      const el = this.$el;
      if (el && !el.contains(event.target)) {
        this.mostrarOpciones = false;
      }
    },

    toggleOpciones() {
      this.mostrarOpciones = !this.mostrarOpciones;
    },
    seleccionarBandera(opcion) {
      this.selectedIndex = this.opciones.findIndex(o => o.id === opcion.id);  // Actualizar selectedIndex
      this.banderaSeleccionada = opcion;
      this.mostrarOpciones = false;
      this.$emit('update:modelValue', opcion.id);  // Emitir el evento para actualizar modelValue
      this.$emit('change', opcion.id);
    }
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
    this.$el.addEventListener('keydown', this.handleKeyDown);  // Añadir este nuevo listener

  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
    this.$el.removeEventListener('keydown', this.handleKeyDown);  // Eliminar el listener

  }
};
</script>

<style>
/* .select-container {
  position: relative;
  width: 70px;
} */

.selected-option, .opcion {
  /* padding: 2px 4em; */
  /* border: 1px solid #ccc; */
  padding-bottom: 3px;
  border-bottom: 1px solid #ccc;
  cursor: pointer;
}

.opciones {
  position: absolute;
  width: 100%;
  border: 1px solid #858585;
  border-top: none;
  z-index: 999;
  background-color: #fff;
}

.icon-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.opcion-seleccionada {
  background-color: #1867d2;
}


.selectPer {
  text-indent: 1px;
  text-overflow: '';
  /* width: px; */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;

  background: transparent url("../assets/ic_arrow-down.svg") no-repeat right ;
  /* background-position-x: 244px; */
}


.is-disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

</style>
