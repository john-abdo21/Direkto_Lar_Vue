<template>
<div>

    <Breadcrumb
      :paths="['Inicio', 'Análisis de restricciones', 'Proyecto Blanco']"
       :settingFlag="true"
    />
    <div class="flex sm:flex-col justify-between mb-12 sm:mb-10">
      <span class="items-start text-2xl text-activeText sm:mb-4"
        >Restricciones</span
      >
      <div class="flex sm:flex-col h-[52px] sm:h-auto">
        <div class="relative mr-4 sm:mr-0 sm:mb-4">
          <input
            type="text"
            name="personalize"
            id="personalize"
            class="h-[52px] px-4 border border-[#8A9CC9] rounded text-base sm:w-full"
            placeholder="Personalizar"
          />
          <img
            src="../../assets/ic_arrow-down.svg"
            alt=""
            class="absolute flex transition top-1/2 right-4 -translate-y-1/2 cursor-pointer"
            :class="{
              'rotate-180': personalizeOpen,
              'rotate-0': !personalizeOpen,
            }"
            @click="handleClick('personalize')"
          />
        </div>
        <div class="relative">
          <input
            type="text"
            name="filter"
            id="filter"
            v-model="filterName"
            class="h-[52px] px-4 border border-[#8A9CC9] rounded text-base sm:w-full"
            placeholder="Filtrar"
          />
          <img
            src="../../assets/ic_arrow-down.svg"
            alt=""
            class="absolute flex transition top-1/2 right-4 -translate-y-1/2 cursor-pointer"
            :class="{ 'rotate-180': filterOpen, 'rotate-0': !filterOpen }"
            @click="handleClick('filter')"
          />
          <SelectOption :selType="'tree'" @selected="selFilterOpt" :options="options" v-if="filterOpen" />
        </div>
      </div>
    </div>
    <div class="flex sm:flex-col mb-12 justify-between">
      <div class="flex sm:flex-col sm:mb-2" v-if="!fullScreen">
        <button
          class="w-[150px] sm:w-full h-8 px-4 flex justify-between items-center border-2 border-orange rounded mr-4 sm:mb-2"
          @click="openModal('addFront')"
        >
          <span class="text-xs text-orange">Agregar frente</span>
          <img src="../../assets/btn-plus.svg" alt="" />
        </button>
        <button
          class="w-[140px] sm:w-full h-8 px-4 flex justify-between items-center border-2 border-orange rounded mr-4 sm:mb-2"
          @click="openModal('addPhase')"
        >
          <span class="text-xs text-orange">Agregar Fase</span>
          <img src="../../assets/btn-plus.svg" alt="" />
        </button>
        <button
          class="w-[110px] sm:w-full h-8 px-4 flex justify-between items-center border-2 rounded"
          :disabled="isDisabled"
          :class="{
            'border-orange': !isDisabled,
            'border-[#DCE4F9]': isDisabled,
          }"
          @click="openModal('deleteFront')"
        >
          <span
            class="text-xs"
            :class="{
              'text-orange': !isDisabled,
              'text-[#8A9CC9]': isDisabled,
            }"
          >
            Eliminar
          </span>
          <img
            src="../../assets/tooltip-delete.svg"
            :class="{ 'content-deleteActive': !isDisabled }"
            alt=""
          />
        </button>
      </div>
      <div class="flex sm:mb-2" v-if="fullScreen">
        <ul class="text-[#8A9CC9] items-center flex text-xs">
          <li class="flex">
            {{ frontName }}
          </li>
          <li class="text-[#616E8E] flex">
            <img
              src="../../assets/arrow-right.svg"
              alt=""
              class="mx-[5px]"
            />
            {{ phaseName }}
          </li>
        </ul>
      </div>
      <div class="flex sm:flex-wrap" v-if="!isDisabled">
        <div class="flex items-center mr-4 cursor-pointer sm:mb-2">
          <span class="text-xs text-[#002B6B] mr-1">Descargar excel</span>
          <img src="../../assets/download.svg" alt="" />
        </div>
        <div class="flex items-center mr-4 cursor-pointer sm:mb-2">
          <span class="text-xs text-[#002B6B] mr-1">Importar excel</span>
          <img src="../../assets/upload.svg" alt="" />
        </div>
        <div class="flex items-center cursor-pointer sm:mb-2" @click="openModal('downloadReport')">
          <span class="text-xs text-[#002B6B] mr-1">Descargar reporte</span>
          <img src="../../assets/download.svg" alt="" />
        </div>
      </div>
    </div>
    <div class="flex flex-col">
      <!-- <div v-if="!fullScreen"> -->
      <div>

        <div v-for="(frente, index) in rows" :key="index">
          <hr v-if="index !== 0" class="mb-6 bg-[#D0D9F1]" />
          <div
            class="flex justify-between items-cener mb-6 sm:w-full cursor-pointer"
            @click="toggleOpen(frente.desFrente)"
          >
            <span class="text-xl text-activeText">{{ frente.desFrente }}</span>
            <img
              src="../../assets/ic_arrow-down.svg"
              alt=""
              class="flex transition"
              :class="{ 'rotate-180': frente.isOpen, 'rotate-0': !frente.isOpen }"
            />
          </div>
          <div class="flex flex-col mt-2 mb-8 pl-8 sm:pl-4" v-if="frente.isOpen">
            <div
              class="flex flex-col mb-4"
              v-for="(fase, index) in frente.listaFase"
              :key="index"
            >
              <div>
                <span class="text-[0.7rem] leading-7 text-activeText shrink-0">
                  {{ fase.desFase }}
                </span>
                <span
                  class="ml-[100px] sm:ml-8 text-[0.7rem] leading-7 text-activeText"
                >
                  <!-- No retrasadas: {{ item.notDelayed }}
                  <span class="sm:hidden">/</span> Retrasadas:
                  {{ item.delayed }} -->
                </span>
              </div>

              <div class="mt-8">

                <div class="flex sm:flex-col justify-between sm:mb-10">
                    <div class="flex mb-6 items-start cursor-pointer" @click="openModal({param: 'toggleColumn', frontId: frente.id, phaseId: fase.id})">
                      <img
                        src="../../assets/visibility.svg"
                        alt=""
                        class="mr-1"
                      />
                      <span class="text-xs text-[#002B6B]"
                        >Ocultar / mostrar columnas</span
                      >
                    </div>

                    <div class="flex items-end mb-6 cursor-pointer ">
                      <img
                        src="../../assets/upload.svg"
                        alt=""
                        class="mr-1"
                      />

                      <span class="text-xs text-[#002B6B]"
                        > Registros Actualizados</span
                      >

                    </div>
                </div>
                <!-- <div v-if="item.tableData.length > 0" class="mt-8"> -->


                <div class="outer relative border border-[#D0D9F1] rounded-lg before:w-24 before:absolute before:h-full before:shadow-tooltip">
                  <div class="inner overflow-scroll overflow-hidden ml-24">
                    <DataTableRestricciones
                      :tableType="'scroll'"
                      :cols="headerCols"
                      :restrictions="fase.listaRestricciones"
                      :hideCols="[]"
                      :frontId="frente.codFrente"
                      :phaseId="fase.codFase"
                      :frontName="frente.desFrente"
                      :phaseName="fase.desFase"
                      class="table-fixed"
                      @fullScreen="toggleFullScreen"
                      @addRowModal="addRowModal"

                    >
                      <template v-slot="tr">
                        <DataTableRestriccionesRow  :restriction_data="tr" :isupdate="tr.isupdate" :isEnabled="tr.isEnabled" :frontId="frente.codFrente" :phaseId="fase.codFase" :hideCols="[]" @openModal="openModal" @updateRow  ="updateRow"   />
                      </template>

                    </DataTableRestricciones>
                  </div>
                </div>



              </div>
            </div>
          </div>
        </div>


      </div>
    </div>





    <AddFront :rows="rows" v-if="modalName === 'addFront'" @closeModal="closeModal" @addFront="addFront" />
    <AddPhase :rows="rows" v-if="modalName === 'addPhase'" @closeModal="closeModal" @addPhase="addPhase" />
    <ToggleColumn :hideCols="hideCols" v-if="modalName == 'toggleColumn'" @closeModal="closeModal" @setColumnsStatus="setColumnsStatus" />

</div>
</template>

<script>
import Breadcrumb from "../../components/Layout/Breadcrumb.vue";


import AddFront from "../../components/AddFront.vue";
import AddPhase from "../../components/AddPhase.vue";
import DataTableRestricciones from "../../components/DataTableRestricciones.vue";
import DataTableRestriccionesRow from "../../components/DataTableRestriccionesRow.vue";
// import RestrictionPerson from "../../components/RestrictionPerson.vue";
// import DeleteFront from "../../components/DeleteFront.vue";
import ToggleColumn from "../../components/ToggleColumn.vue";
import AddRow from "../../components/AddRow.vue";
// import DeleteRow from "../../components/DeleteRow.vue";
// import DownloadReport from "../../components/DownloadReport.vue";
import SelectOption from "../../components/SelectOption.vue";
// import Confirm from "../../components/Confirm.vue";
import store from "../../store";
export default {
  name: "white-project-component",
  components: {
    Breadcrumb,
    AddFront,
    AddPhase,
    DataTableRestricciones,
    DataTableRestriccionesRow,
    AddRow,
    // ScrollTableRow,
    // RestrictionPerson,
    // DeleteFront,
    // ToggleColumn,

    // DeleteRow,
    // DownloadReport,
    SelectOption,

    // Confirm
},
  data: function () {
    return {
      modalName: "",
      personalizeOpen: false,
      filterOpen: false,
      filterId: "",
      filterName: "",
      frontId: '',
      frontName: '',
      phaseId: '',
      exercise: '',
      phaseName: '',
      fullScreen: false,
      options: [],
      headerCols: {
        plus: "",
        exercise: "Descrip. actividad",
        restriction: "Descrip. restricción",
        restrictionType: "Tipo de restricción",
        date_required: "Fecha requerida",
        responsible: "Responsable",
        responsible_area: "Área responsable",
        condition: "Estado",
        applicant: "Solicitante ",
      },
      restrictions:[
        {
          codFrente:1,
          desFrente:"Frente 001",
          isOpen:true,
          listaFase:[
             {
              codFase:1,
              desFase:"Fase 001",
              listaRestricciones:
              [
                 {
                  codAnaResActividad:1,
                  desActividad:"Activi 1",
                  desRestriccion:" restriccion 1",
                  codTipoRestriccion: 1,
                  desTipoRestriccion: "ARQUITECTURA"

                 },
                 {
                  codAnaResActividad:2,
                  desActividad:"Actividad 2",
                  desRestriccion:" restriccion 2",
                  codTipoRestriccion: 1,
                  desTipoRestriccion: "ARQUITECTURA"

                 }
              ]
             },
             {
              codFase:2,
              desFase:"Fase 002",
              listaRestricciones:
              [

                 {
                  codAnaResActividad:3,
                  desActividad:"Actividad 3",
                  desRestriccion:" restriccion 3",
                  codTipoRestriccion: 2

                 },

              ]
             },

          ]
        },
        {
          codFrente:2,
          desFrente:"Frente 002",
          isOpen:true,
          listaFase:[

          ]
        }

      ]
    };
  },
  methods: {
    handleClick: function (id) {
      id === "personalize" && (this.personalizeOpen = !this.personalizeOpen);
      id === "filter" && (this.filterOpen = !this.filterOpen);
    },
    toggleOpen: function (param) {
      this.rows.map((row) => {
        if (row.name === param) {
          row.isOpen = !row.isOpen;
        }
      });
    },
    toggleFullScreen: function (payload) {
      this.frontId = payload.frontId;
      this.phaseId = payload.phaseId;
      this.frontName = payload.frontName;
      this.phaseName = payload.phaseName;
      this.fullScreen = !this.fullScreen;
    },
    openModal: function (param) {
      if (typeof param !== "string") {
        this.frontId = (typeof param.frontId !== "undefined") ? param.frontId : '';
        this.phaseId = (typeof param.phaseId !== "undefined") ? param.phaseId : '';
        // this.exercise = (typeof param.exercise !== "undefined") ? param.exercise : '';
        param = param.param;
      }
      this.modalName = param;
    },
    closeModal: function () {
      this.modalName = "";
      this.personalizeOpen = false;
    },
    addFront: function (payload) {
      this.$store.commit({
        type: 'addFront',
        ...payload,
      });
      store.dispatch('add_front', payload)
      this.closeModal();
    },
    addPhase: function (payload) {
      this.$store.commit({
        type: 'addFront',
        ...payload,
      });
      store.dispatch('add_phase', payload)
      this.closeModal();
    },
    addRowModal: function(payload) {
      this.openModal({param: 'addRow', frontId: payload.frontId, phaseId: payload.phaseId})
    },
    updateRowInList:function(data, frente, fase , restriccion, restricciondata){

      for (let i = 0; i < this.restrictions.length; i++) {

          if(this.restrictions[i]['codFrente'] == frente){

              for (let j = 0; j < this.restrictions[i]['listaFase'].length; j++) {

                  if(this.restrictions[i]['listaFase'][j]['codFase'] == fase){

                      for (let k = 0; k < this.restrictions[i]['listaFase'][j].listaRestricciones.length; k++) {

                          if(this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['codAnaResActividad'] == restriccion){

                            this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]  = restricciondata

                            break;

                          }
                      }
                  }

              }

          }
        }

    },
    getupdaterows:function(data, frente, fase , restriccion, restricciondata){
      let enviar = []
      for (let i = 0; i < this.restrictions.length; i++) {
        for (let j = 0; j < this.restrictions[i]['listaFase'].length; j++) {
          for (let k = 0; k < this.restrictions[i]['listaFase'][j].listaRestricciones.length; k++) {
              if(this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['isupdate'] == true){
                let fila = this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]
                let filaupd = {
                          codAnaResActividad:fila["codAnaResActividad"],
                          desActividad:fila["desActividad"],
                          desRestriccion:fila["desRestriccion"],
                          codTipoRestriccion: fila["codTipoRestriccion"]
                       }

                // this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['isEnabled'] = false
                enviar.push(filaupd)
              }else{

                // this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['isEnabled'] = true

              }
          }
        }
      }

      return enviar

    },
    updateRow: function(data) {
      var frente          = data.frente
      var fase            = data.fase
      var restriccion     = data.restriccion
      var restricciondata = data.data
      var enviar          = []
      enviar.push(restricciondata)

      console.log(">> llegamos a updateROw")
      console.log(restricciondata)

      //
      // let enviar = this.getupdaterows(data, frente, fase , restriccion, restricciondata)

      this.$store.dispatch('update_restricciones', enviar)
          .then((response) => {
            // console.log(response)
            this.updateRowInList(data, frente, fase , restriccion, restricciondata);
            this.enabledAllRows();
            // this.$refs.dataRows.loadRow();

            console.log(this.restrictions)



      })

    },
    // disabledRowsNotUpdate: function(data){

    //   for (let i = 0; i < this.restrictions.length; i++) {

    //       if(this.restrictions[i]['codFrente'] == data.frente){

    //         for (let j = 0; j < this.restrictions[i]['listaFase'].length; j++) {

    //           if(this.restrictions[i]['listaFase'][j]['codFase'] == data.fase){

    //             for (let k = 0; k < this.restrictions[i]['listaFase'][j].listaRestricciones.length; k++) {
    //                 if(this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['codAnaResActividad'] == data.actividad){

    //                     this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['isEnabled']  = false
    //                     this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['isupdate']   = true

    //                 }else{

    //                     this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['isEnabled']  = true

    //                 }
    //               }
    //           }

    //         }

    //       }
    //   }

    // },
    enabledAllRows: function (){

      for (let i = 0; i < this.restrictions.length; i++) {
            for (let j = 0; j < this.restrictions[i]['listaFase'].length; j++) {
              for (let k = 0; k < this.restrictions[i]['listaFase'][j].listaRestricciones.length; k++) {

                  // this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['isEnabled']  = false
                  this.restrictions[i]['listaFase'][j]['listaRestricciones'][k]['isupdate']   = false

              }
            }
          }

    },
    // disabledRows: function(data) {
    //   if (data.isupdate == true ){
    //     this.disabledRowsNotUpdate(data)

    //   }else{
    //     this.enabledAllRows()
    //   }

    // },



    getRestrictions: function(){
        let restrictions  = this.$store.state.whiteproject_rows;
        this.restrictions = restrictions
    },
  },
  computed:{

    isDisabled: function () {
      return this.rows.length > 0 ? false : true;
    },
    rows: function() {
      let res           = this.$store.state.whiteproject_rows;
      this.restrictions = res

      return this.restrictions;
    },
    hideCols: function() {
      return this.$store.getters.hideCols({id: this.frontId, phaseId: this.phaseId});
    },


  },
  mounted: function() {
    store.dispatch('get_Restriction_P');
    store.dispatch('get_restricciones');
    // this.getRestrictions();
    // console.log(this.restrictions)
  }

}
</script>
