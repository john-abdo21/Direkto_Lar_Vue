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

    <!-- <div class="h-[25px] w-[60px]">
      <FlagSelect v-model="valor_defecto" @change="capturamos_veremos" disabled="true" />
    </div> -->

    <br>



    <div class="flex flex-col">
      <div class="flex justify-start"  v-if="!fullScreen">





      <!-- <div @click="mverificamos"> hacer clicks para probar</div> -->
      <div class="flex justify-between space-x-4">
        <!-- Primer bloque - Indicador principal en Card -->
        <div
              class="flex-1 text-white rounded-lg p-1 shadow-md"
              :class="getBgColor"
          >
            <h2 class="text-sm text-center">Avance General</h2>
            <h3 class="text-lg text-center">{{indicadorAvanceGeneral}}%</h3>
            <div class="h-2 bg-white mt-2">
            <div
                class="h-full " v-bind:style="{ width: indicadorAvanceGeneral + '%' }"
                :class="getBgColorBold"
            ></div>

          </div>
        </div>

        <!-- Segundo bloque - 2 indicadores en Barra -->
        <div class="space-y-2 w-[12em]">
          <div class="text-xxs flex ">
            <span class="mr-2 flex-col  ">Tiempo de Anticipacion <b>(Promedio Total)</b></span>

            <!-- <span class="ml-2 text-md w-[20px] ws_green400"></span> -->
            <div class="ml-2 text-center">
            <div class="flex w-11 h-10 flex-none items-center justify-center rounded-lg bg-gray-100 group-hover:bg-white text-sm">
              {{indicadorAnticipacion}}

            </div>
            dias
          </div>

          </div>
        </div>

        <div class="space-y-2 w-[12em]">
          <div class="text-xxs flex ">
            <span class="mr-2 flex-col  ">Tiempo de Cumplimiento <b>(Promedio Total)</b></span>

            <!-- <span class="ml-2 text-md w-[20px] ws_green400"></span> -->
            <div class="ml-2 text-center">
            <div class="flex w-11 h-10 flex-none items-center justify-center rounded-lg bg-gray-100 group-hover:bg-white text-sm">
              {{indicadorCumplimiento}}

            </div>
            dias
          </div>

          </div>
        </div>

  <!-- Tercer bloque - 2 indicadores en Barra -->
  <div class="flex-1 flex flex-col space-y-2 w-[12em]">
    <!-- Aquí puedes agregar los dos indicadores adicionales siguiendo el formato anterior -->
  </div>
</div>








    </div>

      <br>

    <div class=" flex  justify-between  sm:flex-col">
      <!-- Sección izquierda -->
      <div class=" flex w-[50%] sm:w-full " v-if="!fullScreen">
        <button
            :disabled="isDisabled"
            class="bg-white w-[18%]  sm:w-[25%] h-[30px] text-[0.6rem] hover:bg-gray-100 px-2 py-1 border border-orange rounded shadow text-orange"
            @mouseover="hoverEffect" @mouseleave="removeHoverEffect"
            @click="openModal({ param: 'addFront' })"
            :class="{
                'border-orange': !isDisabled,
                'border-[#DCE4F9]': isDisabled,
              }"

            >
            <i class="fas fa-plus-circle"></i> Agregar frente
        </button>

        <button
          class="bg-white w-[18%] sm:w-[25%] h-[30px] text-[0.6rem] hover:bg-gray-100 px-2 py-1 border border-orange rounded shadow text-orange"
          @mouseover="hoverEffect" @mouseleave="removeHoverEffect"
          :disabled="isDisabled"
          @click="openModal({ param: 'addPhase' })"
          :class="{
                'border-orange': !isDisabled,
                'border-[#DCE4F9]': isDisabled,
              }"

          >
          <i class="fas fa-plus-circle"></i> Agregar fase
        </button>

        <button
          class="bg-white w-[18%] sm:w-[25%] h-[30px] text-[0.6rem] hover:bg-gray-100 px-2 py-1 border border-orange rounded shadow text-orange"
          @mouseover="hoverEffect" @mouseleave="removeHoverEffect"
          :disabled="isDisabled"
          :class="{
                'border-orange': !isDisabled,
                'border-[#DCE4F9]': isDisabled,
              }"
          @click="openModal({ param: 'deleteFront' })"

          >
          <i class="fas fa-trash"></i> Eliminar
        </button>

        <button
          class="bg-white w-[18%] sm:w-[25%] h-[30px] text-[0.6rem] hover:bg-gray-100 px-2 py-1 border border-orange rounded shadow text-orange relative"
          @mouseover="hoverEffect" @mouseleave="removeHoverEffect"
          @click="openModal({ param: 'enviarNoti' })"
          :disabled="disabledItemsEnviarCorreos"
          :class="{
                'border-orange': !disabledItemsEnviarCorreos,
                'border-[#DCE4F9]': disabledItemsEnviarCorreos,
              }"

          >
          <i class="fas fa-envelope"></i> Enviar Correos
          <span class="badge absolute top-[-2] right-[-4] h-4 w-4 bg-red400-500 rounded-full text-white text-center text-tinysm min-w-[10px]" >{{countNotNoti}}</span>
        </button>

      </div>
      <div class=" flex  w-[50%] sm:w-full" v-if="fullScreen">
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

      <div class=" flex  w-[50%] sm:w-full justify-end" v-if="!fullScreen">
        <div class=" flex flex-col w-[60%] sm:w-full space-x-1" v-if="!isDisabled ">
          <div class="flex-1 flex justify-end text-xs ">
            <button class="px-2 py-1 border border-gray-400 rounded hover:bg-gray-100 w-[35%] h-[30px] text-[0.55rem]" @click="download_template_for_project">
              <i class="fas fa-file-download"></i> Descargar Plantilla
            </button>
            <button class="px-2 py-1 border border-gray-400 rounded hover:bg-gray-100 w-[32%] h-[30px] text-[0.55rem]" @click="openModal({ param: 'uploadExcel' })">
              <i class="fas fa-file-upload"></i> Subir Plantilla
            </button>
            <button class="px-2 py-1 border border-gray-400 rounded hover:bg-gray-100 w-[25%] h-[30px] text-[0.55rem]" @click="download_reporte_for_project">
              <i class="fas fa-file-download"></i> Reporte
            </button>
          </div>
        </div>

        <div class=" flex flex-col w-[40%] sm:w-full ">
            <div class="flex-1 relative z-10">
              <i class="fas fa-filter absolute right-3 top-2 text-orange cursor-pointer" @click="toggleFilterOptions"></i>

              <input type="text" v-model="search" @input="filterOptions" placeholder="Filtro .. " class="h-[30px] px-2 py-1 border border-[#8A9CC9] rounded text-xs w-full focus:outline-none focus:ring-2 focus:ring-blue-200 text-[0.6rem]">

              <!-- Lista de filtros seleccionados -->
              <div v-for="(filter, index) in selectedFilters" :key="index" class="mt-1 px-2 py-1 border border-gray-300 rounded flex justify-between font-normal text-xs">
                <span>{{ filter }}</span>
                <i class="fas fa-times cursor-pointer " @click="removeFilter(index)"></i>
              </div>

              <transition name="fade">

              <div class="absolute left-0 mt-1 w-full bg-white rounded shadow-lg text-[0.8rem]" v-if="showOptions" ref="dropdown">
                <div v-for="(option, index) in visibleOptions" :key="index" class="px-2 py-1 cursor-pointer mb-2 shadow-sm"  @click="optionClicked(option)">
                  <div class="font-normal flex justify-between">
                    <span>{{option.name}}</span>
                    <span v-if="option.subOptions">
                    <i :class="option.showSubOptions ? 'fas fa-chevron-down' : 'fas fa-chevron-right'"></i>
                    </span>
                  </div>
                  <div v-if="option.subOptions && option.showSubOptions" class="pl-2">
                  <div v-for="(subOption) in option.subOptions" :key="subOption.id" class="px-2 py-1 cursor-pointer hover:bg-blue-100 font-normal text-xs" @click.stop="selectOption(option, subOption)">
                    {{subOption.name}}
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


    <br>
    <div class="flex flex-col">
      <!-- <div v-if="!fullScreen"> -->
      <div v-if="!fullScreen">
        <div v-for="(frente, index1) in rows" :key="index1">
          <!-- <hr v-if="index1 !== 0" class="mb-6 bg-[#D0D9F1]" /> -->
          <div
        :class="{
            'border-t-2': index1 === 0,
            'border-b-2': index1 === rows.length - 1,
            'border-t': index1 !== 0,
            'border-l-2 border-r-2':  1==1,
            'rounded-t-lg': index1 === 0,
            'rounded-b-lg': index1 === rows.length - 1
        }"
        class="pl-2 pb-2 pr-2 border-gray-300 flex justify-between items-center sm:w-full cursor-pointer"
        @click="toggleOpen(frente.desFrente)"
    >
            <span class="mt-1 text-sm text-activeText font-bold">
              {{ frente.desFrente }}


                  <div class="!h-3 !w-25 bar">
                      <div class="!h-3 filled"
                          :class="countActivities(frente.codFrente).colorClass"
                          :style="{ width: countActivities(frente.codFrente).percentage + '%' }">
                          {{ countActivities(frente.codFrente).percentage }}%
                      </div>
                  </div>


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
            class="flex flex-col  sm:pl-4 border-l-2 border-r-2"
            v-if="frente.isOpen"
          >
            <div
              class="flex flex-col mb-4 pl-8 sm:pl-4 bg-[#f8f9f9] "
              v-for="(fase, index2) in frente.listaFase"
              :key="index2"
            >
              <div
                 :class="{
                  'border-t-2': index2 === 0,
                  'border-b-2': !fase.isOpen,
                  'border-l-2 border-r-2':  1==1,
                  'rounded-t-lg': index2 === 0,
                  'rounded-b-lg': !fase.isOpen,

                 }"
                 class="pl-2 pb-2 pr-2 border-gray-300 sm:w-full cursor-pointer"
                 @click="toggleOpenFase(frente.codFrente , fase.codFase)"
              >
                <span class="text-[0.7rem] leading-7 text-activeText shrink-0">
                  {{ fase.desFase }}
                </span>
                <span
                  class="ml-[100px] sm:ml-8 text-[0.7rem] leading-7 text-activeText"
                >


                  No retrasadas: {{get_noretrasados(fase.listaRestricciones)}}
                  <span class="sm:hidden">/</span> <span style="color:#d50505">Retrasadas:
                    {{get_retrasados(fase.listaRestricciones)}}</span>
                </span>
              </div>

              <div
                  class=" border-l-2 border-r-2 border-gray-300"
                  :class = "{
                     'rounded-b-lg': index2 === frente.listaFase.length - 1,

                  }"
                  v-if="fase.isOpen"
              >
                <div class="flex sm:flex-col justify-between sm:mb-10">
                  <div
                    class="flex mt-1 mb-3 items-start cursor-pointer"
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
                    <span class="text-[0.6rem] text-[#002B6B]"
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
                  class="outer relative border border-[#D0D9F1] rounded-lg before:w-18 before:absolute before:h-full before:shadow-tooltip"
                >
                  <div
                    class="inner overflow-scroll overflow-hidden ml-16"
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
    <ConfirmBloq
      :bloq="bloqConfirmNotification"
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
// import { AdjustmentsIcon } from "@vue-hero-icons/solid"
import moment from 'moment'
import * as XLSX from "xlsx"
import FileSaver from 'file-saver';
import excelJs from 'exceljs'
import exportFromJSON from "export-from-json";
import Breadcrumb from "../../components/Layout/Breadcrumb.vue";

import AddFront from "../../components/AddFront.vue";
import AddPhase from "../../components/AddPhase.vue";
import DataTableRestricciones from "../../components/DataTableRestricciones.vue";
// import DataTableRestriccionesRow from "../../components/DataTableRestriccionesRow.vue";
// import RestrictionPerson from "../../components/RestrictionPerson.vue";

import ToggleColumn from "../../components/ToggleColumn.vue";
import AddRow from "../../components/AddRow.vue";
import DeleteRow from "../../components/DeleteRow.vue";
import UploadExcel from "../../components/UploadExcel.vue";
import ConfirmBloq from "../../components/ConfirmBloq.vue";
// import DownloadReport from "../../components/DownloadReport.vue";
import SelectOption from "../../components/SelectOption.vue";
import DeleteFront from "../../components/DeleteFront.vue";
import Loading from "vue-loading-overlay";

import AddRowData from "../../components/AddRowData.vue";
import FlagSelect from '../../components/FlagSelect.vue';

import store from "../../store";
import '@fortawesome/fontawesome-free/css/all.css'
export default {
  name: "white-project-component",
  components: {
    FlagSelect,
    Loading,
    Breadcrumb,
    AddFront,
    AddPhase,
    DataTableRestricciones,
    // DataTableRestriccionesRow,
    AddRow,
    DeleteRow,
    DeleteFront,
    UploadExcel,
    ConfirmBloq,
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
      valor_defecto:2,
      data_array: [],
      bloqConfirmNotification:false,
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
      areaUsuario:0,

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



      search: '',
      showOptions: false,
      options: [
        { name: 'Estado', visible: false ,subOptions: [], showSubOptions: false },
        { name: 'Responsables', visible: false ,subOptions: [], showSubOptions: false },
        { name: 'Solicitantes', visible: false, subOptions: [], showSubOptions: false },
        { name: 'Vencimiento', visible: false , subOptions: [], showSubOptions: false },
        { name: 'Tipo de Restricción', visible: false ,subOptions: [], showSubOptions: false }
      ],
      anyResults: false,
      selectedFilters: [],

      options0: [
        {
          name: "Estado",
          value: "estado",
        },
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
      idxPhase_pivot:0,



      optionFilterIndex:0,
      optionFilterSelected : null,
      optionSubFilterSelected : null,

      indAvanceGeneral:0
    };
  },
  methods: {

    capturamos_veremos(data){
      console.log(">>>>> entrada")
      console.log(this.valor_defecto)
    },

    mverificamos(){
      console.log(">> impresion de la var. restricciones")
      console.log(this.restrictions)

      console.log(">> impresion de la var. rows")
      console.log(this.rows)


    },

   get_retrasados(datos){
    let conteo            = 0
    try {

      const hoy     = new Date();
       conteo        = datos.filter(item => parseInt(item.codEstadoActividad,10) < 3 && new Date(item.dayFechaConciliada+ "T00:00:00-05:00") < hoy).length;

    } catch (error) {

    }
    return conteo;
   },
   get_noretrasados(datos){
    let conteo            = 0
    try {

      const hoy    = new Date().toLocaleString("es-PE",{ hourCycle: 'h24'});
      // let fecha_comparacion = new Date(item.dayFechaRequerida).toLocaleString("es-PE",{ hourCycle: 'h24'})
      conteo       = datos.filter(item => parseInt(item.codEstadoActividad,10) < 3 && new Date(item.dayFechaConciliada+ "T00:00:00-05:00").toLocaleString("es-PE",{ hourCycle: 'h24'}) > hoy).length;

    } catch (error) {

    }

    return conteo;
   },
   download_reporte_for_project(){

    store.dispatch("report_restrictions_for_project");

   },

   download_template_for_project(payload) {
    store.dispatch("get_datos_restricciones", payload).then((response) => {
      const Frente = [];
      const Fase = [];
      const TipoRestriccion = [];
      const Responsable = [];
      const Estado = [];
      const Solicitante = [];
      for(let i = 0; i < response.restricciones.length; i ++){
        Frente[i] = response.restricciones[i]['desFrente'];
        if (response.restricciones[i]['listaFase'].length > 0){

          Fase[i]   = response.restricciones[i]['listaFase'][0]['desFase'];

        }

      }
      for(let k = 0; k < response.tipoRestricciones.length; k ++){
        TipoRestriccion.push(response.tipoRestricciones[k]['desTipoRestricciones']);
      }
      for (let h = 0; h < response.estados.length; h ++){
        Estado[h] = response.estados[h]['desEstado'];
      }

      for (let h = 0; h < response.integrantesAnaReS.length; h ++){
        Responsable[h] = response.integrantesAnaReS[h]['desProyIntegrante'];
      }

      Solicitante.push(response.solicitanteActual);
      const data_array = [
        {
          "A": Frente,
          "B": Fase,
          "C": "",
          "D": "",
          "E": TipoRestriccion,
          "F": "",
          "G": "",
          "H": Responsable,
          "I": Estado,
          "J": Solicitante
        }
      ];

      const workbook = new excelJs.Workbook();
      const ws = workbook.addWorksheet('Restricciones');
      ws.addRow(["Frente", "Fase", "Actividad", "Restriccion", "Tipo Restriccion", "Fecha Requerida", "Fecha Conciliada", "Responsable", "Estado", "Solicitante"]);
      ws.addRow(["", "", "", "", "", "", "", "", "", ""]);

      const header_row = ws.getRow(1);
      header_row.height = 22;


      const columnA = ws.getColumn('A');
      columnA.width = 16;

      const columnB = ws.getColumn('B');
      columnB.width = 16;

      const columnC = ws.getColumn('C');
      columnC.width = 12;

      const columnD = ws.getColumn('D');
      columnD.width = 12;

      const columnE = ws.getColumn('E');
      columnE.width = 20;

      const columnF = ws.getColumn('F');
      columnF.width = 16;

      const columnG = ws.getColumn('G');
      columnG.width = 16

      const columnH = ws.getColumn('H');
      columnH.width = 16;

      const columnI = ws.getColumn('I');
      columnI.width = 12;

      const columnJ = ws.getColumn('J');
      columnJ.width = 14;


      const fill_data = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'A5A5A5' },
      };
      const font_data =  {
        color: { argb: '000000', bold: true },
      };
      const alignment_data = { vertical: 'middle', horizontal: 'center' };
      const border_data = {
                              top: {style:'thin'},
                              left: {style:'thin'},
                              bottom: {style:'thin'},
                              right: {style:'thin'}
                          };


      const header_frente = ws.getCell('A1');
      header_frente.fill = fill_data
      header_frente.font = font_data
      header_frente.alignment = alignment_data;
      header_frente.border    = border_data;

      const header_Fase = ws.getCell('B1');
      header_Fase.fill = fill_data
      header_Fase.font = font_data
      header_Fase.alignment = alignment_data;
      header_Fase.border    = border_data;

      const header_Actividad = ws.getCell('C1');
      header_Actividad.fill = fill_data
      header_Actividad.font = font_data
      header_Actividad.alignment = alignment_data;
      header_Actividad.border    = border_data;

      const header_Restriccion = ws.getCell('D1');
      header_Restriccion.fill = fill_data
      header_Restriccion.font = font_data
      header_Restriccion.alignment = alignment_data;
      header_Restriccion.border    = border_data;


      const header_Tipo = ws.getCell('E1');
      header_Tipo.fill = fill_data
      header_Tipo.font = font_data
      header_Tipo.alignment = alignment_data;
      header_Tipo.border    = border_data;

      const header_FechaR = ws.getCell('F1');
      header_FechaR.fill = fill_data
      header_FechaR.font = font_data
      header_FechaR.alignment = alignment_data;
      header_FechaR.border    = border_data;

      const header_FechaC = ws.getCell('G1');
      header_FechaC.fill = fill_data
      header_FechaC.font = font_data
      header_FechaC.alignment = alignment_data;
      header_FechaC.border    = border_data;

      const header_Responsable = ws.getCell('H1');
      header_Responsable.fill = fill_data
      header_Responsable.font = font_data
      header_Responsable.alignment = alignment_data;
      header_Responsable.border    = border_data;

      const header_Estado = ws.getCell('I1');
      header_Estado.fill = fill_data
      header_Estado.font = font_data
      header_Estado.alignment = alignment_data;
      header_Estado.border    = border_data;

      const header_Soli = ws.getCell('J1');
      header_Soli.fill = fill_data
      header_Soli.font = font_data
      header_Soli.alignment = alignment_data;
      header_Soli.border    = border_data;




      for(let i = 2; i < 100; i ++){
        const validationCell_Frente = ws.getCell('A'+`"${i}"`);
        validationCell_Frente.dataValidation = {
          type: 'list',
          allowBlank: false,
          showDropDown: true,
          formulae: [`"${data_array[0].A.join(',')}"`],
        };

        const validationCell_Fase = ws.getCell('B'+`"${i}"`);
        validationCell_Fase.dataValidation = {
          type: 'list',
          allowBlank: false,
          showDropDown: true,
          formulae: [`"${data_array[0].B.join(',')}"`],
        };

        const validationCell_Tipo = ws.getCell('E'+`"${i}"`);
        validationCell_Tipo.dataValidation = {
          type: 'list',
          allowBlank: false,
          showDropDown: true,
          formulae: [`"${data_array[0].E.join(',')}"`],
        };

        const validationCell_Res = ws.getCell('H'+`"${i}"`);
        validationCell_Res.dataValidation = {
          type: 'list',
          allowBlank: false,
          showDropDown: true,
          formulae: [`"${data_array[0].H.join(',')}"`],
        };

        const validationCell_Estado = ws.getCell('I'+`"${i}"`);
        validationCell_Estado.dataValidation = {
          type: 'list',
          allowBlank: false,
          showDropDown: true,
          formulae: [`"${data_array[0].I.join(',')}"`],
        };

        const validationCell_Soli = ws.getCell('J'+`"${i}"`);
        validationCell_Soli.dataValidation = {
          type: 'list',
          allowBlank: false,
          showDropDown: true,
          formulae: [`"${data_array[0].J.join(',')}"`],
        };
      }

      // Save the workbook to a file
      workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        FileSaver.saveAs(blob, ('plantilla_'+this.nameProyecto+'.xlsx').toLowerCase());
      });

    });
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

    toggleOpenFase: function (codfrente , codfase) {

      this.rows.map((frente) => {


        if (frente.codFrente === codfrente) {

          console.log(frente)

          frente.listaFase.map((fase) => {

            if(fase.codFase === codfase){
              fase.isOpen  = !fase.isOpen;
            }

          });

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

      this.bloqConfirmNotification = true;
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
         this.bloqConfirmNotification = false;
         point.closeModal();


      });



    },

    deleteFront: function (payload) {
      console.log(payload);
      store.dispatch("delete_front", payload);
      this.closeModal();
    },

    addFront: function (payload) {
      payload['codAreaUsuario'] = this.areaUsuario
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

      payload['codAreaUsuario'] = this.areaUsuario

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

      payload['codAreaUsuario'] = this.areaUsuario

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

                /* Siempre se actualiza las nuevas restricciones se tiene que actualizar el codigo real en el registros */
                if (response.data.inserciones.length > 0) {
                  let codAntiguo = 0;
                  let codNuevo = 0;

                  // >>>>>> cuantos registros insertados tenemos

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


                      codNuevo            = response.data.inserciones[index1]["idReal"];

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

                    /* Despues ya actualizamos la fecha de levantamiento si es que tuviera*/
                if (response.data.actualizaciones.length > 0) {

                    for (
                      let index1 = 0;
                      index1 < response.data.actualizaciones.length;
                      index1++
                    )
                    {
                      if (
                        response.data.actualizaciones[index1]["idReal"] ==
                        this.restrictions[enviar[i].idfrente]["listaFase"][
                          enviar[i].idfase
                        ]["listaRestricciones"][enviar[i].idrestriccion][
                          "codAnaResActividad"
                        ]
                      ) {


                          this.restrictions[enviar[i].idfrente]["listaFase"][
                            enviar[i].idfase
                          ]["listaRestricciones"][enviar[i].idrestriccion][
                            "dayFechaLevantamiento"
                          ] = response.data.actualizaciones[index1]["fechaLevantamiento"];

                          break;

                        }

                    }
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
        const nuevasFases = row.listaFase.map((fase) => {
          const match = fase.listaRestricciones.some(
            (restriction) => restriction.idUsuarioResponsable === payload.id
          );

          return {
            ...fase,
            isOpen: match ? true : fase.isOpen,
            listaRestricciones: fase.listaRestricciones.filter(
              (restriction) => restriction.idUsuarioResponsable === payload.id
            ),
            shouldShow: match, // Agregamos un campo 'shouldShow' para saber si se debe mostrar
          };
        }).filter(fase => fase.shouldShow); // Filtramos las fases que no tienen coincidencias

        return {
          ...row,
          listaFase: nuevasFases,
          shouldShow: nuevasFases.length > 0 // Aquí determinamos si la fila debe mostrarse
        };
      }).filter(row => row.shouldShow); // Filtramos las filas que no tienen fases coincidentes

    },
    getApplicantRows(payload) {
      // return this.$store.getters.getApplicantRows();
      // let applicantId = sessionStorage.getItem("Id");
      return this.$store.state.whiteproject_rows.map((row) => {
        const nuevasFases = row.listaFase.map((fase) => {
          const match = fase.listaRestricciones.some(
            (restriction) => restriction.idUsuarioSolicitante === payload.id
          );

          return {
            ...fase,
            isOpen: match ? true : fase.isOpen,
            listaRestricciones: fase.listaRestricciones.filter(
              (restriction) => restriction.idUsuarioSolicitante === payload.id
            ),
            shouldShow: match, // Agregamos un campo 'shouldShow' para saber si se debe mostrar
          };
        }).filter(fase => fase.shouldShow); // Filtramos las fases que no tienen coincidencias

        return {
          ...row,
          listaFase: nuevasFases,
          shouldShow: nuevasFases.length > 0 // Aquí determinamos si la fila debe mostrarse
        };
      }).filter(row => row.shouldShow); // Filtramos las filas que no tienen fases coincidentes


    },
    getExpirationRows(payload) {

      return this.$store.state.whiteproject_rows.map((row) => {
        const nuevasFases = row.listaFase.map((fase) => {
          const match = fase.listaRestricciones.some(
            (restriction) =>  parseInt(restriction.codEstadoActividad,10) != this.$store.state.anaEstado.find(
                      (estado) => estado.desEstado == "Completado"
                    ).codEstado &&
                  new Date(restriction.dayFechaConciliada) < new Date()
          );

          return {
            ...fase,
            isOpen: match ? true : fase.isOpen,
            listaRestricciones: fase.listaRestricciones.filter(
              (restriction) => parseInt(restriction.codEstadoActividad,10) != this.$store.state.anaEstado.find(
                      (estado) => estado.desEstado == "Completado"
                    ).codEstado &&
                  new Date(restriction.dayFechaConciliada) < new Date()
            ),
            shouldShow: match, // Agregamos un campo 'shouldShow' para saber si se debe mostrar
          };
        }).filter(fase => fase.shouldShow); // Filtramos las fases que no tienen coincidencias

        return {
          ...row,
          listaFase: nuevasFases,
          shouldShow: nuevasFases.length > 0 // Aquí determinamos si la fila debe mostrarse
        };
      }).filter(row => row.shouldShow);


    },
    getResTypeRows(payload) {
      // console.log(payload);
      //return this.$store.getters.getResTypeRows(payload);
      return this.$store.state.whiteproject_rows.map((row) => {
        const nuevasFases = row.listaFase.map((fase) => {
          const match = fase.listaRestricciones.some(
            (restriction) => parseInt(restriction.codTipoRestriccion) === payload.id
          );

          return {
            ...fase,
            isOpen: match ? true : fase.isOpen,
            listaRestricciones: fase.listaRestricciones.filter(
              (restriction) => parseInt(restriction.codTipoRestriccion) === payload.id
            ),
            shouldShow: match, // Agregamos un campo 'shouldShow' para saber si se debe mostrar
          };
        }).filter(fase => fase.shouldShow); // Filtramos las fases que no tienen coincidencias

        return {
          ...row,
          listaFase: nuevasFases,
          shouldShow: nuevasFases.length > 0 // Aquí determinamos si la fila debe mostrarse
        };
      }).filter(row => row.shouldShow); // Filtramos las filas que no tienen fases coincidentes
    },

    getResEstados(payload) {

      return this.$store.state.whiteproject_rows.map((row) => {
        const nuevasFases = row.listaFase.map((fase) => {
          const match = fase.listaRestricciones.some(
            (restriction) => parseInt(restriction.codEstadoActividad) === payload.id
          );

          return {
            ...fase,
            isOpen: match ? true : fase.isOpen,
            listaRestricciones: fase.listaRestricciones.filter(
              (restriction) => parseInt(restriction.codEstadoActividad) === payload.id
            ),
            shouldShow: match, // Agregamos un campo 'shouldShow' para saber si se debe mostrar
          };
        }).filter(fase => fase.shouldShow); // Filtramos las fases que no tienen coincidencias

        return {
          ...row,
          listaFase: nuevasFases,
          shouldShow: nuevasFases.length > 0 // Aquí determinamos si la fila debe mostrarse
        };
      }).filter(row => row.shouldShow); // Filtramos las filas que no tienen fases coincidentes
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
                completed += fase.listaRestricciones.filter(res => parseInt(res.codEstadoActividad,10) == 3).length;
            });
        }

        // Calcula y redondea el porcentaje
        let percentage = Math.round((completed / total) * 100);

        // Determina la clase de color
        let colorClass = percentage === 100 ? 'bg-green400' : (percentage >= 20 ? 'bg-orange400' : 'bg-red400');

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
        this.areaUsuario       = this.$store.state.areaUsuario;

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

        /*Al iniciar la ventana cargamos los valores para los tipos de restricciones y de vencimiento,  estos no se modifican es data unica ,
          es mejor cargarlo una sola vez. */

          this.updateTipoRestricciones();
          this.updateVencimiento();
          this.updateEstados();
      });

      console.log(">> entro 3");
      await this.filterSectionHeight();
      this.pageloadflag = true;

    },

    /* TENEMOS FUNCIONES PARA EL FILTRO */

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
                    if (subOption.name.toLowerCase().includes(this.search.toLowerCase())) {
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
      const selected = subOption ? subOption.name : option.name;
      this.selectedFilters = [selected];  // Reemplazamos todos los filtros existentes con el nuevo
      // console.log('Nombre de la opción seleccionada:', selected);
      // console.log('ID de la opción seleccionada:', subOption ? subOption.id : option.id);
      this.showOptions = false;
      this.search = '';
      this.optionFilterSelected    = selected

      this.optionSubFilterSelected = subOption ? subOption : option
      this.optionFilterIndex       = subOption.key

      this.rows.map((row) => {
        // console.log(row);
        // if (row.desFrente === param) {
          row.isOpen = true;
        // }
      });

      /* Abrimos todos los frentes para que se vea el resultado de los filtros */

    },
    removeFilter(index) {
        this.selectedFilters.splice(index, 1);
        this.optionFilterIndex = 0;
    },


    updateVencimiento(){

      const tipoRestriccionesOption = this.options.find(option => option.name === 'Vencimiento');
        if (tipoRestriccionesOption) {
          tipoRestriccionesOption.subOptions = [{ name: 'Con Retraso', id: 1 , key: 4 }];
        }


    },

    updateTipoRestricciones() {
      let tiporestricciones = this.$store.state.Restrictionlist_P.map(tipos => {
              return { name: tipos.desTipoRestricciones, id: tipos.codTipoRestricciones , key: 3 };
            });

      const tipoRestriccionesOption = this.options.find(option => option.name === 'Tipo de Restricción');
        if (tipoRestriccionesOption) {
          tipoRestriccionesOption.subOptions = tiporestricciones;
        }

    },

    updateEstados() {
      let tipoEstados = this.$store.state.anaEstado.map(estados => {
              return { name: estados.desEstado, id: estados.codEstado , key: 5 };
            });

      const estadoOption = this.options.find(option => option.name === 'Estado');
        if (estadoOption) {
          estadoOption.subOptions = tipoEstados;
        }

    },
    updateResponsables(newVal) {

          let datamembers = this.$store.state.anaDataMembers
          // console.log(">>>> al inicio")
          // console.log(datamembers)
        // Comprueba que las propiedades requeridas existen.
          if (newVal) {
            // Encuentra todos los responsables únicos en la lista de restricciones.
            const uniqueResponsables = [...new Set(newVal.flatMap(frente =>
              frente.listaFase && frente.listaFase.flatMap(fase =>
                fase.listaRestricciones && fase.listaRestricciones.map(res => res.idUsuarioResponsable)
              )
            ))];

            // console.log(">> lista de ids")
            // console.log(uniqueResponsables)

            // Busca los responsables en la lista de integrantesAnaReS y obtén el desProyIntegrante.
            const responsables = datamembers.filter(integrante => uniqueResponsables.includes(integrante.codProyIntegrante)).map(integrante => {
              return { name: integrante.desProyIntegrante, id: integrante.codProyIntegrante , key: 1 };
            });

            // console.log(">>>> llegando a esta parte")
            // console.log(responsables)

            // Encuentra el elemento "Reponsable" en options y actualiza su subOptions.
            const responsableOption = this.options.find(option => option.name === 'Responsables');
            if (responsableOption) {
              responsableOption.subOptions = responsables;
            }

          }
    },
    updateSolicitantes(newVal) {

          let datamembers = this.$store.state.anaDataMembers
          // console.log(">>>> al inicio")
          // console.log(datamembers)
        // Comprueba que las propiedades requeridas existen.
          if (newVal) {
            // Encuentra todos los responsables únicos en la lista de restricciones.
            const uniqueSolicitantes = [...new Set(newVal.flatMap(frente =>
              frente.listaFase && frente.listaFase.flatMap(fase =>
                fase.listaRestricciones && fase.listaRestricciones.map(res => res.idUsuarioSolicitante)
              )
            ))];
            const solicitantes = datamembers.filter(integrante => uniqueSolicitantes.includes(integrante.idIntegrante)).map(integrante => {
              return { name: integrante.desProyIntegrante, id: integrante.idIntegrante , key: 2 };
            });

            const responsableOption = this.options.find(option => option.name === 'Solicitantes');
            if (responsableOption) {
              responsableOption.subOptions = solicitantes;
            }
          }
    },





  },
  computed: {

    indicadorCumplimiento: function (){

    let totalDias = 0;
    let contador = 0;

    this.rolProyecto

    this.restrictions.forEach((restriccion) => {
      restriccion.listaFase.forEach((fase) => {
        fase.listaRestricciones.forEach((item) => {

          // if (item.dayFechaLevantamiento != '' && item.dayFechaRequerida != '' && (this.rolProyecto == 3 || this.rolProyecto == 0)) {
            if (item.dayFechaLevantamiento != '' && item.dayFechaRequerida != '' && item.codEstadoActividad == "3"){

            let fechaLevantamiento = new Date(item.dayFechaLevantamiento);
            let dayFechaRequerida = new Date(item.dayFechaRequerida);
            let diferenciaDias = Math.round((dayFechaRequerida - fechaLevantamiento) / (1000 * 60 * 60 * 24));
            totalDias += diferenciaDias;
            contador++;
          // }else{

          //   if (item.dayFechaLevantamiento && item.dayFechaIdentificacion && item.codAreaRestriccion == this.areaUsuario && !(this.rolProyecto == 3 || this.rolProyecto == 0) ) {

          //     let fechaLevantamiento = new Date(item.dayFechaLevantamiento);
          //     let fechaIdentificacion = new Date(item.dayFechaIdentificacion);
          //     let diferenciaDias = Math.round((fechaLevantamiento - fechaIdentificacion) / (1000 * 60 * 60 * 24));
          //     totalDias += diferenciaDias;
          //     contador++;

          //   }
          }
        });
      });
    });

    let promedioDias = Math.round(totalDias / contador);
    return promedioDias;

    },

    indicadorAnticipacion: function (){

      let totalDias = 0;
      let contador = 0;

      this.restrictions.forEach((restriccion) => {
        restriccion.listaFase.forEach((fase) => {
          fase.listaRestricciones.forEach((item) => {
            if (item.dayFechaRequerida  !="" && item.dayFechaIdentificacion != "") {
              let dayFechaRequerida = new Date(item.dayFechaRequerida);
              let fechaIdentificacion = new Date(item.dayFechaIdentificacion);
              let diferenciaDias = Math.round((dayFechaRequerida - fechaIdentificacion) / (1000 * 60 * 60 * 24));
              totalDias += diferenciaDias;
              contador++;
            }
          });
        });
      });

      let promedioDias = Math.round(totalDias / contador);
      return promedioDias;


    },
    indicadorAvanceGeneral: function () {

        let listaRestricciones = this.restrictions.flatMap(frente =>
        frente.listaFase.flatMap(fase => fase.listaRestricciones)
        );

        let totalRestricciones = listaRestricciones.length;

        let restriccionesTerminadas = listaRestricciones.filter(restriccion =>
        parseInt(restriccion.codEstadoActividad,10) === 3
        );

        let totalRestriccionesTerminadas = restriccionesTerminadas.length;

        let porcentajeTerminadas = (totalRestriccionesTerminadas / totalRestricciones) * 100;
        porcentajeTerminadas = Math.round(porcentajeTerminadas)

        // let colorClass = porcentajeTerminadas === 100 ? 'green' : (porcentajeTerminadas >= 20 ? 'orange' : 'red');

        return porcentajeTerminadas;

        // return porcentajeTerminadas;

    },

    getBgColor() {
              if (this.indicadorAvanceGeneral === 100) {
                  return 'bg-green400';
              } else if (this.indicadorAvanceGeneral >= 20) {
                  return 'bg-orange400';
              } else {
                  return 'bg-red400';
              }
      },
      getBgColorBold() {
              if (this.indicadorAvanceGeneral === 100) {
                  return 'bg-greenbold';
              } else if (this.indicadorAvanceGeneral >= 20) {
                  return 'bg-orangebold';
              } else {
                  return 'bg-redbold';
              }
      },
    visibleOptions() {
        return this.options.filter(option => option.visible);
    },
    isDisabled: function () {
      return this.disabledItems;
    },

    countNotNoti: function (){
      let res      = this.$store.state.whiteproject_rows;
      let contador = 0;

      res.forEach(obj => {
        obj.listaFase.forEach(fase => {
          fase.listaRestricciones.forEach(restriccion => {
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

      if (this.optionFilterIndex == 0) {
        return this.restrictions;
      } else {
        switch (this.optionFilterIndex) {
          /* 'Responsable' */
          case 1:
            return this.getResponsibleRows(this.optionSubFilterSelected );
            break;
          /* 'Solicitante' */
          case 2:
            return this.getApplicantRows(this.optionSubFilterSelected );
            break;
          /* 'Vencimiento' */
          case 4:
            return this.getExpirationRows(this.optionSubFilterSelected);
            break;
          /* 'Tipo de restriccion' */
          case 3:
            // console.log(">>>> entrando a la restriccion");
            return this.getResTypeRows(this.optionSubFilterSelected );
            break;
          case 5:
          // console.log(">>>> entrando a la restriccion");
            return this.getResEstados(this.optionSubFilterSelected );
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

  watch: {
  restrictions: {
    deep: true,  // Observa los cambios profundos en el objeto
    handler(newVal) {

      if (newVal) {
          console.log(">>>>>> verificamos que tenemos actualizaciones ")
          this.updateResponsables(newVal);
          this.updateSolicitantes(newVal);
          // this.indicadorAvanceGeneral(newVal);
      }

    },
  },
},


  mounted: async function () {
    document.addEventListener('click', this.outsideClickListener);

    await this.callMounted();

  },

  created: function () {},
  beforeMount: function () {

  },
  beforeDestroy() {
      document.removeEventListener('click', this.outsideClickListener);
  },

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
    border-radius: 2px;
    background-color: #f0f0f0; /* Un color de fondo para la parte no llena de la barra */
}

.bar .filled {
    /* height: 1.5em; */
    border-radius: 2px;
    text-align: center;
    font-size: 0.7em;
    line-height: 1.2em;
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
