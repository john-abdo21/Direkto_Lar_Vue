<template>
  <div
    v-if="isLoading == false"
    class="h-full flex justify-center sm:items-start"
  >
    <loading
      v-model:active="isLoadingTrue"
      :can-cancel="false"
      :is-full-page="true"
      loader="dots"
    />
  </div>
  <div v-if="isLoading">
    <Breadcrumb
      :paths="['Inicio', 'Análisis de restricciones', nameProyecto]"
      :urls ="['home', 'restricciones']"
      :settingFlag="true"
    />
    <div
      class="flex sm:flex-col justify-between mb-12 sm:mb-10"
      @click="revision"
    >
      <span class="items-start text-2xl text-activeText sm:mb-4"
        >Restricciones del proyecto : {{nameProyecto}}</span
      >
      <div class="flex sm:flex-col h-[52px] sm:h-auto">
        <!-- <div class="relative mr-4 sm:mr-0 sm:mb-4">
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
        </div> -->
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
          <SelectOption
            :selType="'tree'"
            @selected="selFilterOpt"
            @treeSelected="selTreeOpt"
            :options="options"
            v-if="filterOpen"
            :treeIndex="treeIndex"
            :treeOptions="treeOptions"
          />
        </div>
      </div>
    </div>
    <div class="flex sm:flex-col mb-12 justify-between">
      <div class="flex sm:flex-col sm:mb-2" v-if="!fullScreen">
        <button
          :disabled="isDisabled"
          class="w-[120px] sm:w-full h-8 px-3 flex justify-between items-center border-2 border-orange rounded mr-2 sm:mb-2 "
          @click="openModal({ param: 'addFront' })"
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
          class="w-[120px] sm:w-full h-8 px-3 flex justify-between items-center border-2 border-orange rounded mr-2 sm:mb-2"
          @click="openModal({ param: 'addPhase' })"
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
          <img src="../../assets/btn-plus.svg" alt="" />
        </button>
        <button
          class="w-[110px] sm:w-full h-8 px-3 flex justify-between items-center border-2 rounded mr-2 sm:mb-2"
          :disabled="isDisabled"
          :class="{
            'border-orange': !isDisabled,
            'border-[#DCE4F9]': isDisabled,
          }"
          @click="openModal({ param: 'deleteFront' })"
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
        <button
          @click="openModal({ param: 'enviarNoti' })"
          class="w-[110px] sm:w-full h-8 px-4 flex justify-between items-center border-2 rounded"
          :disabled="disabledItemsEnviarCorreos"
          :class="{
            'border-orange': !disabledItemsEnviarCorreos,
            'border-[#DCE4F9]': disabledItemsEnviarCorreos,
          }"
        >
          <span
            class="text-xs"
            :class="{
              'text-orange': !disabledItemsEnviarCorreos,
              'text-[#8A9CC9]': disabledItemsEnviarCorreos,
            }"
          >
            Enviar Correos ({{countNotNoti}})

          </span>
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

      <div class="flex sm:flex-wrap" v-if="!isDisabled ">
        <a
        class="flex items-center mr-4 cursor-pointer sm:mb-2"
        @click="downloadFile"

        >
          <span class="text-xs text-[#002B6B] mr-1">Descargar plantilla</span>
          <img src="../../assets/download.svg" alt="" />
      </a>
        <div
          class="flex items-center mr-4 cursor-pointer sm:mb-2"
          @click="openModal({ param: 'uploadExcel' })"
        >
          <span class="text-xs text-[#002B6B] mr-1">Importar excel</span>
          <img src="../../assets/upload.svg" alt="" />
        </div>
        <div
          class="flex items-center cursor-pointer sm:mb-2"
          @click="downloadReporte"
        >
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
            <span class="text-xl text-activeText">
              {{ frente.desFrente }}


                  <div class="bar">
                      <div class="filled"
                          :class="countActivities(frente.codFrente).colorClass"
                          :style="{ width: countActivities(frente.codFrente).percentage + '%' }">
                          {{ countActivities(frente.codFrente).percentage }}%
                      </div>
                  </div>


<!--
              <div class="bar" :class="countActivities(frente.codFrente).colorClass" style="width: countActivities(frente.codFrente).percentage + '%'">
               {{ countActivities(frente.codFrente).percentage }}%
              </div> -->


              </span>
            <img
              src="../../assets/ic_arrow-down.svg"
              alt=""
              class="flex transition"
              :class="{
                'rotate-180': frente.isOpen,
                'rotate-0': !frente.isOpen,
              }"
            />
          </div>
          <div
            class="flex flex-col mt-2 mb-8 pl-8 sm:pl-4"
            v-if="frente.isOpen"
          >
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
                  class="ml-[100px] sm:ml-8 text-[0.657remrem] leading-7 text-activeText"
                >


                  No retrasadas: {{get_noretrasados(fase.listaRestricciones)}}
                  <span class="sm:hidden">/</span> <span style="color:#d50505">Retrasadas:
                    {{get_retrasados(fase.listaRestricciones)}}</span>
                </span>
              </div>

              <div class="mt-8">
                <div class="flex sm:flex-col justify-between sm:mb-10">
                  <div
                    class="flex mb-6 items-start cursor-pointer"
                    @click="
                      openModal({
                        param: 'toggleColumn',
                        frontId: frente.codFrente,
                        phaseId: fase.codFase,
                      })
                    "
                  >
                    <img
                      src="../../assets/visibility.svg"
                      alt=""
                      class="mr-1"
                    />
                    <span class="text-xs text-[#002B6B]"
                      >Ocultar / mostrar columnas</span
                    >
                  </div>

                  <transition name="fade">
                    <div
                      key="3"
                      v-if="!showifUpd"
                      class="flex items-end mb-6 cursor-pointer"
                    ></div>
                  </transition>
                  <transition name="fade">
                    <div
                      key="1"
                      v-if="showifUpd"
                      class="flex items-end mb-6 cursor-pointer text-xs text-[#002B6B]"
                    >
                      <img
                        src="../../assets/upload.svg"
                        alt=""
                        class="mr-1"
                      />
                      {{ showifUpdMsg }}
                    </div>
                  </transition>
                </div>
                <div
                  id="filterSection"
                  class="outer relative border border-[#D0D9F1] rounded-lg before:w-24 before:absolute before:h-full before:shadow-tooltip"
                >
                  <div
                    class="inner overflow-scroll overflow-hidden ml-24"
                    :style="{ 'min-height': `${heigthDiv}px` }"
                  >
                    <DataTableRestricciones
                      :rolProyecto = "rolProyecto"
                      :solicitanteActual = "solicitanteActual"
                      :fullScreen = "fullScreen"
                      :tableType="'scroll'"
                      :cols="headerCols"
                      :restrictions="fase.listaRestricciones"
                      :hideCols="hideCols"
                      :frontId="frente.codFrente"
                      :phaseId="fase.codFase"
                      :frontName="frente.desFrente"
                      :phaseName="fase.desFase"
                      :ResizeActually="sizeActually"
                      class="table-fixed"
                      @fullScreen="toggleFullScreen"
                      @addRowModal="addRowModal"
                      :statusDraggable="statusDraggable"
                      :statusRestriction="statusRestriction"
                      :idxFront="index1"
                      :idxPhase="index2"
                      :validarUpd="validarUpd"
                      @updateRow="updateRow"
                      @RegistrarCambioRow="RegistrarCambioRow"
                      @movimientoRow="movimientoRow"
                      @updalidarUpd="updalidarUpd"
                      @openModal="openModal"
                      @addRowData="addRowData"
                    >
                      <!-- <template #default="{ tr, id }">
                        <DataTableRestriccionesRow :statusRestriction="statusRestriction"  :listindex="[index1,index2,id]" :restriction_data="tr" :isupdate="tr.isupdate" :frontId="frente.codFrente" :phaseId="fase.codFase" :hideCols="hideCols" | @openModal="openModal" @updateRow = "updateRow" @RegistrarCambioRow = "RegistrarCambioRow"   />
                      </template> -->
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
          <div
            class="flex mb-6 items-start cursor-pointer"
            @click="
              openModal({
                param: 'toggleColumn',
                frontId: frente.codFrente,
                phaseId: fase.codFase,
              })
            "
          >
            <img
              src="../../assets/visibility.svg"
              alt=""
              class="mr-1"
            />
            <span class="text-xs text-[#002B6B]"
              >Ocultar / mostrar columnas</span
            >
          </div>

          <transition name="fade1">
                    <div
                      key="3"
                      v-if="!showifUpd"
                      class="flex items-end mb-6 cursor-pointer"
                    ></div>
          </transition>
          <transition name="fade1">
                    <div
                      key="1"
                      v-if="showifUpd"
                      class="flex items-end mb-6 cursor-pointer text-xs text-[#002B6B]"
                    >
                      <img
                        src="../../assets/upload.svg"
                        alt=""
                        class="mr-1"
                      />
                      {{ showifUpdMsg }}
                    </div>
            </transition>

          <!-- <div class="flex items-end mb-6 cursor-pointer">
            <img
              src="../../assets/upload.svg"
              alt=""
              class="mr-1"
            />

            <span class="text-xs text-[#002B6B]"> Registros Actualizados</span>
          </div> -->
        </div>
        <div
          id="filterSection"
          class="outer relative border border-[#D0D9F1] rounded-lg before:w-24 before:absolute before:h-full before:shadow-tooltip"
        >
          <div
            class="inner overflow-scroll overflow-hidden ml-24"
            :style="{ 'min-height': `${heigthDiv}px` }"
          >
            <DataTableRestricciones
              :solicitanteActual = "solicitanteActual"
              :fullScreen="fullScreen"
              :tableType="'scroll'"
              :cols="headerCols"
              :restrictions="restrictionsu"
              :hideCols="hideCols"
              :frontId="frontId"
              :phaseId="phaseId"
              :frontName="frontName"
              :phaseName="phaseName"
              :ResizeActually="sizeActually"
              class="table-fixed"
              @fullScreen="toggleFullScreen"
              @addRowModal="addRowModal"
              :statusRestriction="statusRestriction"
              :statusDraggable="statusDraggable"
              :idxFront="idxFront_pivot"
              :idxPhase="idxPhase_pivot"
              :validarUpd="validarUpd"
              @updateRow="updateRow"
              @RegistrarCambioRow="RegistrarCambioRow"
              @movimientoRow="movimientoRow"
              @updalidarUpd="updalidarUpd"
              @openModal="openModal"
              @addRowData="addRowData"
            >
              <!-- <template #default="{ tr, id }">
                        <DataTableRestriccionesRow :statusRestriction="statusRestriction"  :listindex="[index1,index2,id]" :restriction_data="tr" :isupdate="tr.isupdate" :frontId="frontId" :phaseId="phaseId" :hideCols="hideCols" @openModal="openModal" @updateRow = "updateRow" @RegistrarCambioRow = "RegistrarCambioRow"   />
                      </template> -->
            </DataTableRestricciones>
          </div>
        </div>
      </div>
    </div>

    <AddFront
      :rows="rows"
      v-if="modalName === 'addFront'"
      @closeModal="closeModal"
      @addFront="addFront"
    />
    <AddPhase
      :rows="rows"
      v-if="modalName === 'addPhase'"
      @closeModal="closeModal"
      @addPhase="addPhase"
    />
    <AddRow
      v-if="modalName === 'addRow'"
      @closeModal="closeModal"
      @addRow="addRow"
    />
    <AddRowData
      v-if="modalName === 'addRowData'"
      :hideCols="hideCols"
      :statusRestriction="statusRestriction"
      :frontId="frontId"
      :phaseId="phaseId"
      @saveRowfromForm="saveRowfromForm"
      @closeModal="closeModal"
    />
    <DeleteRow
      v-if="modalName === 'deleteRow'"
      @closeModal="closeModal"
      @delRow="delRow"
    />
    <ToggleColumn
      :hideCols="hideCols"
      v-if="modalName == 'toggleColumn'"
      @closeModal="closeModal"
      @setColumnsStatus="setColumnsStatus"
    />
    <DeleteFront
      :rows="rows"
      v-if="modalName === 'deleteFront'"
      @closeModal="closeModal"
      @deleteFront="deleteFront"
    />
    <uploadExcel
    v-if="modalName === 'uploadExcel'"
    @closeModal="closeModal"
    />
    <Confirm
      :confirmHeader="''"
      :header="'Enviar Notificaciones'"
      :paragraphs="['Se enviaran '+countNotNoti+' notificaciones correspondientes a cambios o nuevas inserciones.'] "
      :buttons="['Sí, Enviar', 'No, Cancelar']"
      v-if="modalName === 'enviarNoti' && countNotNoti > '0'"
      @closeModal="closeModal"
      @confirmStatus="enviarNotificaciones"
    />
  </div>
</template>

<script>
// import excelParser from "../excel-parser";
import moment from 'moment'
import exportFromJSON from "export-from-json";
import Breadcrumb from "../../components/Layout/Breadcrumb.vue";

import AddFront from "../../components/AddFront.vue";
import AddPhase from "../../components/AddPhase.vue";
import DataTableRestricciones from "../../components/DataTableRestricciones.vue";
import DataTableRestriccionesRow from "../../components/DataTableRestriccionesRow.vue";
// import RestrictionPerson from "../../components/RestrictionPerson.vue";

import ToggleColumn from "../../components/ToggleColumn.vue";
import AddRow from "../../components/AddRow.vue";
import DeleteRow from "../../components/DeleteRow.vue";
import UploadExcel from "../../components/UploadExcel.vue";
import Confirm from "../../components/Confirm.vue";
// import DownloadReport from "../../components/DownloadReport.vue";
import SelectOption from "../../components/SelectOption.vue";
import DeleteFront from "../../components/DeleteFront.vue";
import Loading from "vue-loading-overlay";

import AddRowData from "../../components/AddRowData.vue";

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
    UploadExcel,
    Confirm,
    // ScrollTableRow,
    // RestrictionPerson,

    // DownloadReport,
    SelectOption,
    ToggleColumn,
    AddRowData,
    exportFromJSON,
    moment
  },
  data: function () {
    return {
      // mensajeNotificaciones: 'Se enviaran '+this.countNotNoti+' Notificaciones',
      sizeActually: 0,

      FilterActiveFlag: false,
      FilterActiveData: [],
      FilterActivetree: [],
      showifUpd: false,
      showifUpdMsg: "",
      validarUpd: false,
      statusRestriction: true,
      rolProyecto:0,
      statusDraggable: true,
      pageloadflag: false,
      nameProyecto: "",
      disabledItems: true,
      disabledItemsEnviarCorreos: false,
      heigthDiv: 0,
      modalName: "",
      personalizeOpen: false,
      filterOpen: false,
      filterId: "",
      filterName: "",
      frontId: "",
      frontName: "",
      phaseId: "",
      exercise: "",
      phaseName: "",
      restrictionsu: [],
      fullScreen: false,
      options: [
        {
          name: "Responsable",
          value: "responsible",
        },
        {
          name: "Solicitante",
          value: "applicant",
        },
        {
          name: "Vencimiento",
          value: "expiration",
        },
        {
          name: "Tipo de restricción",
          value: "restriction_type",
        },
      ],
      listhideCols: [],
      headerCols: {
        // plus: "",
        // exercise: "Descrip. Actividad",
        // restriction: "Descrip. Restricción",
        // restrictionType: "Tipo de restricción",
        exercise: "Actividad",
        restriction: "Restricción",
        restrictionType: "Tipo",
        date_required: "Fecha requerida",
        date_conciliad: "Fecha conciliada",
        date_identity: "Fecha Identificación",
        responsible: "Responsable",
        responsible_area: "Área responsable",
        condition: "Estado",
        applicant: "Solicitante ",
      },
      restrictionsUpd: [],
      restrictions: [
        {
          codFrente: 1,
          desFrente: "Frente 001",
          isOpen: true,
          listaFase: [
            {
              codFase: 1,
              desFase: "Fase 001",
              listaRestricciones: [
                {
                  codAnaResActividad: 1,
                  desActividad: "Activi 1",
                  desRestriccion: " restriccion 1",
                  codTipoRestriccion: 1,
                  desTipoRestriccion: "ARQUITECTURA",
                },
                {
                  codAnaResActividad: 2,
                  desActividad: "Actividad 2",
                  desRestriccion: " restriccion 2",
                  codTipoRestriccion: 1,
                  desTipoRestriccion: "ARQUITECTURA",
                },
              ],
            },
            {
              codFase: 2,
              desFase: "Fase 002",
              listaRestricciones: [
                {
                  codAnaResActividad: 3,
                  desActividad: "Actividad 3",
                  desRestriccion: " restriccion 3",
                  codTipoRestriccion: 2,
                },
              ],
            },
          ],
        },
        {
          codFrente: 2,
          desFrente: "Frente 002",
          isOpen: true,
          listaFase: [],
        },
      ],
      treeOptions: [],
      treeIndex: 0,
      idxFront_pivot:0,
      idxPhase_pivot:0
    };
  },
  methods: {

   get_retrasados(datos){
    let conteo            = 0
    try {

      const hoy     = new Date();
      // console.log(">>>> verificamos la fecha de hoy"+hoy)
      // datos.forEach(item => {
      //   if ( item.codEstadoActividad < "3"  && new Date(item.dayFechaConciliada+ "T00:00:00-05:00") < hoy){
      //     console.log(">> entramos registros")
      //     console.log(new Date(item.dayFechaConciliada+ "T00:00:00-05:00"))
      //     console.log(item.codEstadoActividad)
      //     console.log(hoy)
      //     conteo++;
      //   }

      // });
      // let fecha_comparacion = new Date(item.dayFechaRequerida).toLocaleString("es-PE",{ hourCycle: 'h24'})
       conteo        = datos.filter(item => item.codEstadoActividad < "3" && new Date(item.dayFechaConciliada+ "T00:00:00-05:00") < hoy).length;

    } catch (error) {

    }

    return conteo;
   },
   get_noretrasados(datos){
    let conteo            = 0
    try {

      const hoy    = new Date().toLocaleString("es-PE",{ hourCycle: 'h24'});
      // let fecha_comparacion = new Date(item.dayFechaRequerida).toLocaleString("es-PE",{ hourCycle: 'h24'})
      conteo       = datos.filter(item => item.codEstadoActividad < "3" && new Date(item.dayFechaConciliada+ "T00:00:00-05:00").toLocaleString("es-PE",{ hourCycle: 'h24'}) > hoy).length;

    } catch (error) {

    }

    return conteo;
   },
   downloadReporte(){

    store.dispatch("report_restrictions_for_project");

   },
   downloadFile() {
      // const nombreArchivo = 'formato.xlsx';
      // const rutaArchivo = require('@/assets/' + nombreArchivo);
      // this.$refs.descarga.href = rutaArchivo;
      // this.$refs.descarga.download = nombreArchivo;
      // this.$refs.descarga.click();

      const filePath = import.meta.env.VITE_WEB_FIN_BASE_URL+'/formato.xlsx';
      const link = document.createElement('a');
      link.href = filePath;
      link.download = 'plantilla_data_masiva';
      link.target = '_blank';
      document.body.appendChild(link);
      link.click();
   },
   exportDataFromJSON:function (data, newFileName, fileExportType) {
    if (!data) return;
    try {
      const fileName = newFileName || "exported-data";
      const exportType = exportFromJSON.types[fileExportType || "xls"];
      exportFromJSON({ data, fileName, exportType });
    } catch (e) {
      throw new Error("Parsing failed!");
    }
    },
    updisOpen: function (saveisOpen) {

        // let saveisOpen  = {}

        if (Object.keys(saveisOpen).length == 0 ){

            /* Actualimos  todos los frentes a true para poder descargar sus restricciones */
            this.restrictions.forEach(element => {
            saveisOpen[element.codFrente] = element.isOpen
            element.isOpen = true

            });

        }else{

            /* Actualimos  todos los frentes a cuando estaban antes  */
            this.restrictions.forEach(element => {
                element.isOpen = saveisOpen[element.codFrente]
            });


        }
      return saveisOpen
    },
    exportData: async function (payload) {


      let saveisOpen  = {}
      let rows        = [];
      saveisOpen      = await this.updisOpen(saveisOpen)
      let table       = document.querySelectorAll("table.tbldownload");


      table.forEach(tableData => {

        let elements  = tableData.querySelectorAll('tbody > tr');
        elements.forEach(element => {
          let row = {}

          let frente = element.querySelectorAll('input[name="frontName"]')[0].value;
          let fase   = element.querySelectorAll('input[name="phaseName"]')[0].value;
          row['Frente'] = frente
          row['Fase']   = fase

          let tdData = element.querySelectorAll('td.downExcel:not(.hidden)');
          tdData.forEach(element0 => {
                let data = element0.querySelectorAll('input,select')[0];
                try{
                    // console.log(element.classList.contains('hidden'))
                  if(data.tagName == 'SELECT'){
                    row[this.headerCols[data.name]] =  data.options[data.selectedIndex].text
                    // row.push(data.options[data.selectedIndex].text)

                  }

                  if (data.tagName == 'INPUT'){
                    // row.push(data.value)
                    row[this.headerCols[data.name]] =  data.value
                  }

                }catch(e){
                  // row.push(element0.innerText)
                }
                // if ( typeof data.tagName !== 'undefined') {
                //   console.log(data.tagName)
                // }
                // console.log(element0.tagName)

          });

          rows.push(row)
        });


      });

      let name_file = this.nameProyecto+'_'+moment(new Date()).format('DDMMYYYY')
      this.exportDataFromJSON(rows, name_file, null);
      await this.updisOpen(saveisOpen)

},

    setColumnsStatus: function (payload) {
      let point = this;
      store.dispatch("update_hidden_columns", payload).then((response) => {
        console.log(response);

        if (response.data.estado) {
          this.$store.state.hiddenCols = payload.hideCols;
          this.closeModal();
        } else {
          console.log(">>> tenemos problemas ");
          console.log(response.data.mensaje);
        }
      });


    },
    handleClick: function (id) {
      id === "personalize" && (this.personalizeOpen = !this.personalizeOpen);
      id === "filter" && (this.filterOpen = !this.filterOpen);
    },
    toggleOpen: function (param) {
      console.log(">>>>> vemos los valores");
      console.log(param);
      this.rows.map((row) => {
        console.log(row);
        if (row.desFrente === param) {
          row.isOpen = !row.isOpen;
        }
      });
    },

    pruebavalidar: function (payload) {
      console.log(this.restrictions);
    },
    toggleFullScreen: function (payload) {
      this.frontId = payload.frontId;
      this.phaseId = payload.phaseId;
      this.frontName = payload.frontName;
      this.phaseName = payload.phaseName;
      this.restrictionsu = payload.restrictions;
      this.fullScreen = !this.fullScreen;
      this.idxFront_pivot = payload.idxFront;
      this.idxPhase_pivot = payload.idxPhase;

      console.log(">>>>>> verificamos que restrictions")
      console.log(payload.restrictions)
    },
    openModal: function (param) {
      // console.log(param)
      if (typeof param !== "string") {
        if (param.param != "duplicateRow") {
          this.frontId =
            typeof param.frontId !== "undefined" ? param.frontId : "";
          this.phaseId =
            typeof param.phaseId !== "undefined" ? param.phaseId : "";
          this.exercise =
            typeof param.exercise !== "undefined" ? param.exercise : "";

          param = param.param;
          if ((param == 'enviarNoti')){

            this.modalName = this.countNotNoti > '0' ?  param : '';

          }else{

            this.modalName = param

          }



        } else {
          this.duplicateRow(param);
        }
      }
    },
    closeModal: async function () {

      if (this.modalName == 'uploadExcel'){

        await this.callMounted();

      }

      console.log(">>>>> mira mira")

      this.modalName = "";
      this.personalizeOpen = false;
    },

    enviarNotificaciones: function (payload) {

      let point = this;
      store.dispatch("push_enviar_notificaciones", payload).then((response) => {
        let mensaje = "";
        // let data = [];

        if (response.data.flag == 1) {

          point.$store.commit({
            type: "updNotificaciones",
            ...payload,
          });


          console.log(response.data);
          mensaje = "Se enviaron las notificaciones !! ";

        }else{

          mensaje = "Tenemos errores al eliminar";


        }

         point.setTimeifUpd(600, mensaje);
         point.closeModal();

      });



    },

    deleteFront: function (payload) {
      console.log(payload);
      store.dispatch("delete_front", payload);
      this.closeModal();
    },

    addFront: function (payload) {
      let point = this;
      store.dispatch("add_front", payload).then((response) => {
        payload["codFrenteReal"] = response.data.codFrente;
        point.$store.commit({
          type: "addFront",
          ...payload,
        });
      });

      this.closeModal();
    },
    addPhase: function (payload) {
      // let codFaseTemp = this.$store.commit({
      //   type: 'addFront',
      //   ...payload,
      // });

      let point = this;
      store.dispatch("add_phase", payload).then((response) => {
        payload["cantNew"] = 6;
        point.$store.commit({
          type: "addFront",
          ...payload,
        });

        payload["codFaseReal"] = response.data.codFase;
        point.$store.commit({
          type: "updPhase",
          ...payload,
        });

        point.filterSectionHeight();
      });

      point.closeModal();
    },
    addRow: function (payload) {
      this.$store.commit({
        type: "addScrollTableRow",
        frontId: this.frontId,
        phaseId: this.phaseId,
        restID: this.exercise,
        ...payload,
      });

      this.closeModal();
    },
    delRow: function (payload) {
      let frenteId = this.frontId;
      let faseId = this.phaseId;
      let restriccionId = this.exercise;

      console.log(frenteId + " -- " + faseId + " -- " + restriccionId);
      let enviar = { codAnaResActividad: this.exercise };
      let point = this;

      store.dispatch("del_Restrictions", enviar).then((response) => {
        if (response.data.flag == 1) {
          this.setTimeifUpd(700, " Registro eliminado con exito ");

          point.$store.commit({
            type: "delScrollTableRow",
            frontId: frenteId,
            phaseId: faseId,
            activity: restriccionId,
            ...payload,
          });

          this.closeModal();
        } else {
          console.log("Tenemos errores al eliminar");
        }
      });
    },
    duplicateRow: function (payload) {
      // console.log(payload)
      let frenteId = payload.frontId;
      let faseId = payload.phaseId;
      let restriccionId = payload.exercise;

      let point = this;
      store.dispatch("dup_Restrictions", restriccionId).then((response) => {
        if (response.data.flag == 1) {
          this.setTimeifUpd(600, " Registro duplicado con exito ");
          let codAnaResActividad     = response.data.resultado;
          let dayFechaIdentificacion = response.data.dayFechaIdentificacion;

          point.$store.commit({
            type: "duplicateScrollTableRow",
            frontId: frenteId,
            phaseId: faseId,
            activity: restriccionId,
            didentificacion : dayFechaIdentificacion,
            codAna: codAnaResActividad,
            ...payload,
          });
        } else {
          console.log("Tenemos errores al duplicar registros");
        }
      });
    },
    addRowModal: function (payload) {
      this.openModal({
        param: "addRow",
        frontId: payload.frontId,
        phaseId: payload.phaseId,
      });
    },
    addRowData: function (payload) {
      this.openModal({
        param: "addRowData",
        frontId: payload.frontId,
        phaseId: payload.phaseId,
      });
    },
    saveRowfromForm: function (data) {
      let enviar = [];
      let payload = [];

      let dr = data.row["dayFechaRequerida"];
      let dayFechaRequerida_str = dr != "" ? dr + " " + "12:00:00" : "";
      let dc = data.row["dayFechaConciliada"];
      let dayFechaConciliada_str = dc != "" ? dc + " " + "12:00:00" : "";

      data.row["dayFechaRequerida"] = dayFechaRequerida_str;
      data.row["dayFechaConciliada"] = dayFechaConciliada_str;
      data.row["codAnaResActividad"] = -999;

      let data2 = data.row;
      data2["codAnaResFase"] = data.phaseId;
      data2["codAnaResFrente"] = data.frontId;
      data2["idrestriccion"] = 0;

      enviar.push(data2);
      let point = this;
      this.$store.dispatch("update_restricciones", enviar).then((response) => {
        if (response.data.flag == 1) {
          if (response.data.inserciones.length > 0) {
            let codNuevo            = response.data.inserciones[0]["idReal"];
            let fechaIdentificacion = response.data.inserciones[0]["fechaIdentificacion"];

            data.row["codAnaResActividad"]  = codNuevo;
            data.row["fechaIdentificacion"] = fechaIdentificacion;

            point.$store.commit({
              type: "saveRowfromForm",
              frontId: data.frontId,
              phaseId: data.phaseId,
              data: data,
              ...payload,
            });

            point.closeModal();
            point.setTimeifUpd(500, "Se inserto nuevo registro");
          }
        }
      });
    },
    updateRow: function (data) {

      // return;
      let frontIdx = data.frontIdx;
      let phaseIdx = data.phaseIdx;
      let enviar = this.restrictionsUpd;
      if (enviar.length > 0) {
        this.$store
          .dispatch("update_restricciones", enviar)
          .then((response) => {
            if (response.data.flag == 1) {
              console.log(">>>> llegue a updateRow dd")
              console.log(response.data)
              console.log("cuantos_ registros tenemos "+enviar.length.toString())

              for (let i = 0; i < enviar.length; i++) {
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "desActividad"
                ] = enviar[i]["desActividad"];

                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "flgNoti"
                ] = 0;

                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "desRestriccion"
                ] = enviar[i]["desRestriccion"];
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "codTipoRestriccion"
                ] = enviar[i]["codTipoRestriccion"];
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "desTipoRestriccion"
                ] = enviar[i]["desTipoRestriccion"];

                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "dayFechaRequerida"
                ] = enviar[i]["dayFechaRequerida"].split(" ")[0];
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "dayFechaConciliada"
                ] = enviar[i]["dayFechaConciliada"].split(" ")[0];

                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "idUsuarioResponsable"
                ] = enviar[i]["idUsuarioResponsable"];
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "desUsuarioResponsable"
                ] = enviar[i]["desUsuarioResponsable"];
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "codAreaResponsable"
                ] = enviar[i]["codAreaResponsable"];
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "codEstadoActividad"
                ] = enviar[i]["codEstadoActividad"];
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "desEstadoActividad"
                ] = enviar[i]["desEstadoActividad"];
                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "isEnabled"
                ] = true;

                this.restrictions[enviar[i].idfrente]["listaFase"][
                  enviar[i].idfase
                ]["listaRestricciones"][enviar[i].idrestriccion][
                  "desUsuarioSolicitante"
                ] = this.solicitanteActual;

                if (response.data.inserciones.length > 0) {
                  let codAntiguo = 0;
                  let codNuevo = 0;

                  console.log(">>>>>> cuantos registros insertados tenemos ")
                  console.log(response.data.inserciones.length)

                  for (
                    let index1 = 0;
                    index1 < response.data.inserciones.length;
                    index1++
                  ) {
                    if (
                      response.data.inserciones[index1]["idPivote"] ==
                      this.restrictions[enviar[i].idfrente]["listaFase"][
                        enviar[i].idfase
                      ]["listaRestricciones"][enviar[i].idrestriccion][
                        "codAnaResActividad"
                      ]
                    ) {

                      console.log(">>>>>>> nuevo registro >>>>>>>>");
                      console.log(response.data.inserciones[index1]);

                      codNuevo            = response.data.inserciones[index1]["idReal"];
                      console.log(response.data.inserciones[index1]["fechaIdentificacion"]);


                      this.restrictions[enviar[i].idfrente]["listaFase"][
                        enviar[i].idfase
                      ]["listaRestricciones"][enviar[i].idrestriccion][
                        "codAnaResActividad"
                      ] = codNuevo;

                      this.restrictions[enviar[i].idfrente]["listaFase"][
                        enviar[i].idfase
                      ]["listaRestricciones"][enviar[i].idrestriccion][
                        "dayFechaIdentificacion"
                      ] = response.data.inserciones[index1]["fechaIdentificacion"];


                      break;
                    }
                  }

                  this.setTimeifUpd(500, "Se inserto nuevo registro");
                } else {
                  this.setTimeifUpd(500, "Se actualizo datos");
                }
              }

              this.restrictionsUpd = [];

              if (response.data.inserciones.length > 0) {
                let enviar2 = [];
                this.restrictions[frontIdx]["listaFase"][phaseIdx][
                  "listaRestricciones"
                ].forEach(function (item, key, mapObj) {
                  let data = {
                    index: key,
                    codAnaResActividad: item.codAnaResActividad,
                  };
                  enviar2.push(data);
                });

                this.movimientoRow(enviar2);
              }
            } else {
              console.log(">>>>>> No tenemos registros para actualizar.");
              console.log(response.data.mensaje);
            }
          });
      }
    },

    RegistrarCambioRow: function (data) {

      console.log(">>> llegamos a este punto en registrarCmabio")
      // console.log("idfrente :"+data.idfrente+" || idfase :"+data.idfase+" || codActividad : "+datafinal["codAnaResActividad"]+" || isupdate : "+datafinal["isupdate"])
      console.log(data)
      let idfrente = data.idfrente;
      let idfase = data.idfase;
      let idrestriccion = 0; //data.idrestriccion
      let datafinal = data.data;
      let codActividad = datafinal["codAnaResActividad"];
      let isupdate = datafinal["isupdate"];

      let llave = null;
      let unid = -1;

      let restriccionesData =
        this.restrictions[idfrente]["listaFase"][idfase]["listaRestricciones"];
      for (var j = 0; j < restriccionesData.length; j++) {
        if (restriccionesData[j]["codAnaResActividad"] == codActividad) {
          idrestriccion = j; //llave = key;
          break;
        }
      }

      if (isupdate == false) {
        this.restrictionsUpd.forEach(function (item, key, mapObj) {
          if (item.codAnaResActividad == codActividad) {
            llave = key;
            unid = 1;
          }
        });

        this.restrictionsUpd.splice(llave, unid);
      } else {
        this.restrictionsUpd.forEach(function (item, key, mapObj) {
          if (item.codAnaResActividad == codActividad) {
            llave = key;
            unid = 1;
          }
        });

        console.log(">>> llegamos a este punto en registrarCmabio2")
        this.restrictionsUpd.splice(llave, unid);

        let dr = datafinal["dayFechaRequerida"];
        let dayFechaRequerida_str = dr != "" ? dr + " " + "12:00:00" : "";
        let dc = datafinal["dayFechaConciliada"];
        let dayFechaConciliada_str = dc != "" ? dc + " " + "12:00:00" : "";
        //let dayFechaConciliada_str =  (dc != "") ? dc.getFullYear().toString()+"-"+((dc.getMonth()+1).toString().length==2?(dc.getMonth()+1).toString():"0"+(dc.getMonth()+1).toString())+"-"+(dc.getDate().toString().length==2?dc.getDate().toString():"0"+dc.getDate().toString())+" "+"12:00:00":"";

        let codAnaResFrente = this.restrictions[idfrente]["codFrente"];
        let codAnaResFase =
          this.restrictions[idfrente]["listaFase"][idfase]["codFase"];
        let codAnaResActividad =
          this.restrictions[idfrente]["listaFase"][idfase][
            "listaRestricciones"
          ][idrestriccion]["codAnaResActividad"];

        console.log(">>> llegamos a este punto en registrarCmabio 3")
        let row = {
          idfrente: idfrente,
          idfase: idfase,
          idrestriccion: idrestriccion,
          columna: datafinal["column"],
          codAnaResFrente: codAnaResFrente,
          codAnaResFase: codAnaResFase,
          codAnaResActividad: codAnaResActividad,
          desActividad: datafinal["desActividad"],
          desRestriccion: datafinal["desRestriccion"],
          codTipoRestriccion: datafinal["codTipoRestriccion"],
          desTipoRestriccion: datafinal["desTipoRestriccion"],
          dayFechaRequerida: dayFechaRequerida_str,
          dayFechaConciliada: dayFechaConciliada_str,
          idUsuarioResponsable: datafinal["idUsuarioResponsable"],
          desUsuarioResponsable: datafinal["desUsuarioResponsable"],
          codEstadoActividad: datafinal["codEstadoActividad"],
          desEstadoActividad: datafinal["desEstadoActividad"],
          numOrden: datafinal["numOrden"],
        };

        this.restrictionsUpd.push(row);
      }

      // console.log(">>> Verificamos lo que enviamos  ")
      // console.log(this.restrictionsUpd)
    },
    revision: function () {
      console.log(this.restrictions);
    },
    movimientoRow: function (data) {
      this.$store.dispatch("update_numOrden", data).then((response) => {
        console.log(response);

        if (response.data.estado == true) {
          this.setTimeifUpd(500, "Nuevo Orden Actualizado");
        }
      });
    },

    setTimeifUpd: function (time, mensaje) {
      this.showifUpd = true;
      this.showifUpdMsg = mensaje;

      setTimeout(() => {
        this.showifUpd = false;
      }, time);
    },

    updalidarUpd: function (data) {
      console.log(">>>> mandamos a actualizar el valor ::: " + data);
      this.validarUpd = data;
    },

    filterSectionHeight() {
      const filterSectionDOM = document.getElementById("filterSection");
      this.heigthDiv = filterSectionDOM
        ? filterSectionDOM.offsetHeight + filterSectionDOM.offsetHeight * 0.5
        : 0;
    },

    selFilterOpt(payload) {
      if (payload.selType == "clean") {
        this.FilterActiveFlag = false;
        this.FilterActiveData = [];
        this.statusDraggable = false;

        this.filterOpen = !this.filterOpen;

        return;
      }

      //this.filterOpen = !this.filterOpen;
      if (payload.selType && payload.selType !== "tree") {
        this.filterName = payload.name;
      }
      this.treeOpen = !this.treeOpen;

      let projectId = sessionStorage.getItem("constraintid");

      this.treeOptions = [];
      this.treeIndex = this.options.findIndex(
        (option) => option.value === payload.value
      );
      console.log(payload);

      switch (this.treeIndex) {
        /* 'Responsable' */
        case 0:
          store
            .dispatch("get_resprojectuser", {
              projectId: projectId,
              responsible: true,
            })
            .then((response) => {
              console.log(response);
              response.forEach((r) => {
                let option = {
                  value: r.codProyIntegrante,
                  name: r.desCorreo,
                };
                this.treeOptions.push(option);
              });
            });
          break;
        /* 'Solicitante' */
        case 1:
          store
            .dispatch("get_proy_applicant", { projectId: projectId })
            .then((response) => {
              response.forEach((r) => {
                let option = {
                  value: r.id,
                  name: r.email,
                };
                this.treeOptions.push(option);
              });
            });
          break;
        /* 'Vencimiento' */
        case 2:
          this.treeOptions.push({
            value: 1,
            name: "Con retraso",
          });
          break;
        /* 'Tipo de restriccion' */
        case 3:
          console.log(">>>> entrando a tipo de restriccion");
          this.treeOptions = this.$store.state.Restrictionlist;
          break;
        default:
          break;
      }
    },
    selTreeOpt: function (payload) {
      this.FilterActiveFlag = true;
      this.FilterActiveData = payload;
      this.statusDraggable = true;

      this.filterOpen = !this.filterOpen;
    },
    getResponsibleRows(payload) {
      // return this.$store.getters.getResponsibleRows(payload);
      return this.$store.state.whiteproject_rows.map((row) => {
        return {
          ...row,
          listaFase: row.listaFase.map((fase) => {
            return {
              ...fase,
              listaRestricciones: fase.listaRestricciones.filter(
                (restriction) =>
                  restriction.idUsuarioResponsable === payload.value
              ),
            };
          }),
        };
      });
    },
    getApplicantRows(payload) {
      // return this.$store.getters.getApplicantRows();
      let applicantId = sessionStorage.getItem("Id");
      return this.$store.state.whiteproject_rows.map((row) => {
        return {
          ...row,
          listaFase: row.listaFase.map((fase) => {
            return {
              ...fase,
              listaRestricciones: fase.listaRestricciones.filter(
                (restriction) =>
                  restriction.codUsuarioSolicitante === applicantId
              ),
            };
          }),
        };
      });
    },
    getExpirationRows(payload) {
      console.log(payload);
      // return this.$store.getters.getExpirationRows(payload);
      let res = this.$store.state.whiteproject_rows.map((row) => {
        return {
          ...row,
          listaFase: row.listaFase.map((fase) => {
            return {
              ...fase,
              listaRestricciones: fase.listaRestricciones.filter(
                (restriction) =>
                  restriction.codEstadoActividad != this.$store.state.anaEstado.find(
                      (estado) => estado.desEstado == "Completado"
                    ).codEstado &&
                  new Date(restriction.dayFechaConciliada) < new Date()
              ),
            };
          }),
        };
      });

      return res;
    },
    getResTypeRows(payload) {
      console.log(payload);
      //return this.$store.getters.getResTypeRows(payload);
      return this.$store.state.whiteproject_rows.map((row) => {
        return {
          ...row,
          listaFase: row.listaFase.map((fase) => {
            return {
              ...fase,
              listaRestricciones: fase.listaRestricciones.filter(
                (restriction) =>
                  restriction.codTipoRestriccion === payload.value
              ),
            };
          }),
        };
      });
    },
    countActivities(codFrente) {
      let total = 0;
        let completed = 0;

        // Encuentra el frente correspondiente
        let frente = this.$store.state.whiteproject_rows.find(f => f.codFrente === codFrente);
        if (frente) {
            // Recorre cada fase en el frente
            frente.listaFase.forEach(fase => {
                // Añade el número de restricciones en la fase al total
                total += fase.listaRestricciones.length;
                // Añade el número de actividades completadas al total de completadas
                completed += fase.listaRestricciones.filter(res => res.codEstadoActividad === "3").length;
            });
        }

        // Calcula y redondea el porcentaje
        let percentage = Math.round((completed / total) * 100);

        // Determina la clase de color
        let colorClass = percentage === 100 ? 'green' : (percentage >= 20 ? 'orange' : 'red');

        return {percentage, colorClass};
    },
    ResizeActually() {
      this.sizeActually = window.innerWidth;
    },
    callMounted: async function () {
      window.addEventListener("resize", this.ResizeActually);
      this.ResizeActually();

      await store.dispatch("get_infoPerson");
      console.log(">> entro 1");
      await store.dispatch("getNameProy").then((response) => {
        this.nameProyecto = response;
      });
      console.log(">> entro 2");
      await store.dispatch("get_datos_restricciones").then((response) => {
        this.statusRestriction = this.$store.state.estadoRestriccion;
        this.rolProyecto       = this.$store.state.rolProyecto;

        this.$store.state.sidebar = false;

        if (this.statusRestriction === false) {

          this.disabledItems   = true;
          this.statusDraggable = true;
          this.disabledItemsEnviarCorreos = true;


        } else {

          this.disabledItems   = false;
          this.statusDraggable = false;

            /* Si el rol no es creador , ni administrador */
            if (this.rolProyecto != 0 && this.rolProyecto != 3){
              this.disabledItems = true;
              this.disabledItemsEnviarCorreos = false;
            }

        }



      });
      console.log(">> entro 3");
      await this.filterSectionHeight();

      this.pageloadflag = true;
    },
  },
  computed: {
    isDisabled: function () {
      return this.disabledItems;
    },
    // rowsCant: function(){
    //   return this.$store.state.whiteproject_rows.length
    // },
    countNotNoti: function (){
      let res      = this.$store.state.whiteproject_rows;
      let contador = 0;

      res.forEach(obj => {
        obj.listaFase.forEach(fase => {
          fase.listaRestricciones.forEach(restriccion => {
            // console.log(">>>>>>>")
            // console.log(restriccion)
            if (restriccion.flgNoti === 0) {
              contador++;
            }
          });
        });
      });

      return contador.toString();

    },
    solicitanteActual: function(){
      return this.$store.state.solicitanteActual;
    },
    rows: function () {
      let res = this.$store.state.whiteproject_rows;
      this.restrictions = res;

      if (this.FilterActiveFlag == false) {
        return this.restrictions;
      } else {
        switch (this.treeIndex) {
          /* 'Responsable' */
          case 0:
            return this.getResponsibleRows(this.FilterActiveData);
            break;
          /* 'Solicitante' */
          case 1:
            return this.getApplicantRows(this.FilterActiveData);
            break;
          /* 'Vencimiento' */
          case 2:
            return this.getExpirationRows(this.FilterActiveData);
            break;
          /* 'Tipo de restriccion' */
          case 3:
            console.log(">>>> entrando a la restriccion");
            return this.getResTypeRows(this.FilterActiveData);

            break;
          default:
            return [];
            break;
        }
      }
    },
    hideCols: function () {
      return this.$store.state.hiddenCols; //this.listhideCols; //this.$store.getters.hideCols({id: this.frontId, phaseId: this.phaseId});
    },
    isLoadingTrue() {
      return true;
    },
    isLoading: function () {
      return this.pageloadflag;
    },

    // hideCols: function() {
    //   return this.$store.getters.hideCols({id: this.frontId, phaseId: this.phaseId});
    // }
  },
  mounted: async function () {
    await this.callMounted();
    // window.addEventListener('resize', this.ResizeActually);
    // this.ResizeActually()

    // await store.dispatch('get_infoPerson');
    // console.log(">> entro 1")
    // await store.dispatch('getNameProy').then((response) => {
    //   this.nameProyecto = response
    // });
    // console.log(">> entro 2")
    // await store.dispatch('get_datos_restricciones').then((response) => {

    //   this.statusRestriction    = this.$store.state.estadoRestriccion;
    //   this.$store.state.sidebar = false;

    //   if (this.statusRestriction === false){
    //     this.disabledItems   = true
    //     this.statusDraggable = true
    //   }else{
    //     this.disabledItems   = false
    //     this.statusDraggable = false


    //   }



    // });
    // console.log(">> entro 3")
    // await this.filterSectionHeight()

    // this.pageloadflag = true
  },

  created: function () {},
  beforeMount: function () {
    // this.filterSectionHeight()
  },
  // updated:function(){
  //   this.filterSectionHeight()
  // }
};
</script>
<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to
/* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}

.bar {
    width: 200px;
    height: 20px;
    border-radius: 10px;
    background-color: #f0f0f0; /* Un color de fondo para la parte no llena de la barra */
}

.bar .filled {
    height: 20px;
    border-radius: 10px;
    text-align: center;
    font-size: 0.6em;
    line-height: 20px;
    color: white;
}

.green {
    background-color: #189118;
}

.orange {
    background-color: #e38b1d;
}

.red {
    background-color: #c71616;
}

</style>
