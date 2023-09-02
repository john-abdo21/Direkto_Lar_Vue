<template>
  <Modal :header="'Agregar Nueva Restricción'" :modalType="'addRow'" :paragraphs="paragraphs">
      <span class="text-sm text-center leading-6 mb-2 w-full"> Llene por lo menos un dato valido. </span>

      <div class = "w-[70%] sm:w-full" :class="{'hidden': hideCols.indexOf('exercise') > -1}"> <input placeholder="Ingresar Actividad" :disabled="!statusRestriction" v-model="row.desActividad" type="text" class="text-xs w-full border border-[#8A9CC9] px-2 h-8 mb-2 rounded"  /> </div>
      <div class = "w-[70%] sm:w-full" :class="{'dden': hideCols.indexOf('restriction') > -1}"> <input placeholder="Ingresar Restricción" :disabled="!statusRestriction"  v-model="row.desRestriccion" type="text" class="text-xs w-full border border-[#8A9CC9] px-2 h-8 mb-2 rounded"   /> </div>
      <div class = "w-[70%] sm:w-full" :class="{'hidden': hideCols.indexOf('restrictionType') > -1}">
            <select
            v-if="statusRestriction"
            v-model="row.codTipoRestriccion"
            placeholder="Seleccionar Tipo Restricción"
            class="text-xs w-full border border-[#8A9CC9] px-2 h-8 mb-2  rounded  selectPer "
          >

          <option
          v-for="item in getOption()" v-bind:key="item.value" v-bind:value = "item.value">
            {{ item.name }}
          </option>

          </select>


      </div>
      <div class = "w-[70%] sm:w-full" :class="{'hidden': hideCols.indexOf('date_required') > -1}">

        <input
            v-if="statusRestriction"
            :disabled="!statusRestriction"
            v-model="row.dayFechaRequerida"
            placeholder="Fecha Requerida"
            type="date"
            class="text-xs w-full border border-[#8A9CC9] px-2 h-8 mb-2  rounded"
        />

      </div>


      <div class = "w-[70%] sm:w-full" :class="{'hidden': hideCols.indexOf('date_conciliad') > -1}">
        <input
          v-if="statusRestriction"
          :disabled="!statusRestriction"
          v-model="row.dayFechaConciliada"
          placeholder="Fecha Conciliada"
          type="date"
          class="text-xs w-full border border-[#8A9CC9] px-2 h-8 mb-2 rounded"
        />

      </div>
      <div class = "w-[70%] sm:w-full"  :class="{'hidden': hideCols.indexOf('responsible') > -1}">
            <select v-if="statusRestriction && getOptionResponsables().length > 0"
              placeholder="Seleccionar Responsable"
              v-model="row.idUsuarioResponsable"
              class="text-xs w-full border border-[#8A9CC9] px-2 h-8 mb-2 rounded selectPer"
            >

            <option
            v-for="item in getOptionResponsables()" v-bind:key="item.value" v-bind:value = "item.value">
              {{ item.name }}
            </option>

            </select>
            <input :disabled="(!statusRestriction || getOptionResponsables().length == 0)" v-if="!statusRestriction || getOptionResponsables().length == 0" :placeholder="(getOptionResponsables().length == 0 ? 'No se puede elegir responsables': '')" :value="row.desUsuarioResponsable" type="text" class="w-full border border-[#8A9CC9] px-2 text-xs h-8 mb-2 rounded"   />

      </div>

      <div class = "w-[70%] sm:w-full"  :class="{'hidden': hideCols.indexOf('condition') > -1}">
              <select v-if="statusRestriction"
              v-model="row.codEstadoActividad"
              class="text-xs w-full border border-[#8A9CC9] px-2 h-8 mb-2 rounded selectPer"
              >

              <option
              v-for="item in getOptionEstados()" v-bind:key="item.value" v-bind:value = "item.value">
                {{ item.name }}
              </option>

            </select>

      </div>


      <button
        class="h-14 w-[70%] sm:w-full rounded px-8 text-base leading-4  bg-orange mt-4 text-white"
        @click="$emit('saveRowfromForm', { row: row , frontId: frontId , phaseId:phaseId})"
      >
        Guardar Restricción
      </button>
  </Modal>
</template>

<script>
import Modal from "./Modal.vue";

export default {
  name: "add-row-component",
  components: {
    Modal,
  },
  props: {
    hideCols: Array,
    frontId: Number,
    phaseId: Number,
    statusRestriction:Boolean,

  },
  data: function () {
    return {
      paragraphs: [],
      exercise: '',
      row :   {
            codAnaResActividad: 0,
            codEstadoActividad: 1,
            codTipoRestriccion: "",
            dayFechaConciliada: "",
            dayFechaRequerida: "",
            desActividad: "",
            desAreaResponsable:"",
            desEstadoActividad:1,
            desRestriccion:"",
            desTipoRestriccion:"",
            desUsuarioResponsable:"",
            idUsuarioResponsable:"",
            isEnabled:false,
            isupdate:false,
            numOrden:0
          }
    };
  },
  methods: {

    getOption: function () {
      const options = [];
      this.$store.state.Restrictionlist.map((row) => {
        const temp = {};
        temp["value"] = row.value;
        temp["name"] = row.name;
        temp["carea"] = '';
        options.push(temp);
      });
      this.options = options;
      return options
    },

    getOptionResponsables: function () {
      const options = [];
      this.$store.state.anaDataMembers.map((row) => {
        const temp = {};
        temp["value"] = row.codProyIntegrante;
        temp["name"]  = row.desProyIntegrante;
        temp["carea"] = row.codArea;
        options.push(temp);
      });
      this.options = options;
      return options
    },

    getOptionEstados: function () {
      const options = [];
      this.$store.state.anaEstado.map((row) => {
        const temp = {};
        temp["value"] = row.codEstado;
        temp["name"]  = row.desEstado;
        temp["carea"] = "";
        options.push(temp);
      });
      this.options = options;
      return options
    },

  }
};
</script>
