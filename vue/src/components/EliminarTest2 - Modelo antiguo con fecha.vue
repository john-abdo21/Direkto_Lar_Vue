<template>

        <tr style="cursor: grab" @click="valida">
          <td class="absolute left-0 mt-2">
            <button class="bg-[#DCE4F9] w-6 h-6 rounded-md justify-center flex items-center" @click="handleClick('modal')"
              v-click-outside="hide">
              <img src="../assets/points.svg" :class="{ 'content-pointsActive': restriction_data.isTooltip }" alt="" />
            </button>
            <TableTooltip v-if="isOpen" @tooltip="openModal" />
          </td>
          <td :class="{'hidden': hideCols.indexOf('exercise') > -1}"> <input :disabled="!statusRestriction"  @keyup="verificarCambio({name:'desActividad', value: restriction_data.desActividad})" v-model="restriction_data.desActividad" type="text" class="text-xs w-full border border-[#8A9CC9] px-2 h-8 rounded" :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" /> </td>
          <td :class="{'hidden': hideCols.indexOf('restriction') > -1}"> <input :disabled="!statusRestriction"  @keyup="verificarCambio({name:'desRestriccion', value: restriction_data.desRestriccion})" v-model="restriction_data.desRestriccion" type="text" class="text-xs w-full border border-[#8A9CC9] px-2 h-8 rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" /> </td>
          <!-- <td>ass</td>
          <td></td>
          <td>11</td>
          <td>asas</td>
          <td></td>
          <td>11</td>
           -->


          <td class = "" :class="{'hidden': hideCols.indexOf('restrictionType') > -1}">
            <select
            v-if="statusRestriction"
            v-model="restriction_data.codTipoRestriccion"
            class="text-xs w-full border border-[#8A9CC9] px-2 h-8 rounded  selectPer"
            :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }"
            @change="verificarCambio({name:'codTipoRestriccion', value: restriction_data.codTipoRestriccion})"
          >

          <option
          v-for="item in getOption()" v-bind:key="item.value" v-bind:value = "item.value">
            {{ item.name }}
          </option>

          </select>
          <input :disabled="!statusRestriction"  v-if="!statusRestriction" :value="restriction_data.desTipoRestriccion" type="text" class="w-full border border-[#8A9CC9] px-2 text-xs h-8  rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />


          </td>
          <td :class="{'hidden': hideCols.indexOf('date_required') > -1}">

            <v-date-picker v-if="statusRestriction" @click="verificarCambio({name:'dayFechaRequerida', value: restriction_data.dayFechaRequerida})"  ref="datepicker"  v-model="restriction_data.dayFechaRequerida" mode="date" is24hr: false class="flex relative  cursor-pointer cursor-pointer"  >
                <template v-slot="{ inputValue, inputEvents }" >
                  <input


                    class="w-full border border-[#8A9CC9] px-2 text-xs h-8 rounded "
                    placeholder="Fecha de inicio*"
                    :value="inputValue"
                    v-on="inputEvents"

                  />
                </template>
              </v-date-picker>
            <input :disabled="!statusRestriction"  v-if="!statusRestriction" :value="restriction_data.dayFechaRequerida2" type="text" class="w-full border border-[#8A9CC9] px-2 text-xs h-8  rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />

          </td>

          <td :class="{'hidden': hideCols.indexOf('date_conciliad') > -1}">

            <v-date-picker v-if="statusRestriction" @click="verificarCambio({name:'dayFechaConciliada', value: restriction_data.dayFechaConciliada})"  ref="datepicker"  v-model="(restriction_data.dayFechaConciliada)" mode="date" is24hr: false class="flex relative  cursor-pointer cursor-pointer" >
                <template v-slot="{ inputValue, inputEvents }" >
                  <input
                    class="w-full border border-[#8A9CC9] px-2 text-xs h-8 rounded "
                    placeholder="Fecha de inicio*"
                    :value="inputValue"
                    v-on="inputEvents"

                  />
                </template>
              </v-date-picker>
              <input  :disabled="!statusRestriction"  v-if="!statusRestriction" :value="restriction_data.dayFechaConciliada2" type="text" class="w-full border border-[#8A9CC9] px-2 text-xs h-8  rounded" :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />


          </td>

          <td :class="{'hidden': hideCols.indexOf('responsible') > -1}">
            <select v-if="statusRestriction && getOptionResponsables().length > 0"

            v-model="restriction_data.idUsuarioResponsable"
            class="text-xs w-full border border-[#8A9CC9] px-2 h-8 rounded selectPer"
            :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }"
            >

            <option
            v-for="item in getOptionResponsables()" v-bind:key="item.value" v-bind:value = "item.value">
              {{ item.name }}
            </option>

          </select>
          <input :disabled="(!statusRestriction || getOptionResponsables().length == 0)" v-if="!statusRestriction || getOptionResponsables().length == 0" :placeholder="(getOptionResponsables().length == 0 ? 'Sin Miembros': '')" :value="restriction_data.desUsuarioResponsable" type="text" class="w-full border border-[#8A9CC9] px-2 text-xs h-8  rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />

          </td>
          <td :class="{'hidden': hideCols.indexOf('responsible_area') > -1}" ><nav class="text-xs h-8">{{ restriction_data.desAreaResponsable}}</nav></td>

          <td :class="{'hidden': hideCols.indexOf('responsible') > -1}">
              <select v-if="statusRestriction"
              v-model="restriction_data.codEstadoActividad"
              class="text-xs w-full border border-[#8A9CC9] px-2 h-8 rounded selectPer"
              :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }"
              >

              <option
              v-for="item in getOptionEstados()" v-bind:key="item.value" v-bind:value = "item.value">
                {{ item.name }}
              </option>

            </select>
          <input :disabled="!statusRestriction"  v-if="!statusRestriction" :value="restriction_data.desEstadoActividad" type="text" class="w-full border border-[#8A9CC9] px-2 text-xs h-8  rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />

          </td>

          <td></td>
        </tr>

</template>

<script>
import draggable from "vuedraggable";
import TableTooltip from "./TableTooltip.vue";
import ClickOutside from "vue-click-outside";
import SelectOption from "./SelectOption.vue";

export default {
  name: "eliminar_test",
  components: {
    draggable,
    TableTooltip,
    SelectOption
  },
  props: {
    statusRestriction:Boolean,
    restriction_data: Object,
    index:Number,
    hideCols: Array,
    listindex:Array,
    validarUpd:Boolean
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

    }
  },
  methods:{
    valida(){
      console.log(">>>>>>> validamos data")
      console.log(this.restriction_data)
    },
    // handleClickEst: function (index) {
    //   if (index === 'option') {
    //     this.isoptionsEst = !this.isoptionsEst;
    //   } else
    //     this.isOpenEst = !this.isOpenEst;
    // },

    handleClickResp: function (index) {
      if (index === 'option') {
        this.isoptionsResp = !this.isoptionsResp;
      } else
        this.isOpenResp = !this.isOpenResp;
    },

    handleClick: function (index) {
      if (index === 'option') {
        this.isoptions = !this.isoptions;
      } else
        this.isOpen = !this.isOpen;
    },
    // selOpt: function (payload) {
    //   // console.log(payload)
    //   // console.log(payload.target.value)
    //   // this.isoptions = false;

    //   // this.restriction_row.codTipoRestriccion  = payload.value;
    //   // this.restriction_row.desTipoRestriccion  = payload.name;
    //   let enviar = {name: 'codTipoRestriccion', value: payload.target.value}
    //   this.verificarCambio(enviar)

    // },
    selOptResponsable: function (payload) {
      this.isoptionsResp = false;

      this.restriction_row.idUsuarioResponsable   = payload.value;
      this.restriction_row.desUsuarioResponsable  = payload.name;
      let enviar = {name: payload.name_variable, value: payload.value}
      let carea  = payload.carea;

      this.$store.state.areaintegrante.map((row) => {
        if(row.codArea == carea){
          this.restriction_row.desAreaResponsable = row.desArea
        }

      });

      this.verificarCambio(enviar)

    },
    selOptEstado: function (payload) {
      // this.isoptionsEst = false;

      // this.restriction_row.codEstadoActividad   = payload.value;
      // this.restriction_row.desEstadoActividad   = payload.name;
      // let enviar = {name: payload.name_variable, value: payload.value}

      // this.verificarCambio(enviar)

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
        }

        if (this.restriction_data.dayFechaConciliada.toString().split('T') == 2 ){
            this.restriction_row.dayFechaConciliada = this.restriction_data.dayFechaConciliada.toLocaleDateString('en-CA').split('T')[0]
        }
        else if(this.restriction_data.dayFechaConciliada.toString().split(' ').length == 2){
            this.restriction_row.dayFechaConciliada = this.restriction_data.dayFechaConciliada.toString().split(' ')[0]
        }
        // this.restriction_row.dayFechaRequerida      = this.restriction_data.dayFechaRequerida.toString().split(' ')[0]
        // this.restriction_row.dayFechaConciliada     = this.restriction_data.dayFechaConciliada.toString().split(' ')[0]

        this.restriction_row.dayFechaRequerida2     =  this.restriction_data.dayFechaRequerida.toString().split(' ')[0]
        this.restriction_row.dayFechaConciliada2    =  this.restriction_data.dayFechaConciliada.toString().split(' ')[0]

        this.restriction_row.idUsuarioResponsable   = this.restriction_data.idUsuarioResponsable
        this.restriction_row.desUsuarioResponsable  = this.restriction_data.desUsuarioResponsable

        this.restriction_row.desEstadoActividad     = this.restriction_data.desEstadoActividad
        this.restriction_row.codEstadoActividad     = this.restriction_data.codEstadoActividad

        this.restriction_row.desAreaResponsable     = this.restriction_data.desAreaResponsable
        this.restriction_row.desSolicitante         = this.$store.state.user.name;

        this.restriction_row.isupdate               = this.restriction_data.isupdate

    },
    updateRow: function (updRow) {

      let enviar = {}
      this.$emit('updateRow', enviar);

    },
    verificarCambio(data) {

      console.log(">>>> deta de props")
      console.log(this.restriction_data['desActividad'])


      console.log(">>>> deta de rows llenados")
      console.log(this.restriction_row['desActividad'])


      // return ;


      if (this.statusRestriction == false){
        return;
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
            input             = input != "" ? input.toLocaleDateString('en-CA').split('T')[0] : input
            comparar          = comparar != "" ?  comparar.split(' ')[0] : comparar
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
        idrestriccion:this.listindex[2],
        data:updRow })


      if (this.timetimeinit) {
          clearTimeout(this.timetimeinit);
      }

      this.timetimeinit = setTimeout( () => {

        this.$emit('updalidarUpd', true);
        this.updateRow(updRow);

      }, 1500);

      console.log(' Esperando a que termine de escribir');

    },

    openModal: function (payload) {
      this.hide()
      this.$emit('openModal', { param: payload.param, frontId: this.listindex[0], phaseId: this.listindex[1], exercise: this.restriction_data.codAnaResActividad });
    },

    hide: function () {
      this.isOpen = false;
    },
  },

  directives: {
    ClickOutside,
  },
  watch: {

        // console.log("tenemos un cambio ====> "+this.validarUpd)
    validarUpd(newValor, oldValor) {
      if(newValor == true){

        this.loadRow()
        this.$emit('updalidarUpd', false);

      }


      // console.log(newQuestion+" ---- "+oldQuestion)
      // if (newQuestion.includes('?')) {
      //   this.getAnswer()
      // }
    }

  },
  mounted: function () {
    this.getOption();
    this.loadRow();
  }

}
</script>
<style>

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



