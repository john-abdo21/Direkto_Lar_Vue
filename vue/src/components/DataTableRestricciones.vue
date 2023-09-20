<template>
  <div>

  <!-- <nav @click="validamosArray"> click aqui para ver </nav> -->
  <table class="w-full tbldownload">
    <thead class="bg-[#DCE4F9]">
      <tr :class="{ 'h-10': tableType === 'scroll' }">
        <th
          v-if="tableType === 'scroll'"
          class="pl-2 pr-10 pt-2 pb-3 rounded-tl-[7px]  absolute left-0 bg-[#DCE4F9] h-10 flex"
        >
        <span>

        </span>

          <img
            v-if="!mfullScreen"
            src="../assets/maximizar-icon.svg"
            class="cursor-pointer w-6 h-6"
            @click="$emit('fullScreen', {frontId: frontId, phaseId: phaseId, frontName: frontName, phaseName: phaseName, restrictions:restrictions, idxFront:idxFront, idxPhase:idxPhase })"
            alt=""
          />
          <img
            v-if="mfullScreen"
            src="../assets/minimizar-icon.svg"
            class="cursor-pointer"
            @click="$emit('fullScreen', {frontId: frontId, phaseId: phaseId, frontName: frontName, phaseName: phaseName, restrictions:restrictions, idxFront:idxFront, idxPhase:idxPhase})"
            alt=""
          />
          <img
            v-if="ResizeActually < 800 || restrictions.length == 0 "
            src="../assets/add-row3.svg"
            class="cursor-pointer w-[32px] ml-[4px]"
            @click="$emit('addRowData', {frontId: frontId, phaseId: phaseId, frontName: frontName, phaseName: phaseName})"
            alt=""
          />
        </th>
        <th
          v-for="(col, key) in cols"
          :key="key"
          class="text-left text-[0.6rem] px-3 last:rounded-tr-[7px] [&:nth-child(2)]:w-[90px]"
          :class="{
            hidden: tableType === 'scroll' && hideCols.indexOf(key) > -1,
            '[&:nth-child(2)]:w-28 [&:nth-child(5)]:w-44 ':
              tableType === 'scroll',
            'first:rounded-tl-[7px]': tableType !== 'scroll',
          }"
        >
          {{ col }}

        </th>
      </tr>
    </thead>

      <draggable
        v-model="restrictions"
        tag="tbody"
        item-key="name"
        v-bind="dragOptions"
        @change="movimientoRow"
        :disabled="mstatusDraggable"

      >
      <template #item="{ element , index}">
            <DataTableRestriccionesRow2
            :rolProyecto = "mrolProyecto"
            :restriction_data      = "element"
            :index="index"
            :hideCols = "mhideCols"
            :statusRestriction="mstatusRestriction"
            :listindex="[midxFront,midxPhase,index]"
            :listIds  = "[frontId, phaseId]"
            :ResizeActually = "ResizeActually"
            :validarUpd = "mvalidarUpd"
            :frontName = "frontName"
            :phaseName = "phaseName"
            :solicitanteActual = "msolicitanteActual"
            @openModal = "openModal"
            @updateRow = "updateRow"
            @RegistrarCambioRow = "RegistrarCambioRow"
            @updalidarUpd = "updalidarUpd"
            >
          </DataTableRestriccionesRow2>
      </template>

      </draggable>

  </table>
  </div>
  <rawDisplayer class="col-3" :value="restrictions" title="List" />
</template>


<script>
// import ClickOutside from "vue-click-outside";
import DataTableRestriccionesRow2 from "./DataTableRestriccionesRow2.vue";
import draggable from "vuedraggable";
import "../assets/css/reset.css"

export default {
  name: "data-table-restrictions",
  components: {
    draggable,
    DataTableRestriccionesRow2
  },
  props: {
    rolProyecto:Number,
    fullScreen:Number,
    tableType: String,

    cols: Object,
    restrictions: Array,
    hideCols: Array,
    frontId: Number,
    phaseId: Number,
    frontName: String,
    phaseName: String,

    statusRestriction:Boolean,
    statusDraggable:Boolean,
    idxFront:Number,
    idxPhase:Number,

    validarUpd: Boolean,
    ResizeActually: Number,
    solicitanteActual:String

    // rowsCant:Number
  },
  computed:{

      mrolProyecto(){
        return this.rolProyecto
      },
      msolicitanteActual(){
        return this.solicitanteActual
      },
      mfullScreen(){
        console.log("verificamos el estado :: "+this.fullScreen)
        return this.fullScreen
      },
      mhideCols: function() {
        return this.hideCols
      },
      mstatusRestriction: function() {
        return this.statusRestriction
      },
      mstatusDraggable: function() {
        return this.rolProyecto != 3 ? true: this.statusDraggable
      },
      // mRestrictionData: {
      //       get () {
      //           return this.name
      //       },
      //       set (idx) {
      //           this.name = new_name
      //       }
      // },
      midxFront : function() {
        return this.idxFront

      },
      midxPhase : function() {
        return this.idxPhase

      },
      dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };

      },
      mvalidarUpd : function() {
        return this.validarUpd
      },

  },
  methods: {
    validamosArray: function () {

      console.log(this.restrictions)

    },
    // mRestrictionData : function (idx) {
    //   return  this.$store.state.whiteproject_rows[this.midxFront]['listaFase'][this.midxPhase]['listaRestricciones'][idx] //this.restrictions[idx]
    // },
    movimientoRow : function (dataRow) {
      let enviar              = []
      let lista_restricciones = this.restrictions
      console.log(lista_restricciones[0])
      for (var i = 0; i < lista_restricciones.length; i++) {
        let data = {index: i , codAnaResActividad: lista_restricciones[i]['codAnaResActividad'] }
        enviar.push(data)
      }

      console.log(enviar)

      // let data = {
      //   codana     : dataRow.moved.element.codAnaResActividad,
      //   orden      : dataRow.moved.element.numOrden,
      //   idxnuevo   : dataRow.moved.newIndex,
      //   idxantiguo : dataRow.moved.oldIndex,
      //   idxfront   : this.idxFront,
      //   idxphase   : this.idxPhase,
      // }

       this.$emit('movimientoRow', enviar);
      //  console.log(dataRow.moved.element.codAnaResActividad)

    },
    updateRow: function (updRow) {
      this.$emit('updateRow', updRow);
    },
    RegistrarCambioRow: function (cambio){
      this.$emit('RegistrarCambioRow', cambio);
    },
    openModal: function (data){
      this.$emit('openModal', data);
    },
    updalidarUpd: function (data){

      this.$emit('updalidarUpd', data);

    }






  },

};
</script>

<style>
tbody tr td {
  font-weight: 500;
  font-size: 12px;
  color: #212530;
  padding: 3px 3px;
}
</style>
