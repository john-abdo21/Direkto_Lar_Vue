<template>
  <tr @click="visualizar">
    <td class="absolute left-0 mt-2">
      <button class="bg-[#DCE4F9] w-6 h-6 rounded-md justify-center flex items-center" @click="handleClick('modal')"
        v-click-outside="hide">
        <img src="../assets/images/icons/points.svg" :class="{ 'content-pointsActive': restriction_row.isTooltip }" alt="" />
      </button>
      <TableTooltip v-if="isOpen" @tooltip="openModal" />
    </td>
    <!-- <td>
      <div class="flex border border-[#8A9CC9] rounded h-8 justify-center w-24">
        <img width="16" src="../assets/images/icons/down-arrow-icon.svg" alt="">
        <span class="px-1 text-sm leading-7"></span>
        <img width="16" src="../assets/images/icons/up-arrow-icon.svg" alt="">
      </div>
    </td> -->
    <!-- :class="[isupdate == true ? needupdate : notneedupdate]" -->
    <td> <input  @keyup="verificarCambio({name:'desActividad', value: restriction_row.desActividad})" v-model="restriction_row.desActividad" type="text" class="text-xs w-full border border-[#8A9CC9] px-2 h-8 rounded" :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" /> </td>
    <td> <input  @keyup="verificarCambio({name:'desRestriccion', value: restriction_row.desRestriccion})" v-model="restriction_row.desRestriccion" type="text" class="text-xs w-full border border-[#8A9CC9] px-2 h-8 rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" /> </td>
    <td>
      <div v-if="statusRestriction" class="flex relative  cursor-pointer cursor-pointer ">
        <input type="hidden" v-model="restriction_row.codTipoRestriccion">
        <input  v-model="restriction_row.desTipoRestriccion" type="text" class="w-full border border-[#8A9CC9] px-2 text-xs h-8 rounded "  placeholder="Elegir Tipo Restricción" :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />
        <img @click="handleClick('option')" src="../assets/images/icons/ic_arrow-down.svg"
          class="absolute top-1/2 -translate-y-1/2 right-2" alt=""
          :class="{ 'rotate-180': isoptions, 'rotate-0': !isoptions }">
        <SelectOption @selected="selOpt" :options="getOption()" :name = "'codTipoRestriccion'" v-if="isoptions" />
      </div>
      <input v-if="!statusRestriction" :value="restriction_row.desTipoRestriccion" type="text" class="w-full border border-[#8A9CC9] px-2 h-10 rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />

    </td>
    <td>

      <v-date-picker v-if="statusRestriction" @click="verificarCambio({name:'dayFechaRequerida', value: restriction_row.dayFechaRequerida})"  ref="datepicker"  v-model="restriction_row.dayFechaRequerida" mode="date" is24hr: false class="flex relative  cursor-pointer cursor-pointer"  >
          <template v-slot="{ inputValue, inputEvents }" >
            <input


              class="w-full border border-[#8A9CC9] px-2 text-xs h-8 rounded "
              placeholder="Fecha de inicio*"
              :value="inputValue"
              v-on="inputEvents"

            />
          </template>
        </v-date-picker>
        <input v-if="!statusRestriction" :value="restriction_row.dayFechaRequerida2" type="text" class="w-full border border-[#8A9CC9] px-2 h-10 rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />

    </td>
    <td>

      <v-date-picker v-if="statusRestriction" @click="verificarCambio({name:'dayFechaConciliada', value: restriction_row.dayFechaConciliada})"  ref="datepicker"  v-model="(restriction_row.dayFechaConciliada)" mode="date" is24hr: false class="flex relative  cursor-pointer cursor-pointer" >
          <template v-slot="{ inputValue, inputEvents }" >
            <input
              class="w-full border border-[#8A9CC9] px-2 text-xs h-8 rounded "
              placeholder="Fecha de inicio*"
              :value="inputValue"
              v-on="inputEvents"

            />
          </template>
        </v-date-picker>
        <input v-if="!statusRestriction" :value="restriction_row.dayFechaConciliada2" type="text" class="w-full border border-[#8A9CC9] px-2 h-10 rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />


    </td>
    <td>
      <div v-if="statusRestriction" class="flex relative  cursor-pointer cursor-pointer">
        <input type="hidden" v-model="restriction_row.idUsuarioResponsable">
        <input v-model="restriction_row.desUsuarioResponsable" type="text" class="w-full border border-[#8A9CC9] px-2 text-xs h-8 rounded "  placeholder="Elegir Tipo Restricción" />
        <img @click="handleClickResp('option')" src="../assets/images/icons/ic_arrow-down.svg"
          class="absolute top-1/2 -translate-y-1/2 right-2" alt=""
          :class="{ 'rotate-180': isoptionsResp, 'rotate-0': !isoptionsResp }">
        <SelectOption @selected="selOptResponsable" :options="getOptionResponsables()" :name = "'idUsuarioResponsable'" v-if="isoptionsResp" />
      </div>
      <input v-if="!statusRestriction" :value="restriction_row.desUsuarioResponsable" type="text" class="w-full border border-[#8A9CC9] px-2 h-10 rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />

    </td>
    <td ><nav class="text-xs h-8">{{ restriction_row.desAreaResponsable}}</nav></td>
    <td>
      <div v-if="statusRestriction"  class="flex relative  cursor-pointer cursor-pointer">
        <input type="hidden" v-model="restriction_row.codEstadoActividad">
        <input v-model="restriction_row.desEstadoActividad" type="text" class="w-full border border-[#8A9CC9] px-2 h-10 rounded "  placeholder="Elegir un Estado" />
        <img @click="handleClickEst('option')" src="../assets/images/icons/ic_arrow-down.svg"
          class="absolute top-1/2 -translate-y-1/2 right-2" alt=""
          :class="{ 'rotate-180': isoptionsEst, 'rotate-0': !isoptionsEst }">
        <SelectOption @selected="selOptEstado" :options="getOptionEstados()" :name = "'codEstadoActividad'" v-if="isoptionsEst" />
      </div>
      <input v-if="!statusRestriction" :value="restriction_row.desEstadoActividad" type="text" class="w-full border border-[#8A9CC9] px-2 h-10 rounded"  :class="{'bg-gray-100': !statusRestriction , 'text-gray-700': !statusRestriction  }" />
    </td>
    <td >{{ restriction_row.desSolicitante}}</td>


   <!-- <td :class="{ 'hidden': hideCols.indexOf('date_required') > -1 }">{{ row['date_required'] }}</td>
    <td :class="{ 'hidden': hideCols.indexOf('responsible') > -1 }">{{ row['responsible'] }}</td>
    <td :class="{ 'hidden': hideCols.indexOf('responsible_area') > -1 }">{{ row['responsible_area'] }}</td>
    <td :class="{ 'hidden': hideCols.indexOf('condition') > -1 }">
      <span class="px-1 py-0.5 bg-[#EB9B00] rounded text-[10px] leading-3">En proceso</span>
    </td>
    <td :class="{ 'hidden': hideCols.indexOf('applicant') > -1 }">{{ row['applicant'] }}</td> -->
  </tr>
</template>

<script>
import ClickOutside from "vue-click-outside";
import TableTooltip from "./TableTooltip.vue";
import SelectOption from "./SelectOption.vue";
// import DataTableVue from './DataTable.vue';
// import store from "../../store";

export default {
  name: "custome-table-row",
  components: {
    TableTooltip,
    SelectOption
  },
  props: {
    statusRestriction:Number,
    frontId: Number,
    phaseId: Number,
    restriction_data: Object,
    // restriction_row_orig: Object,
    hideCols: Array,
    restrictionOption: Array,
    listindex:Array,
    // isEnabled:Boolean,
    isupdate:Boolean
  },
  data: function () {
    return {
      timedown: false,
      timetimeinit: null,
      isOpen: false,
      isoptions: false,

      isOpenResp: false,
      isoptionsResp: false,

      isOpenEst: false,
      isoptionsEst: false,

      notneedupdate: 'notneedupdate',
      needupdate: 'needupdate',
      options: [],
      restriction_row:{},
    };
  },
  methods: {

    handleClick: function (index) {
      if (index === 'option') {
        this.isoptions = !this.isoptions;
      } else
        this.isOpen = !this.isOpen;
    },
    handleClickResp: function (index) {
      if (index === 'option') {
        this.isoptionsResp = !this.isoptionsResp;
      } else
        this.isOpenResp = !this.isOpenResp;
    },
    handleClickEst: function (index) {
      if (index === 'option') {
        this.isoptionsEst = !this.isoptionsEst;
      } else
        this.isOpenEst = !this.isOpenEst;
    },
    hide: function () {
      this.isOpen = false;
    },
    selOpt: function (payload) {
      this.isoptions = false;

      this.restriction_row.codTipoRestriccion  = payload.value;
      this.restriction_row.desTipoRestriccion  = payload.name;
      let enviar = {name: payload.name_variable, value: payload.value}
      this.verificarCambio(enviar)

    },
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
      this.isoptionsEst = false;

      this.restriction_row.codEstadoActividad   = payload.value;
      this.restriction_row.desEstadoActividad   = payload.name;
      let enviar = {name: payload.name_variable, value: payload.value}

      this.verificarCambio(enviar)

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

      this.restriction_row.codAnaResActividad    = this.restriction_data.codAnaResActividad
      this.restriction_row.desRestriccion        = this.restriction_data.desRestriccion
      this.restriction_row.desActividad          = this.restriction_data.desActividad
      this.restriction_row.codTipoRestriccion    = this.restriction_data.codTipoRestriccion
      this.restriction_row.desTipoRestriccion    = this.restriction_data.desTipoRestriccion
      this.restriction_row.dayFechaRequerida     = this.restriction_data.dayFechaRequerida
      this.restriction_row.dayFechaConciliada    = this.restriction_data.dayFechaConciliada

      this.restriction_row.dayFechaRequerida2     =  this.restriction_data.dayFechaRequerida.split(' ')[0]
      this.restriction_row.dayFechaConciliada2    =  this.restriction_data.dayFechaConciliada.split(' ')[0]

      this.restriction_row.idUsuarioResponsable  = this.restriction_data.idUsuarioResponsable
      this.restriction_row.desUsuarioResponsable = this.restriction_data.desUsuarioResponsable

      this.restriction_row.desEstadoActividad    = this.restriction_data.desEstadoActividad
      this.restriction_row.codEstadoActividad    = this.restriction_data.codEstadoActividad

      this.restriction_row.desAreaResponsable = this.restriction_data.desAreaResponsable

      // let name     = this.$store.state.user.data.name
      // let lastname = this.$store.state.user.data.lastname

      this.restriction_row.desSolicitante       = this.$store.state.user.name;

      this.restriction_row.isupdate             = this.restriction_data.isupdate

    },

    updateRow: function (updRow) {

      // this.restriction_row.isEnabled       = 1
      // let enviar = {
      //       frente: this.frontId,
      //       fase  : this.phaseId,
      //       restriccion : this.restriction_row.codAnaResActividad,
      //       data  : updRow
      //   }
      let enviar = {}
      this.$emit('updateRow', enviar);


    },
    visualizar() {
          console.log(this.statusRestriction)
    },

    verificarCambio(data) {

        if (this.statusRestriction == false){
           return;
        }

        var self                     = this;
        let flag                     = false;
        let updRow                   = this.restriction_row;
        updRow['codAnaResActividad'] = this.restriction_data['codAnaResActividad'];

        var input       = updRow[data.name]
        var comparar    = this.restriction_data[data.name]

        if ( data.name == 'dayFechaRequerida' || data.name == 'dayFechaConciliada' ){
          input             = input != "" ? input.toLocaleDateString('en-CA').split('T')[0] : input
          comparar          = comparar != "" ?  comparar.split(' ')[0] : comparar
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

        this.timetimeinit = setTimeout( () => this.updateRow(updRow), 1500);
        console.log(' Esperando a que termine de escribir');

    },
    openModal: function (payload) {
      this.hide()
      this.$emit('openModal', { param: payload.param, frontId: this.frontId, phaseId: this.phaseId, exercise: this.restriction_data.codAnaResActividad });
    },

  },
  directives: {
    ClickOutside,
  },
  computed:{

    // validarEstado: function() {
    //   return this.statusRestriction === true ? false : true
    // }

  },
  mounted: function () {
    this.getOption();
    this.loadRow();
    // // Get reference to the date picker component
    // const datepicker = this.$refs.datepicker;
    // // Call method of the component
    // datepicker.move(new Date());
  }
};
</script>
<style>

.needupdate{
   border-color: rgb(245, 136, 12) !important;
}

.notneedupdate{
   border-color: rgb(47, 139, 47) !important;

}

</style>
