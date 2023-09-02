

<template>



<div class=" w-[1000px] mb-4">

  <div class="flex justify-start" id="indicadores">




  <div class="flex justify-between space-x-4">
    <!-- Primer bloque - Indicador principal en Card -->
    <div class="flex-1 bg-blue-500 text-white rounded-lg p-3 shadow-md">
      <h2 class="text-base">Avance de restricciones</h2>
      <h3 class="text-md">50%</h3>
      <div class="h-2 bg-white mt-2">
        <div class="h-full bg-orange-500" style="width: 50%;"></div>
      </div>
    </div>

    <!-- Segundo bloque - 2 indicadores en Barra -->
    <div class="flex-1 flex flex-col space-y-2 w-[9em]">
      <div>
        <span class="mr-2 text-xs">Áreas cubiertas:</span>
        <div class="h-2 w-full bg-gray-300">
          <div class="h-full bg-green-500" style="width: 30%;"></div>
        </div>
        <span class="ml-2 text-md">30%</span>
      </div>

      <div>
        <span class="mr-2 text-xs">Acabados:</span>
        <div class="h-2 w-full bg-gray-300">
          <div class="h-full bg-red-500" style="width: 40%;"></div>
        </div>
        <span class="ml-2 text-md">40%</span>
      </div>
    </div>

    <!-- Tercer bloque - 2 indicadores en Barra -->
    <div class="flex-1 flex flex-col space-y-2 w-[9em]">
      <!-- Aquí puedes agregar los dos indicadores adicionales siguiendo el formato anterior -->
    </div>
  </div>






  </div>

  <br>

  <div class=" flex  justify-between  sm:flex-col">
    <!-- Sección izquierda -->
    <div class=" flex w-[50%] sm:w-full">
      <button class="bg-white w-[17%]  sm:w-[25%] h-[40px] text-xs hover:bg-gray-100 px-2 py-1 border border-orange rounded shadow text-orange" @mouseover="hoverEffect" @mouseleave="removeHoverEffect">
          <i class="fas fa-plus-circle"></i> asdf
      </button>

      <button class="bg-white w-[17%] sm:w-[25%] h-[40px] text-xs hover:bg-gray-100 px-2 py-1 border border-orange rounded shadow text-orange" @mouseover="hoverEffect" @mouseleave="removeHoverEffect">
        <i class="fas fa-plus-circle"></i> Agregar fase
      </button>

      <button class="bg-white w-[17%] sm:w-[25%] h-[40px] text-xs hover:bg-gray-100 px-2 py-1 border border-orange rounded shadow text-orange" @mouseover="hoverEffect" @mouseleave="removeHoverEffect">
        <i class="fas fa-trash"></i> Eliminar
      </button>

      <button class="bg-white w-[17%] sm:w-[25%] h-[40px] text-xs hover:bg-gray-100 px-2 py-1 border border-orange rounded shadow text-orange relative" @mouseover="hoverEffect" @mouseleave="removeHoverEffect">
        <i class="fas fa-envelope"></i> Enviar Correos
        <span class="badge absolute top-0 right-0 h-5 w-5 bg-red-500 rounded-full text-white text-center text-xs" >2</span>
      </button>

    </div>

    <div class=" flex  w-[50%] sm:w-full  justify-end">
      <div class=" flex flex-col w-[60%] sm:w-full space-x-1">
        <div class="flex-1 flex justify-end text-xs ">
          <button class="px-2 py-1 border border-gray-400 rounded hover:bg-gray-100 w-[35%] h-[42px]">
            <i class="fas fa-file-download"></i> Descargar Plantilla
          </button>
          <button class="px-2 py-1 border border-gray-400 rounded hover:bg-gray-100 w-[32%] h-[42px]">
            <i class="fas fa-file-upload"></i> Subir Plantilla
          </button>
          <button class="px-2 py-1 border border-gray-400 rounded hover:bg-gray-100 w-[25%] h-[42px]">
            <i class="fas fa-file-download"></i> Reporte
          </button>
        </div>
      </div>

      <div class=" flex flex-col w-[40%] sm:w-full ">
        <div class="flex-1 relative">
          <i class="fas fa-filter absolute right-3 top-2 text-orange cursor-pointer" @click="toggleFilterOptions"></i>

          <input type="text" v-model="search" @input="filterOptions" placeholder="Filtro .. " class="h-[42px] px-2 py-1 border border-[#8A9CC9] rounded text-xs w-full focus:outline-none focus:ring-2 focus:ring-blue-200 ">

          <!-- Lista de filtros seleccionados -->
          <div v-for="(filter, index) in selectedFilters" :key="index" class="mt-1 px-2 py-1 border border-gray-300 rounded flex justify-between">
            <span>{{ filter }}</span>
            <i class="fas fa-times cursor-pointer" @click="removeFilter(index)"></i>
          </div>

          <transition name="fade">

          <div class="absolute left-0 mt-1 w-full bg-white rounded shadow-lg" v-if="showOptions" ref="dropdown">
            <div v-for="(option, index) in visibleOptions" :key="index" class="px-2 py-1 cursor-pointer mb-2 shadow-sm"  @click="optionClicked(option)">
              <div class="font-normal flex justify-between">
                <span>{{option.name}}</span>
                <span v-if="option.subOptions">
                <i :class="option.showSubOptions ? 'fas fa-chevron-down' : 'fas fa-chevron-right'"></i>
                </span>
              </div>
              <div v-if="option.subOptions && option.showSubOptions" class="pl-2">
                <div v-for="(subOption, subIndex) in option.subOptions" :key="subIndex" class="px-2 py-1 cursor-pointer hover:bg-blue-100 font-normal" @click.stop="selectOption(option, subOption)">
                  {{subOption}}
                </div>
              </div>
            </div>
            <div v-if="!anyResults" class="px-2 py-1 text-xs">No se tienen resultado de la búsqueda.</div>
          </div>

        </transition>
        </div>
      </div>
    </div>
  </div>


</div>
</template>

<script>
  import '@fortawesome/fontawesome-free/css/all.css'

  export default {
    data() {
      return {
        search: '',
        showOptions: false,
        options: [
          { name: 'Reponsable', visible: false ,subOptions: [], showSubOptions: false },
          { name: 'Solicitante', visible: false, subOptions: [], showSubOptions: false },
          { name: 'Vencimiento', visible: false , subOptions: ['con Retraso',], showSubOptions: false },
          { name: 'Tipo de Restricción', visible: false ,subOptions: [], showSubOptions: false }
        ],
        anyResults: false,
        selectedFilters: []
      }
    },
    computed: {
      visibleOptions() {
        return this.options.filter(option => option.visible);
      }
    },
    mounted() {
      document.addEventListener('click', this.outsideClickListener);
    },
    beforeDestroy() {
      document.removeEventListener('click', this.outsideClickListener);
    },
    methods: {

    toggleFilterOptions() {
        if (this.search.length === 0) {
            this.showOptions = !this.showOptions;
            if (this.showOptions) {
                this.options.forEach(option => option.visible = true);
                this.anyResults = true; // Asegúrate de que cualquier resultado sea verdadero para evitar mostrar el mensaje de sin resultados
            }
        } else {
            this.filterOptions();
        }
    },

    toggleOptions() {
        this.showOptions = !this.showOptions;
    },
    outsideClickListener(event) {
        const dropdownElem = this.$refs.dropdown;
        if (dropdownElem && !dropdownElem.contains(event.target) && event.target.className.indexOf('fa-filter') == -1) {
            this.showOptions = false;
        }
    },
    filterOptions() {
        this.showOptions = this.search.length > 0 || this.toggleButtonClicked;
        this.anyResults = false;
        for (let option of this.options) {
            option.visible = false;
            if (option.subOptions) {
                // Si la opción tiene subopciones, también se deben revisar
                for (let subOption of option.subOptions) {
                    if (subOption.toLowerCase().includes(this.search.toLowerCase())) {
                        option.visible = true;
                        option.showSubOptions = true; // Mostrar automáticamente las subopciones
                        this.anyResults = true;
                        break;  // Si encuentra una coincidencia, no necesita buscar más en las subopciones
                    }
                }
            } else {
                option.visible = option.name.toLowerCase().includes(this.search.toLowerCase());
                if (option.visible) {
                    this.anyResults = true;
                }
            }
        }
    },

    optionClicked(option) {
        if (option.subOptions) {
            // Solo altera showSubOptions si no hay búsqueda, de lo contrario deja las subopciones abiertas
            if (this.search === '') {
                option.showSubOptions = !option.showSubOptions;
            }
        } else {
            this.selectOption(option);
        }
    },
    selectOption(option, subOption) {
        const selected = subOption || option.name;
        this.selectedFilters = [selected];  // Reemplazamos todos los filtros existentes con el nuevo
        this.showOptions = false;
        this.search = '';
    },
    removeFilter(index) {
        this.selectedFilters.splice(index, 1);
      },


    }
  }
</script>
<style scoped>
.badge {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2px;
  font-size: 10px;
  min-width: 18px;
  height: 18px;
  color: #fff;
  background-color: #eb5d00;
  border-radius: 50%;
  position: absolute;
  top: -5px;
  right: -5px;
  box-shadow: 0 0 1px #333;
}

</style>
