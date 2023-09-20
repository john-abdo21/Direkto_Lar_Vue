<template>
  <tr>
    <td>
      <button
        class="border border-orange rounded h-8 text-orange text-[0.7rem] px-4"
        @click="ConstraintAnalysis(row.codProyecto)"
        ref="Boton para ingresar al administrador de restricciones"
      >
        Ingresar
      </button>
    </td>
    <td>
      <span class="flex items-center">
        <span class="mr-2 text-[0.6rem]" :class="{'status_open': row.codEstado == 0 , 'status_closed': row.codEstado > 0    }">{{ row.codEstado == 0 ? `Abierto` : `Cerrado` }}</span>
        <!-- <span class="mr-2">abierto</span> -->
        <img src="../assets/edit.svg" alt="" class="cursor-pointer" @click="openModal({param: 'editStatus', id: row.codProyecto})" />
      </span>
    </td>
    <td class="text-[0.6rem]">{{ row.desnombreproyecto }}</td>
    <td>
      <p class="flex flex-col">
        <span class="text-[0.6rem]">No retrasadas: {{ row.indNoRetrasados }}</span>
        <span class="text-[0.6rem]" v-if="row.indRetrasados == 0">Retrasadas: {{ row.indRetrasados }}</span>
        <span class="text-[0.6rem]" v-if="row.indRetrasados > 0" style="color: red; font-weight: bold;">Retrasadas: {{ row.indRetrasados }}
          <img src="../assets/alert.svg" alt="Alert icon" class="inline-block w-4 h-4 ml-2 text-red-500">
        </span>

      </p>
    </td>
    <td>
      <span class="flex flex-col text-[0.6rem]">
        <template v-for="(equipment, index) in row.integrantesProy" :key="index">

          <span  v-if ="row.integrantes.includes(equipment.codProyIntegrante)">
           {{ equipment.desCorreo }}
          </span>

        </template>

      </span>
    </td>
    <td>
      <button
        v-if="row.isInvitado == 0 && row.codEstado == 0"
        class="bg-[#DCE4F9] w-6 h-6 rounded-md justify-center flex items-center"
        @click="$emit('selectUserFunc', {codProyecto:row.codProyecto, index:index}); openModal({param: 'selectusers'})"
        v-click-outside="hide"
      >
        <img
          src="../assets/tooltip-person-add-active.svg"
          :class="{ 'content-pointsActive': row.isTooltip }"
          alt=""
        />
      </button>
    </td>
  </tr>
</template>


<script>
import { emit } from "process";
import ClickOutside from "vue-click-outside";
import Tooltip from "./Tooltip.vue";
import { useRouter } from "vue-router";

export default {
  name: "custome-table-row",
  components: {
    Tooltip,
},
  props: {
    index: Number,
    row: Object,
    users: Array,
  },
  data: function () {
    return {
      isOpen: false,
      modalName: '',
      // section: row.integrantes
    };
  },
  computed:{

  },
  methods: {

    handleClick: function () {
      this.isOpen = !this.isOpen;
    },
    hide: function () {
      this.isOpen = false;
    },
    openModal: function(payload) {
      this.$emit('openModal', {param: payload.param, id: payload.id, index:this.index });
    },
    ConstraintAnalysis: function(id) {

      this.$emit('openConstraintPage', {id: this.row.codProyecto, nameProy: this.row.desnombreproyecto})

    }
  },
  directives: {
    ClickOutside,
  },

};
</script>
<style>

.status_open{

background-color: #2f6fe3;
color: #fff;
padding: 5px;
border-radius: 1em;

}

.status_closed{

background-color: #bf2323;
color: #fff;
padding: 5px;
border-radius: 1em;

}
</style>
