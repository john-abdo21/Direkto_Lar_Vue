<template>
  <tr @click="mirar">
    <td class="absolute left-0 mt-2">
      <button class="bg-[#DCE4F9] w-6 h-6 rounded-md justify-center flex items-center" @click="handleClick('modal')"
        v-click-outside="hide">
        <img src="../assets/points.svg" :class="{ 'content-pointsActive': restriction_row.isTooltip }" alt="" />
      </button>
      <TableTooltip v-if="isOpen" @tooltip="openModal" />
    </td>
    <td>
      <div class="flex border border-[#8A9CC9] rounded h-8 justify-center w-24">
        <img width="16" src="../assets/down-arrow-icon.svg" alt="">
        <span class="px-1 text-sm leading-7"></span>
        <img width="16" src="../assets/up-arrow-icon.svg" alt="">
      </div>
    </td>
    <!-- :class="[isupdate == true ? needupdate : notneedupdate]" -->
    <td> <input @keyup="verificarCambio({name:'desActividad', value: restriction_row.desActividad})" v-model="restriction_row.desActividad" type="text" class="w-full border  px-2 h-10 rounded " /> </td>
    <td> <input v-model="restriction_row.desRestriccion" type="text" class="w-full border  px-2 h-10 rounded " :class="[isupdate == true ? needupdate : notneedupdate]"  /> </td>
    <td>
      <div class="flex relative  cursor-pointer cursor-pointer">
        <input type="hidden" v-model="restriction_row.codTipoRestriccion">
        <input v-model="restriction_row.desTipoRestriccion" type="text" class="w-full border px-2 h-10 rounded " :class="[isupdate == true ? needupdate : notneedupdate]"  placeholder="Materiales" />
        <img @click="handleClick('option')" src="../assets/ic_arrow-down.svg"
          class="absolute top-1/2 -translate-y-1/2 right-2" alt=""
          :class="{ 'rotate-180': isoptions, 'rotate-0': !isoptions }">
        <SelectOption @selected="selOpt" :options="getOption()" v-if="isoptions" />
      </div>
    </td>
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
import DataTableVue from './DataTable.vue';
// import store from "../../store";

export default {
  name: "custome-table-row",
  components: {
    TableTooltip,
    SelectOption
  },
  props: {
    frontId: Number,
    phaseId: Number,
    restriction_data: Object,
    // restriction_row_orig: Object,
    hideCols: Array,
    restrictionOption: Array,
    isEnabled:Boolean,
    isupdate:Boolean
  },
  data: function () {
    return {
      timedown: false,
      timetimeinit: null,
      isOpen: false,
      isoptions: false,
      notneedupdate: 'notneedupdate',
      needupdate: 'needupdate',
      // material:'',
      options: [],
      restriction_row:{},
      // restriction_row:{}
    };
  },
  methods: {

    handleClick: function (index) {
      if (index === 'option') {
        this.isoptions = !this.isoptions;
      } else
        this.isOpen = !this.isOpen;
    },
    hide: function () {
      this.isOpen = false;
    },
    selOpt: function (payload) {
      this.isoptions = false;

      this.restriction_row.codTipoRestriccion  = payload.value;
      this.restriction_row.desTipoRestriccion  = payload.name;
    },
    getOption: function () {
      const options = [];
      this.$store.state.Restrictionlist.map((row) => {
        const temp = {};
        temp["value"] = row.value;
        temp["name"] = row.name;
        options.push(temp);
      });
      this.options = options;
      return options
    },
    loadRow (){

      this.restriction_row.codAnaResActividad   = this.restriction_data.codAnaResActividad
      this.restriction_row.desRestriccion       = this.restriction_data.desRestriccion
      this.restriction_row.desActividad         = this.restriction_data.desActividad
      this.restriction_row.codTipoRestriccion   = this.restriction_data.codTipoRestriccion
      this.restriction_row.desTipoRestriccion   = this.restriction_data.desTipoRestriccion
      // this.restriction_row.isEnabled            = this.row.isEnabled

    },
    // copyOrig:function(){
    //   this.restriction_row_orig = {
    //     codAnaResActividad: this.restriction_data.codAnaResActividad,
    //     desActividad:this.restriction_data.desActividad,
    //     desRestriccion:this.restriction_data.desRestriccion,
    //     codTipoRestriccion:this.restriction_data.codTipoRestriccion,
    //     desTipoRestriccion:this.restriction_data.desTipoRestriccion,
    //     // isupdate:false,
    //     // isEnabled: this.restriction_row.isEnabled,
    //   }
    // },

    mirar: function () {
      // console.log(">>>>> verificamos que encontramos <<<<<< ")
      // console.log(this.restriction_row)
      // console.log(this.restriction_data)
      // console.log(this.isEnabled)

    },
    updateRow: function (updRow) {

      // this.restriction_row.isEnabled       = 1
      let enviar = {
            frente: this.frontId,
            fase  : this.phaseId,
            restriccion : this.restriction_row.codAnaResActividad,
            data  : updRow
        }
      // console.log(">>>>>>>> enviamos a actualizacion")
      // console.log(enviar)
      this.$emit('updateRow', enviar);


    },

    verificarCambio(data) {
        var self    = this;
        let flag    = false;
        let updRow  = this.restriction_row;
        // console.log(">> validamos la diferencia <<")
        // console.log(this.restriction_row)
        // console.log(this.restriction_data)

        if (this.restriction_row[data.name] != this.restriction_data[data.name] ){
               console.log(" >> tenemos diferencias")
               updRow.isupdate = true
              //  flag = true;

        }else{
              //  flag            = fals
              updRow.isupdate = false
        }

        // this.$emit('disabledRows', {frente:this.frontId, fase:this.phaseId, actividad: this.restriction_row.codAnaResActividad, isupdate: flag });

        if (this.timetimeinit) {
            clearTimeout(this.timetimeinit);
        }

        this.timetimeinit = setTimeout( () => this.updateRow(updRow), 1500);
        console.log(' Esperando a que termine de escribir');

    },
    openModal: function (payload) {
      this.$emit('openModal', { param: payload.param, frontId: this.frontId, phaseId: this.phaseId, exercise: this.row.exercise });
    },

  },
  directives: {
    ClickOutside,
  },
  computed: {

    updEnabled: function () {
      return this.isEnabled
    },

  },
  mounted: function () {
    this.getOption();
    this.loadRow();
    // this.copyOrig();
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
