<template>

        <tr scope="row">
          <td class="absolute left-0 mt-2">
            ssdsdsasas
          </td>
          <td>{{restriction_row.desActividad}}</td>
          <td>{{restriction_row.desRestriccion}}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>


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

    loadRow (){

        this.restriction_row.codAnaResActividad     = this.restriction_data.codAnaResActividad
        this.restriction_row.desRestriccion         = this.restriction_data.desRestriccion
        this.restriction_row.desActividad           = this.restriction_data.desActividad
        this.restriction_row.codTipoRestriccion     = this.restriction_data.codTipoRestriccion
        this.restriction_row.desTipoRestriccion     = this.restriction_data.desTipoRestriccion
        this.restriction_row.dayFechaRequerida      = this.restriction_data.dayFechaRequerida
        this.restriction_row.dayFechaConciliada     = this.restriction_data.dayFechaConciliada

        this.restriction_row.dayFechaRequerida2     =  this.restriction_data.dayFechaRequerida.split(' ')[0]
        this.restriction_row.dayFechaConciliada2    =  this.restriction_data.dayFechaConciliada.split(' ')[0]

        this.restriction_row.idUsuarioResponsable   = this.restriction_data.idUsuarioResponsable
        this.restriction_row.desUsuarioResponsable  = this.restriction_data.desUsuarioResponsable

        this.restriction_row.desEstadoActividad     = this.restriction_data.desEstadoActividad
        this.restriction_row.codEstadoActividad     = this.restriction_data.codEstadoActividad

        this.restriction_row.desAreaResponsable     = this.restriction_data.desAreaResponsable
        this.restriction_row.desSolicitante         = this.$store.state.user.name;

        this.restriction_row.isupdate               = this.restriction_data.isupdate

    },


  },

  directives: {
    ClickOutside,
  },
  mounted: function () {
    // this.getOption();
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



