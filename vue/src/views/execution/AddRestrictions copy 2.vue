<template>
<div
  v-if="isLoading == false"
  class="h-full flex justify-center sm:items-start"

>
<loading

      v-model:active=isLoadingTrue
      :can-cancel="false"
      :is-full-page=true
      loader="dots"
  />


</div>
<div v-if="isLoading">

    <Breadcrumb
      :paths="['Inicio', 'Análisis de restricciones', nameProyecto]"
       :settingFlag="true"
    />
    <div class="flex sm:flex-col justify-between mb-12 sm:mb-10">
      <span @click="pruebavalidar" class="items-start text-2xl text-activeText sm:mb-4"
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
      <div class="flex sm:flex-col sm:mb-2" v-if="!fullScreen ">
        <button
          :disabled="isDisabled"
          class="w-[150px] sm:w-full h-8 px-4 flex justify-between items-center border-2 border-orange rounded mr-4 sm:mb-2"
          @click="openModal({param: 'addFront'})"
          :class="{
            'border-orange': !isDisabled,
            'border-[#DCE4F9]': isDisabled,
          }"
        >
        <span
            class="text-xs"
            :class="{
              'text-orange': !isDisabled,
              'text-[#8A9CC9]': isDisabled,
            }"
          >
            Agregar frente
          </span>
          <img src="../../assets/btn-plus.svg" alt="" />
        </button>
        <button
          :disabled="isDisabled"
          class="w-[140px] sm:w-full h-8 px-4 flex justify-between items-center border-2 border-orange rounded mr-4 sm:mb-2"
          @click="openModal({param: 'addPhase'})"
          :class="{
            'border-orange': !isDisabled,
            'border-[#DCE4F9]': isDisabled,
          }"
        >
        <span
            class="text-xs"
            :class="{
              'text-orange': !isDisabled,
              'text-[#8A9CC9]': isDisabled,
            }"
          >
            Agregar Fase
          </span>
          <img src="../../assets/btn-plus.svg"

          alt="" />
        </button>
        <button
          class="w-[110px] sm:w-full h-8 px-4 flex justify-between items-center border-2 rounded"
          :disabled="isDisabled"
          :class="{
            'border-orange': !isDisabled,
            'border-[#DCE4F9]': isDisabled,
          }"
          @click="openModal({param: 'deleteFront'})"
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
            src="../../assets/tooltip-delete-active.svg"

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
      <div v-if="!fullScreen">

        <div v-for="(frente, index1) in rows" :key="index1">
          <hr v-if="index1 !== 0" class="mb-6 bg-[#D0D9F1]" />
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
              v-for="(fase, index2) in frente.listaFase"
              :key="index2"
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
                    <div class="flex mb-6 items-start cursor-pointer" @click="openModal({param: 'toggleColumn', frontId: frente.codFrente, phaseId: fase.codFase})">
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
                <div id="filterSection" class="outer relative border border-[#D0D9F1] rounded-lg before:w-24 before:absolute before:h-full before:shadow-tooltip">
                  <div
                  class="inner overflow-scroll overflow-hidden ml-24"

                  :style="{ 'min-height': `${heigthDiv}px` }">
                    <DataTableRestricciones
                      :tableType="'scroll'"
                      :cols="headerCols"
                      :restrictions="fase.listaRestricciones"
                      :hideCols=hideCols
                      :frontId="frente.codFrente"
                      :phaseId="fase.codFase"
                      :frontName="frente.desFrente"
                      :phaseName="fase.desFase"
                      class="table-fixed"
                      @fullScreen="toggleFullScreen"
                      @addRowModal="addRowModal"

                    >
                      <template #default="{ tr, id }">
                        <DataTableRestriccionesRow :statusRestriction="statusRestriction"  :listindex="[index1,index2,id]" :restriction_data="tr" :isupdate="tr.isupdate" :frontId="frente.codFrente" :phaseId="fase.codFase" :hideCols="hideCols" @openModal="openModal" @updateRow = "updateRow" @RegistrarCambioRow = "RegistrarCambioRow"   />
                      </template>

                    </DataTableRestricciones>
                  </div>
                </div>



              </div>
            </div>
          </div>
        </div>


      </div>
      <div v-if="fullScreen" class="mt-12">

        <div class="flex sm:flex-col justify-between sm:mb-10">
                    <div class="flex mb-6 items-start cursor-pointer" @click="openModal({param: 'toggleColumn', frontId: frente.codFrente, phaseId: fase.codFase})">
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
                <div id="filterSection" class="outer relative border border-[#D0D9F1] rounded-lg before:w-24 before:absolute before:h-full before:shadow-tooltip">
                  <div
                  class="inner overflow-scroll overflow-hidden ml-24"

                  :style="{ 'min-height': `${heigthDiv}px` }">
                    <DataTableRestricciones
                      :tableType="'scroll'"
                      :cols="headerCols"
                      :restrictions="restrictionsu"
                      :hideCols=hideCols
                      :frontId="frontId"
                      :phaseId="phaseId"
                      :frontName="frontName"
                      :phaseName="phaseName"
                      class="table-fixed"
                      @fullScreen="toggleFullScreen"
                      @addRowModal="addRowModal"

                    >
                      <template #default="{ tr, id }">
                        <DataTableRestriccionesRow :statusRestriction="statusRestriction"  :listindex="[index1,index2,id]" :restriction_data="tr" :isupdate="tr.isupdate" :frontId="frontId" :phaseId="phaseId" :hideCols="hideCols" @openModal="openModal" @updateRow = "updateRow" @RegistrarCambioRow = "RegistrarCambioRow"   />
                      </template>

                    </DataTableRestricciones>
                  </div>
                </div>



      </div>


    </div>





    <AddFront :rows="rows" v-if="modalName === 'addFront'" @closeModal="closeModal" @addFront="addFront" />
    <AddPhase :rows="rows" v-if="modalName === 'addPhase'" @closeModal="closeModal" @addPhase="addPhase" />
    <AddRow v-if="modalName === 'addRow'" @closeModal="closeModal" @addRow="addRow" />
    <DeleteRow v-if="modalName === 'deleteRow'" @closeModal="closeModal" @delRow="delRow" />
    <ToggleColumn :hideCols="hideCols" v-if="modalName == 'toggleColumn'" @closeModal="closeModal" @setColumnsStatus="setColumnsStatus" />
    <DeleteFront :rows="rows" v-if="modalName === 'deleteFront'" @closeModal="closeModal" @deleteFront="deleteFront" />
    <!-- <Confirm
      :confirmHeader="'Eliminar usuario'"
      :header="''"
      :paragraphs="[]"
      :buttons="['Sí, eliminar', 'No, mantener']"
      v-if="modalName === 'duplicateRow'"
      @closeModal="closeModal"
      @confirmStatus="duplicateRow"
    /> -->
</div>
</template>

<script>
import Breadcrumb from "../../components/Layout/Breadcrumb.vue";


import AddFront from "../../components/AddFront.vue";
import AddPhase from "../../components/AddPhase.vue";
import DataTableRestricciones from "../../components/DataTableRestricciones.vue";
import DataTableRestriccionesRow from "../../components/DataTableRestriccionesRow.vue";
// import RestrictionPerson from "../../components/RestrictionPerson.vue";

import ToggleColumn from "../../components/ToggleColumn.vue";
import AddRow from "../../components/AddRow.vue";
import DeleteRow from "../../components/DeleteRow.vue";
// import Confirm from "../../components/Confirm.vue";
// import DownloadReport from "../../components/DownloadReport.vue";
import SelectOption from "../../components/SelectOption.vue";
import DeleteFront from "../../components/DeleteFront.vue";
import Loading from 'vue-loading-overlay';

import store from "../../store";
export default {
  name: "white-project-component",
  components: {
    Loading,
    Breadcrumb,
    AddFront,
    AddPhase,
    DataTableRestricciones,
    DataTableRestriccionesRow,
    AddRow,
    DeleteRow,
    DeleteFront,
    // Confirm,
    // ScrollTableRow,
    // RestrictionPerson,


    // DownloadReport,
    SelectOption,
    ToggleColumn,


},
  data: function () {
    return {
      statusRestriction: true,
      pageloadflag:false,
      nameProyecto:"",
      disabledItems:true,
      heigthDiv: 0 ,
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
      restrictionsu:[],
      fullScreen: false,
      options: [],
      listhideCols: [],
      headerCols: {
        // plus: "",
        exercise: "Descrip. actividad",
        restriction: "Descrip. restricción",
        restrictionType: "Tipo de restricción",
        date_required: "Fecha requerida",
        date_conciliad: "Fecha conciliada",
        responsible: "Responsable",
        responsible_area: "Área responsable",
        condition: "Estado",
        applicant: "Solicitante ",
      },
      restrictionsUpd:[],
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
    setColumnsStatus: function(payload) {

      let point = this
      store.dispatch('update_hidden_columns', payload).then((response) => {
        console.log(response)

        if(response.data.estado){

          this.$store.state.hiddenCols = payload.hideCols
          this.closeModal();


        }else{

          console.log(">>> tenemos problemas ")
          console.log(response.data.mensaje)

        }

      });

      // console.log(payload.hideCols)
      // this.listhideCols = payload.hideCols
      // this.$store.commit({
      //   type: 'setHideCols',
      //   frontId: this.frontId,
      //   phaseId: this.phaseId,
      //   hideCols: payload.hideCols
      // });
      // this.closeModal();
    },
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

    pruebavalidar: function (payload) {
          console.log(this.restrictions)
    },
    toggleFullScreen: function (payload) {
      this.frontId       = payload.frontId;
      this.phaseId       = payload.phaseId;
      this.frontName     = payload.frontName;
      this.phaseName     = payload.phaseName;
      this.restrictionsu = payload.restrictions;
      this.fullScreen = !this.fullScreen;
    },
    openModal: function (param) {
      // console.log(param)
      if (typeof param !== "string") {

        if(param.param != "duplicateRow"){

          this.frontId   = (typeof param.frontId !== "undefined") ? param.frontId : '';
          this.phaseId   = (typeof param.phaseId !== "undefined") ? param.phaseId : '';
          this.exercise  = (typeof param.exercise !== "undefined") ? param.exercise : '';

          param          = param.param;
          this.modalName = param;

        }else{

          this.duplicateRow(param)

        }

      }

    },
    closeModal: function () {
      this.modalName = "";
      this.personalizeOpen = false;
    },

    deleteFront: function (payload) {

      console.log(payload)
      store.dispatch('delete_front', payload)
      this.closeModal();
    },

    addFront: function (payload) {

      let point = this
      store.dispatch('add_front', payload).then((response) => {

        payload['codFrenteReal'] = response.data.codFrente
        point.$store.commit({
        type: 'addFront',
        ...payload,
      });

      });

      this.closeModal();
    },
    addPhase: function (payload) {

      console.log(payload)
      let codFaseTemp = this.$store.commit({
        type: 'addFront',
        ...payload,
      });

      store.dispatch('add_phase', payload).then((response) => {

        payload['codFaseReal'] = response.data.codFase
        this.$store.commit({
          type: 'updPhase',
          ...payload,
        });

        this.filterSectionHeight()

      });


      this.closeModal();
    },
    addRow: function (payload) {

      this.$store.commit({
        type: 'addScrollTableRow',
        frontId: this.frontId,
        phaseId: this.phaseId,
        ...payload,
      });

      this.closeModal();

    },
    delRow: function (payload) {
      let frenteId = this.frontId
      let faseId = this.phaseId
      let restriccionId = this.exercise

      console.log(frenteId+" -- "+faseId+" -- "+restriccionId)
      let enviar = {codAnaResActividad:this.exercise};
      let point  = this;
      store.dispatch('del_Restrictions', enviar).then((response) => {

        if(response.data.flag == 1){

              point.$store.commit({
                type: 'delScrollTableRow',
                frontId: frenteId,
                phaseId: faseId,
                activity: restriccionId,
                ...payload,
              });

        this.closeModal();

        }else{
          console.log("Tenemos errores al eliminar")
        }


      });

    },
    duplicateRow: function (payload) {
      // console.log(payload)
      let frenteId      = payload.frontId
      let faseId        = payload.phaseId
      let restriccionId = payload.exercise

      let point  = this;
      store.dispatch('dup_Restrictions', restriccionId).then((response) => {

        if(response.data.flag == 1){

          let codAnaResActividad = response.data.resultado
          // console.log(">>>>>> codAnaResActividad ")
          // console.log(codAnaResActividad)
          point.$store.commit({
              type: 'duplicateScrollTableRow',
              frontId: frenteId,
              phaseId: faseId,
              activity: restriccionId,
              codAna: codAnaResActividad,
              ...payload,
          });


        }else{
          console.log("Tenemos errores al duplicar registros")
        }


      });

    },
    addRowModal: function(payload) {
      this.openModal({param: 'addRow', frontId: payload.frontId, phaseId: payload.phaseId})
    },
    updateRow: function(data) {

      let enviar = this.restrictionsUpd
      if (enviar.length > 0){
          console.log(">>>>>> Entrando a actualizar.")

          this.$store.dispatch('update_restricciones', enviar)
              .then((response) => {
                // console.log(">>>>>>>>>>> salimos del post ")
                // console.log(response)

                if (response.data.flag == 1 ){
                  console.log(">>>>> se obtuvo resultados positivos ")
                  console.log(this.restrictions)

                    for (let i = 0; i < enviar.length; i++) {

                      if (response.data.typeResult == 'ins'){

                        console.log(">>> entrando a actualizar")
                        console.log(response.data.updateResult.codAnaResActividad)

                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['codAnaResActividad']        = response.data.updateResult.codAnaResActividad

                      }

                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['desActividad']        = enviar[i]['desActividad']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['desRestriccion']      = enviar[i]['desRestriccion']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['codTipoRestriccion']  = enviar[i]['codTipoRestriccion']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['desTipoRestriccion']  = enviar[i]['desTipoRestriccion']

                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['dayFechaRequerida']     = enviar[i]['dayFechaRequerida']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['dayFechaConciliada']    = enviar[i]['dayFechaConciliada']

                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['idUsuarioResponsable']   = enviar[i]['idUsuarioResponsable']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['idUsuarioResponsable']   = enviar[i]['desUsuarioResponsable']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['codAreaResponsable']   = enviar[i]['codAreaResponsable']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['codEstadoActividad']   = enviar[i]['codEstadoActividad']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['desEstadoActividad']   = enviar[i]['desEstadoActividad']
                        this.restrictions[enviar[i].idfrente]['listaFase'][enviar[i].idfase]['listaRestricciones'][enviar[i].idrestriccion]['isEnabled']           = false

                    }
                    console.log(">>>>>> impresion de como queda al final")
                    console.log(this.restrictions)

                    this.restrictionsUpd = []

                }else{
                    console.log(">>>>>> No tenemos registros para actualizar.")
                    console.log(response.data.mensaje)
                }
            });

      }

    },

    RegistrarCambioRow: function(data){

      let idfrente       = data.idfrente
      let idfase         = data.idfase
      let idrestriccion  = data.idrestriccion
      let datafinal      = data.data
      let codActividad   = datafinal["codAnaResActividad"]
      let isupdate       = datafinal["isupdate"]

      let llave = null;
      let unid = -1

      if (isupdate == false){

            this.restrictionsUpd.forEach(function (item, key, mapObj) {
                if (item.codAnaResActividad == codActividad) {
                    llave = key;
                    unid  = 1;
                }
            });

            this.restrictionsUpd.splice(llave, unid);

      }else{


        this.restrictionsUpd.forEach(function (item, key, mapObj) {
                if (item.codAnaResActividad == codActividad) {
                    llave = key;
                    unid  = 1;
                }
            });

        this.restrictionsUpd.splice(llave, unid);

        let dr= datafinal['dayFechaRequerida']
        let dayFechaRequerida_str   = (dr != "") ? dr.getFullYear().toString()+"-"+((dr.getMonth()+1).toString().length==2?(dr.getMonth()+1).toString():"0"+(dr.getMonth()+1).toString())+"-"+(dr.getDate().toString().length==2?dr.getDate().toString():"0"+dr.getDate().toString())+" "+"12:00:00":"";
        let dc= datafinal['dayFechaConciliada']
        let dayFechaConciliada_str =  (dc != "") ? dc.getFullYear().toString()+"-"+((dc.getMonth()+1).toString().length==2?(dc.getMonth()+1).toString():"0"+(dc.getMonth()+1).toString())+"-"+(dc.getDate().toString().length==2?dc.getDate().toString():"0"+dc.getDate().toString())+" "+"12:00:00":"";

        let codAnaResFrente    = this.restrictions[idfrente]['codFrente']
        let codAnaResFase      = this.restrictions[idfrente]['listaFase'][idfase]['codFase']
        let codAnaResActividad = this.restrictions[idfrente]['listaFase'][idfase]['listaRestricciones'][idrestriccion]['codAnaResActividad']
        let row =       {
                            idfrente      : idfrente,
                            idfase        : idfase,
                            idrestriccion : idrestriccion,
                            columna       : datafinal["column"],
                            codAnaResFrente : codAnaResFrente,
                            codAnaResFase   : codAnaResFase,
                            codAnaResActividad: codAnaResActividad,
                            desActividad:datafinal["desActividad"],
                            desRestriccion:datafinal["desRestriccion"],
                            codTipoRestriccion: datafinal["codTipoRestriccion"],
                            desTipoRestriccion: datafinal['desTipoRestriccion'],
                            dayFechaRequerida: dayFechaRequerida_str,
                            dayFechaConciliada: dayFechaConciliada_str,
                            idUsuarioResponsable : datafinal['idUsuarioResponsable'],
                            desUsuarioResponsable : datafinal['desUsuarioResponsable'],
                            codEstadoActividad : datafinal['codEstadoActividad'],
                            desEstadoActividad : datafinal['desEstadoActividad'],


                        }

        this.restrictionsUpd.push(row)


      }

      console.log(">>> Verificamos lo que enviamos  ")
      console.log(this.restrictionsUpd)

    },

    getRestrictions: function(){
        let restrictions  = this.$store.state.whiteproject_rows;
        this.restrictions = restrictions
    },

    filterSectionHeight() {
      const filterSectionDOM = document.getElementById("filterSection");
      this.heigthDiv =  filterSectionDOM ? (filterSectionDOM.offsetHeight + filterSectionDOM.offsetHeight*0.5) : 0;
    },

  },
  computed:{

    isDisabled: function () {
      return this.disabledItems
    },
    rows: function() {
      let res           = this.$store.state.whiteproject_rows;
      this.restrictions = res

      return this.restrictions;
    },
    hideCols: function() {
      return  this.$store.state.hiddenCols; //this.listhideCols; //this.$store.getters.hideCols({id: this.frontId, phaseId: this.phaseId});
    },
    isLoadingTrue(){
       return true
    },
    isLoading: function(){
      return this.pageloadflag
    },
    // hideCols: function() {
    //   return this.$store.getters.hideCols({id: this.frontId, phaseId: this.phaseId});
    // }

  },
  mounted: async function() {

    await store.dispatch('get_infoPerson');
    console.log(">> entro 1")
    await store.dispatch('getNameProy').then((response) => {
      this.nameProyecto = response
    });
    console.log(">> entro 2")
    await store.dispatch('get_datos_restricciones').then((response) => {

      this.statusRestriction = this.$store.state.estadoRestriccion;

      if (this.statusRestriction === false){
        this.disabledItems = true
      }else{
        this.disabledItems = false

      }



    });
    console.log(">> entro 3")
    await this.filterSectionHeight()

    this.pageloadflag = true



  },
  created:function(){



  },
  beforeMount:function(){
   // this.filterSectionHeight()
  }
  // updated:function(){
  //   this.filterSectionHeight()
  // }

}
</script>
