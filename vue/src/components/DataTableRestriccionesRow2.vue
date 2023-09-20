<template>

        <tr class = "bg-[#ffff]" style="cursor: grab" @click="valida" ref="tableRow">
          <input type="hidden" name="frontName" :value="frontName">
          <input type="hidden" name="phaseName" :value="phaseName">

          <td v-if="rolProyecto == 3 || rolProyecto == 0" class="absolute left-0 mt-2 text-[0.6rem]">
            <button class="bg-[#DCE4F9] py-2  rounded-md justify-center flex items-center" @click="handleClick('modal')"
              v-click-outside="hide">
              <img src="../assets/points.svg" :class="{ 'content-pointsActive': restriction_data.isTooltip }" alt="" />
            </button>
            <TableTooltip v-if="isOpen" @tooltip="openModal" :ResizeActually="ResizeActually" />
          </td>
          <td v-else class="absolute left-0 mt-2">

          </td>

          <td class = "downExcel" :class="{'hidden': hideCols.indexOf('exercise') > -1}">
            <textarea
                  ref="exercise"
                  name="exercise"
                  v-if="INIstateRestriction && restriction_data.codEstadoActividad < 3"
                  @keyup="verificarCambio({name:'desActividad', value: restriction_data.desActividad});"
                  v-model="restriction_data.desActividad"
                  type="text"
                  class="mt-1.5 w-full text-[0.6rem] border  px-1 h-6 rounded resizable-textarea"

                  :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1), 'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction, 'text-[0.6rem]': !INIstateRestriction }"
                  @input="updateHeight1"
                  :style="{ lineHeight: 'initial', height: autoSize1 + 'px'}"
            >
            </textarea>
            <textarea
                  v-if="!INIstateRestriction || restriction_data.codEstadoActividad == 3"
                  :disabled="true"
                  v-model="restriction_data.desActividad"
                  ref="exercise"
                  name="exercise"
                  type="text"
                  class="mt-1.5 w-full text-[0.6rem] border  px-1 h-6 rounded resizable-textarea"
                  :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1), 'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
                  @input="updateHeight1"
                  :style="{ lineHeight: 'initial', height: autoSize1 + 'px'}"

            >
            </textarea>
          </td>
          <td class = "downExcel" :class="{'hidden': hideCols.indexOf('restriction') > -1}">
            <textarea
                  ref="restriction"
                  name="restriction"
                  v-if="INIstateRestriction && restriction_data.codEstadoActividad < 3"
                  @keyup="verificarCambio({name:'desRestriccion', value: restriction_data.desRestriccion})"
                  v-model="restriction_data.desRestriccion"
                  type="text"
                  class="w-full text-[0.6rem] border border-[#8A9CC9] px-2 h-6 rounded resizable-textarea"
                  :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
                  :style="{ marginTop: '5px', lineHeight: 'initial', height: autoSize2 + 'px'}"
                  @input="updateHeight2"
            >
            </textarea>

            <textarea
                  v-if="!INIstateRestriction || restriction_data.codEstadoActividad == 3"
                  :disabled="true"
                  v-model="restriction_data.desRestriccion"
                  ref="restriction"
                  name="restriction"
                  type="text"
                  class="w-full text-[0.6rem] border border-[#8A9CC9] px-2 h-6 rounded resizable-textarea"
                  :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
                  :style="{ marginTop: '5px', lineHeight: 'initial', height: autoSize2 + 'px'}"
                  @input="updateHeight2"

            >
          </textarea>
          </td>

          <td class = "downExcel" :class="{'hidden': hideCols.indexOf('restrictionType') > -1}">
            <select
            name="restrictionType"
            v-if="INItipo && restriction_data.codEstadoActividad < 3"
            v-model="restriction_data.codTipoRestriccion"
            class="text-[0.6rem] w-full  border border-[#8A9CC9] pr-5 px-2 h-6 rounded  selectPer"
            :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INItipo , 'text-gray-700': !INItipo  }"
            @change="verificarCambio({name:'codTipoRestriccion', value: restriction_data.codTipoRestriccion, change: $event });"
          >

          <option
          v-for="item in getOption()" v-bind:key="item.value" v-bind:value = "item.value">
            {{ item.name }}
          </option>

          </select>
          <input
                 v-if="!INItipo || restriction_data.codEstadoActividad == 3"
                 name="restrictionType"
                 :disabled="true"
                 :value="restriction_data.desTipoRestriccion"
                 type="text"
                 class="w-full border border-[#8A9CC9] pr-5 px-2 text-[0.6rem] h-6  rounded"
                 :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INItipo , 'text-gray-700': !INItipo }" />


          </td>

          <td class="downExcel" :class="{'hidden': hideCols.indexOf('date_required') > -1}">

            <input
            v-if="(INIstateRestriction && restriction_data.codEstadoActividad < 3 && restriction_data.isEnabledFRequerida )"
             name="date_required"

             @change="verificarCambio({name:'dayFechaRequerida', value: formated_val(restriction_data.dayFechaRequerida)})"
             v-model="restriction_data.dayFechaRequerida"
             type="date"
             class="text-[0.6rem] w-full border border-[#8A9CC9] px-2 h-6 rounded"
             :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
            />

            <input
             v-if="!INIstateRestriction || restriction_data.codEstadoActividad == 3 || restriction_data.isEnabledFRequerida == false "
             name="date_required"
             :disabled="true"

             :value="formated_val(restriction_data.dayFechaRequerida)"
             type="text" class="w-full border border-[#8A9CC9] px-2 text-[0.6rem] h-6  rounded"
             :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),
                          'bg-gray-100': !INIstateRestriction ,
                          'text-gray-700': !INIstateRestriction,

                      }"
            />

          </td>

          <td class="downExcel" :class="{'hidden': hideCols.indexOf('date_conciliad') > -1}">

              <input
                    v-if="(INIstateRestriction && restriction_data.codEstadoActividad < 3 && restriction_data.isEnabledFConciliada )"
                    name="date_conciliad"
                    @change="verificarCambio({name:'dayFechaConciliada', value: restriction_data.dayFechaConciliada})"
                    v-model="restriction_data.dayFechaConciliada"
                    type="date"
                    class="text-[0.6rem] w-full border border-[#8A9CC9] px-2 h-6 rounded"
                    :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
              />

              <input
                    v-if="!INIstateRestriction || restriction_data.codEstadoActividad == 3 || restriction_data.isEnabledFConciliada == false"
                    name="date_conciliad"
                    :disabled="true"
                    :value=formated_val(restriction_data.dayFechaConciliada)
                    type="text"
                    class="w-full border border-[#8A9CC9] px-2 text-[0.6rem] h-6  rounded"
                    :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
              />


          </td>
          <td class="downExcel" :class="{'hidden': hideCols.indexOf('date_identity') > -1}">

            <input

                    name="date_identity"
                    :disabled="true"
                    :value="formated_val(restriction_data.dayFechaIdentificacion)"
                    type="text"
                    class="w-full border border-[#8A9CC9] px-2 text-[0.6rem] h-6  rounded"
                    :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
              />

          </td>

          <td class="downExcel" :class="{'hidden': hideCols.indexOf('responsible') > -1}">
            <select
            v-if="(INIresponsable && getOptionResponsables().length > 0) && restriction_data.codEstadoActividad < 3"
            name="responsible"
            v-model="restriction_data.idUsuarioResponsable"
            class="text-[0.6rem] w-full  border border-[#8A9CC9] pr-5 px-2 h-6 rounded selectPer"
            :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIresponsable , 'text-gray-700': !INIresponsable  }"
            @change="verificarCambio({name:'idUsuarioResponsable', value: restriction_data.idUsuarioResponsable})"
            >

              <option
              v-for="item in getOptionResponsables()" v-bind:key="item.value" v-bind:value = "item.value">
                {{ item.name }}
              </option>
            </select>

          <input
              name="responsible"
              v-if="(!INIresponsable || getOptionResponsables().length == 0) || restriction_data.codEstadoActividad == 3"
              :disabled="true"
              :placeholder="(getOptionResponsables().length == 0 ? 'Sin Miembros': '')"
              :value="restriction_data.desUsuarioResponsable"
              type="text"
              class="w-full border border-[#8A9CC9] pr-5 px-2 text-[0.6rem] h-6  rounded"
              :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIresponsable , 'text-gray-700': !INIresponsable  }"
          />

          </td>

          <td class="downExcel" :class="{'hidden': hideCols.indexOf('responsible_area') > -1}" >
            <input name="responsible_area" type="text"
            :value="restriction_data.desAreaResponsable"
            readonly class="w-full border  px-2 text-[0.6rem] h-6  rounded"
            :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1)}"
            />
          </td>

          <td class="downExcel" :class="{'hidden': hideCols.indexOf('condition') > -1}">

            <FlagSelect
             v-if="INIstateRestriction"
             name="condition"
             v-model="restriction_data.codEstadoActividad"
             :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100,text-gray-700': !INIstateRestriction }"
             @change="verificarCambio({name:'codEstadoActividad', value: restriction_data.codEstadoActividad})"
             class=""

             />

            <!-- <select
              v-if="INIstateRestriction"
              name="condition"
              v-model="restriction_data.codEstadoActividad"
              class="text-[0.6rem] pr-5 w-full  border border-[#8A9CC9] px-2 h-6 rounded selectPer"
              :class="{'bg-gray-100,text-gray-700': !INIstateRestriction }"
              @change="verificarCambio({name:'codEstadoActividad', value: restriction_data.codEstadoActividad})"
              @input="selectItem()"
            >
              <option
                v-for="item in getOptionEstados()"
                v-bind:key="item.name"
                v-bind:value = "item.value"
              >{{ item.name }}
              </option>
            </select> -->

            <FlagSelect
             v-if="!INIstateRestriction"
             name="condition"
             :disabled="true"
             v-model="restriction_data.codEstadoActividad"
             :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1),'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
             @change="verificarCambio({name:'codEstadoActividad', value: restriction_data.codEstadoActividad})"

             />

            <!-- <input
                  v-if="!INIstateRestriction"
                  name="condition"
                  :disabled="true"
                  :value="restriction_data.desEstadoActividad"
                  type="text"
                  class="w-full pr-5 border border-[#8A9CC9] px-2 text-[0.6rem] h-6  rounded"
                  :class="{'bg-gray-100': !INIstateRestriction , 'text-gray-700': !INIstateRestriction  }"
            /> -->

          </td>

          <td class="downExcel" :class="{'hidden': hideCols.indexOf('applicant') > -1}">
            <input
               name="applicant" type="text"
               :value="restriction_data.desUsuarioSolicitante"
               readonly class="w-full border px-2 text-[0.6rem] h-6  rounded"
               :class="{'border-[#3ac189]': (restriction_data.codEstadoActividad == 3),'border-[#e56b37]': (restriction_data.codEstadoActividad == 2),'border-[#ccc]': (restriction_data.codEstadoActividad == 1)}"
               />
          </td>
        </tr>

</template>

<script>
import draggable from "vuedraggable";
import TableTooltip from "./TableTooltip.vue";
import ClickOutside from "vue-click-outside";
import SelectOption from "./SelectOption.vue";
import FlagSelect from './FlagSelect.vue';
import moment from 'moment'
import "../assets/css/reset_datatable.css"

export default {
  name: "eliminar_test",
  components: {
    draggable,
    TableTooltip,
    SelectOption,
    FlagSelect
  },
  props: {
    rolProyecto:Number,
    statusRestriction:Boolean,
    restriction_data: Object,
    index:Number,
    hideCols: Array,
    listindex:Array,
    listIds:Array,
    validarUpd:Boolean,

    frontName:String,
    phaseName:String,


    ResizeActually:Number,
    solicitanteActual:String
  },
  data: function () {
    return {
      options: [],
      restriction_row:{},

      timetimeinit: null,

      isOpen: false,
      isoptions: false,

      isOpenResp: false,
      isoptionsResp: false,

      isOpenEst: false,
      isoptionsEst: false,

      autoSize1: 30,
      autoSize2: 30,

      isVisible: false,

      dateVal: moment(this.restriction_data.dayFechaRequerida).format("DD/MM/YY")
    }
  },
  methods:{

    updatedColorBorder(){

    },

    formated_val(date) {
      if (date) {
           return moment(String(date)).format('DD/MM/YY')
      }
    },

    updateHeight1(event) {
      try {
        this.autoSize1 = Math.max(this.$refs.exercise.scrollHeight, 30);
      } catch (error) {
      }
    },

    updateHeight2(event) {
      try {
        this.autoSize2 = Math.max(this.$refs.restriction.scrollHeight, 30);
      } catch (error) {
      }
    },

    updateHeights() {
      this.updateHeight1();
      this.updateHeight2();
    },

    handleClickResp: function (index) {
      if (index === 'option') {
        this.isoptionsResp = !this.isoptionsResp;
      } else
        this.isOpenResp = !this.isOpenResp;
    },

    handleClick: function (index) {
      console.log(" >>>>>>> veremos que estanis trayendd "+this.ResizeActually)
      if (index === 'option') {
        this.isoptions = !this.isoptions;
      } else
        this.isOpen = !this.isOpen;
    },

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
    loadRow (){

        console.log(">>> analizamos si actualizo.")

        this.restriction_row.codAnaResActividad     = this.restriction_data.codAnaResActividad
        this.restriction_row.desRestriccion         = this.restriction_data.desRestriccion
        this.restriction_row.desActividad           = this.restriction_data.desActividad
        this.restriction_row.codTipoRestriccion     = this.restriction_data.codTipoRestriccion
        this.restriction_row.desTipoRestriccion     = this.restriction_data.desTipoRestriccion

        if (this.restriction_data.dayFechaRequerida.toString().split('T').length == 2 ){
            this.restriction_row.dayFechaRequerida = this.restriction_data.dayFechaRequerida.toLocaleDateString('en-CA').split('T')[0]
        }
        else if(this.restriction_data.dayFechaRequerida.toString().split(' ').length == 2){
            this.restriction_row.dayFechaRequerida = this.restriction_data.dayFechaRequerida.toString().split(' ')[0]
        }else{
            this.restriction_row.dayFechaRequerida = this.restriction_data.dayFechaRequerida
        }

        if (this.restriction_data.dayFechaConciliada.toString().split('T') == 2 ){
            this.restriction_row.dayFechaConciliada = this.restriction_data.dayFechaConciliada.toLocaleDateString('en-CA').split('T')[0]
        }
        else if(this.restriction_data.dayFechaConciliada.toString().split(' ').length == 2){
            this.restriction_row.dayFechaConciliada = this.restriction_data.dayFechaConciliada.toString().split(' ')[0]
        }else{
            this.restriction_row.dayFechaConciliada = this.restriction_data.dayFechaConciliada
        }

        this.restriction_row.dayFechaIdentificacion = this.restriction_data.dayFechaIdentificacion
        this.restriction_row.dayFechaLevantamiento  = this.restriction_data.dayFechaLevantamiento
        // this.restriction_row.dayFechaRequerida      = this.restriction_data.dayFechaRequerida.toString().split(' ')[0]
        // this.restriction_row.dayFechaConciliada     = this.restriction_data.dayFechaConciliada.toString().split(' ')[0]

        this.restriction_row.dayFechaRequerida2     =  this.restriction_data.dayFechaRequerida.toString().split(' ')[0]
        this.restriction_row.dayFechaConciliada2    =  this.restriction_data.dayFechaConciliada.toString().split(' ')[0]

        this.restriction_row.idUsuarioResponsable   = this.restriction_data.idUsuarioResponsable
        this.restriction_row.desUsuarioResponsable  = this.restriction_data.desUsuarioResponsable

        this.restriction_row.desEstadoActividad     = this.restriction_data.desEstadoActividad
        this.restriction_row.codEstadoActividad     = this.restriction_data.codEstadoActividad

        this.restriction_row.desAreaResponsable     = this.restriction_data.desAreaResponsable
        this.restriction_row.numOrden               = this.restriction_data.numOrden
        this.restriction_row.desUsuarioSolicitante         = this.restriction_data.desUsuarioSolicitante

        this.restriction_row.isupdate               = this.restriction_data.isupdate

        this.restriction_row.isEnabledFRequerida    = this.restriction_data.isEnabledFRequerida
        this.restriction_row.isEnabledFConciliada   = this.restriction_data.isEnabledFConciliada
        this.restriction_row.codAreaRestriccion     = this.restriction_data.codAreaRestriccion

    },
    updateRow: function (updRow) {

      let enviar = {}
      this.$emit('updateRow', {frontIdx: this.listindex[0] , phaseIdx:this.listindex[1]});

    },
    changeType(e) {
        var codTipoRestriccion = e.target.value;
        var desTipoRestriccion = e.target.options[e.target.options.selectedIndex].text;
        this.restriction_data.desTipoRestriccion = desTipoRestriccion;
    },
    verificarCambio(data) {


      // console.log(data)
      // console.log(">>>> deta de props")
      // console.log(this.restriction_data['desActividad'])


      // console.log(">>>> deta de rows llenados")
      // console.log(this.restriction_row['desActividad'])



      if (this.statusRestriction == false){
        return;
      }

      // return ;
      if(data.change != undefined){

        var codTipoRestriccion = data.change.target.value;
        var desTipoRestriccion = data.change.target.options[data.change.target.options.selectedIndex].text;
        this.restriction_data.desTipoRestriccion = desTipoRestriccion;

      }

      var self                        = this;
      let flag                        = false;
      let updRow                      = this.restriction_data;


      // updRow['codAnaResActividad'] = this.restriction_data['codAnaResActividad'];

      var input                       = updRow[data.name]
      var comparar                    = this.restriction_row[data.name]

      console.log(">>>>> comparar")
      console.log(">>"+input+" ||| "+comparar)

      if ( data.name == 'dayFechaRequerida' || data.name == 'dayFechaConciliada' ){
        if (input != null){
           // input             = input != "" ? input.toLocaleDateString('en-CA').split('T')[0] : input
           // comparar          = comparar != "" ?  comparar.split(' ')[0] : comparar
        }else{
            this.restriction_data[data.name] = input
            console.log(" >> No tenemos diferencias en las fechas")
            return;
        }
      }


      if (input != comparar ){
            console.log(" >> tenemos diferencias")
            updRow.isupdate = true
            updRow.column   = data.name


      }else{
            console.log(" >> No tenemos diferencias")
            updRow.isupdate = false
            updRow.column   = data.name

      }

      this.$emit('RegistrarCambioRow',{
        idfrente:this.listindex[0],
        idfase:this.listindex[1],
        idrestriccion: updRow['codAnaResActividad'],//this.listindex[2],
        data:updRow })


      if (this.timetimeinit) {
          clearTimeout(this.timetimeinit);
      }

      this.timetimeinit = setTimeout( () => {

        this.loadRow()
        this.$emit('updalidarUpd', true);
        this.updateRow(updRow);

      }, 1500);

      console.log(' Esperando a que termine de escribir');

    },

    openModal: function (payload) {
      this.hide()
      this.$emit('openModal', { param: payload.param, frontId: this.listIds[0], phaseId: this.listIds[1], exercise: this.restriction_data.codAnaResActividad });
    },

    hide: function () {
      this.isOpen = false;
    },

    onClickSpan: function () {
      this.isVisible = !this.isVisible;
    },

    selectItem: function() {
      this.isVisible = !this.isVisible;
    }
  },

  directives: {
    ClickOutside,
  },
  watch: {

    validarUpd(newValor, oldValor) {
      // console.log(">>>>>>> validamos que se esten haciendo la escucha del flag de cambios")
      // console.log(newValor)
      if(newValor == true){

        // console.log("###################")
        // console.log(">> guardado")
        // console.log(this.restriction_row)
        // console.log(">> actualizado")
        // console.log(this.restriction_data)

        // this.loadRow()

        // console.log(">>> despues de la actualizacion")

        // console.log(">> guardado")
        // console.log(this.restriction_row)
        // console.log(">> actualizado")
        // console.log(this.restriction_data)
        // console.log("###################")
        // this.$emit('updalidarUpd', false);

      }

    }

  },
  mounted: function () {

    this.$nextTick(() => {
      this.updateHeights();
      this.resizeObserver = new ResizeObserver(this.updateHeights);
      this.resizeObserver.observe(this.$refs.tableRow);
    });

    this.getOption();
    this.loadRow();

  },
  beforeDestroy() {
    if (this.resizeObserver) {
      this.resizeObserver.disconnect();
    }
  },
  updated() {
    this.$nextTick(this.updateHeights);
  },

  computed: {


    borderColorComp(){
      return this.restriction_data.codEstadoActividad == 1 ? '#ccc' : (this.restriction_data.codEstadoActividad == 2 ? '#e56b37': '#3ac189')

    },



    INIresponsable(){


      if (this.restriction_data.isEnabled == false){

        return false

      }else{

      if(!(this.rolProyecto == 3 || this.rolProyecto == 0)){

        return false

      }else{

        return  this.statusRestriction

      }


      }

    },
    INItipo(){

      if (this.restriction_data.isEnabled == false){

        return false

      }else{

        if(!(this.rolProyecto == 3 || this.rolProyecto == 0)){

          return false

        }else{

          return  this.statusRestriction

        }


      }

    },
    INIstateRestriction(){

      if ( this.restriction_data.isEnabled  == false){

        return false

      }else{

        return  this.statusRestriction

      }

    }

  }

}
</script>
<style>

.resizable-textarea {
  min-height: 20px;
  resize: none;
  overflow: hidden;
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

</style>



