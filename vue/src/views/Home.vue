<template>
  <Breadcrumb :paths="['Inicio']" />
  <slot></slot>

  <ConfirmMultiple v-if="modalName === 'ConfirmMultiple'" :confirmHeader="''" :header="'Invitaciones a Proyectos'"
    :paragraphs="[
      'Usted tiene actualmente las siguientes invitaciones confirme o rechace las invitaciones',
    ]" :infoProyectos="infoProyectos" @closeModal="closeModal" @confirmChanges="confirmChanges" />

  <div class="flex gap-2 text-center">
    <div class="w-2/12">
      <div class=" border border-gray-400 py-1 px-2 mb-2">
        Filtros
      </div>
      <div class="text-[14px] font-thin text-left">
        <div>
          <label class="font-bold">Proyocto</label>
          <select id="filter1" name="filter1" class="border border-gray-400 w-[130px] pt-1 rounded-lg ml-4 my-1" :value="selectedFilter1"
            v-bind:onChange="filter1">
            <option v-for="(item, index) in projectList" :value="index" :key="index">{{ item }}</option>
          </select>
        </div>
        <div>
          <label class="font-bold">Fronto</label>
          <select id="filter2" name="filter2" class="border border-gray-400 w-[100px] pt-1 rounded-lg ml-4 my-1" :value="selectedFilter1">
            <option v-for="(item, index) in codeproject" :value="index" :key="index">{{ item }}</option>
          </select>
        </div>
        <div>
          <label class="font-bold">Faso</label>
          <select id="filter2" name="filter2" class="border border-gray-400 w-[100px] pt-1 rounded-lg ml-4 my-1" :value="selectedFilter1">
            <option v-for="(item, index) in codeproject" :value="index" :key="index">{{ item }}</option>
          </select>
        </div>

      </div>
    </div>
    <div class="w-7/12">
      <div class=" border border-gray-400 py-1 px-2">
        Evolucion Semanal de Cant. Restriciones x Estado
      </div>
      <div id="chart1"></div>
    </div>
    <div class="w-3/12">
      <div class=" border border-gray-400 py-1 px-2 mb-2">
        Leyenda
      </div>
      <div class="flex justify-between items-center">
        <div class="w-[80px] h-[15px] bg-[#3498db]"></div>
        <div class="text-[12px] text-gray-600">
          En Proceso
        </div>
      </div>
      <div class="flex justify-between items-center">
        <div class="w-[80px] h-[15px] bg-[#301cd9]"></div>
        <div class="text-[12px] text-gray-600">
          Levantada
        </div>
      </div>
      <div class="flex justify-between items-center">
        <div class="w-[80px] h-[15px] bg-[#dd7318]"></div>
        <div class="text-[12px] text-gray-600">
          Por Iniciar
        </div>
      </div>
      <div class="border border-gray-400 py-1 px-2 my-2">
        Indicaciones
      </div>
      <div class="text-left flex flex-col font-thin text-[14px]" style="width:100%">
        <div class="flex">
          1.<div class="pl-2">De click dentro de los graficos para poder filtar en los demas.</div>
        </div>
        <div class="flex">
          2.<div class="pl-2">Use los Filtro para elegr el el Proyecto.</div>
        </div>
      </div>

    </div>
  </div>
  <div class="flex gap-2 text-center">
    <div class="w-3/12">
      <div class="border border-gray-400 py-1">
        Cant. Restricciones x Estado
      </div>
      <div id="chart3"></div>
    </div>
    <div class="w-5/12">
      <div class=" border border-gray-400 py-1 px-2">
        Cant. Restricciones x Responsables x Estado
      </div>
      <div id="chart2"></div>
    </div>
    <div class="w-6/12">
      <div class=" border border-gray-400 py-1 px-2">
        Detalle de Restricciones
      </div>
      <div class="flex h-[35vh] overflow-y-auto">
        <table class="w-full text-center m-3">
          <thead class="bg-gray-200 text-[12px]">
            <td v-for="value in headerCols" :id="value">{{ value }}</td>
          </thead>
          <tbody style="overflow-wrap: anywhere">
            <tr v-for="(item, index) in restrictionsu" :id="index">
              <td>{{ item['desActividad'] }}</td>
              <td>{{ item['desTipoRestriccion'] }}</td>
              <td>{{ item['desUsuarioResponsable'] }}</td>
              <td>{{ datatimeStyleChange(item['dayFechaConciliada']) }}</td>
              <td>{{ datatimeStyleChange(item['dayFechaRequerida']) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>

import ConfirmMultiple from "../components/ConfirmMultiple.vue";
import store from "../store";
import Breadcrumb from "../components/Layout/Breadcrumb.vue";
import ApexCharts from 'apexcharts'
export default {
  name: "white-project-component",
  components: {
    Breadcrumb,
    ConfirmMultiple
  },
  data: function () {
    return {
      modalName: "",
      projectList: [],
      codeproject: [],
      datas: [],
      selectedFilter1: 0,
      infoProyectos: [],
      firstLogueo: 0,
      restrictionsu: [],
      headerCols: {
        exercise: "Actividad",
        restriction: "Restricción",
        responsible: "Responsable",
        date_conciliad: "F, conciliada",
        date_required: "D, requerida",
      },
      states: {},
      options2: {
        series: [{
          name: 'EN RROCESO',
          data: [44, 55, 41, 37, 22, 43, 21]
        }, {
          name: 'LEMANTADA',
          data: [53, 32, 33, 52, 13, 43, 32]
        }, {
          name: 'POR INCIAR',
          data: [12, 17, 11, 9, 15, 11, 20]
        }],
        chart: {
          type: 'bar',
          height: 250,
          stacked: true,
        },
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              style: {
                fontSize: '13px',
                fontWeight: 900
              }
            }
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        xaxis: {
          categories: ['Responsible 1', 'Responsible 2', 'Responsible 3', 'Responsible 4', 'Responsible 5', 'Responsible 6', 'Responsible 7'],
          labels: {
            formatter: function (val) {
              return val
            }
          },
        },
        yaxis: {
          title: {
            text: 'AREA RESPONSABLE',
            fontSize: '200px',
            align: 'center'
          },
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
        },
        fill: {
          opacity: 1
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 0
        }
      },
      options1: {
        series: [
          {
            name: 'EN PROCESO',
            group: 'budget',
            data: [44000, 55000, 41000, 67000, 22000, 43000]
          },
          {
            name: 'LEWANTADA',
            group: 'budget',
            data: [48000, 50000, 40000, 65000, 25000, 40000]
          },
          {
            name: 'POR INOAR',
            group: 'budget',
            data: [13000, 36000, 20000, 8000, 13000, 27000]
          }
        ],
        chart: {
          type: 'bar',
          height: 200,
          stacked: true,
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        dataLabels: {
          enabled: false,
          formatter: (val) => {
            return val
          }
        },
        plotOptions: {
          bar: {
            horizontal: false
          },
          columnWidth: '80%',
        },
        xaxis: {
          categories: [
            'Jan 2022',
            'Feb 2022',
            'Mar 2022',
            'Apr 2022',
            'May 2022',
            'Jun 2022',
            'Jul 2022',
            'Ago 2022',
            'Sep 2022',
            'Oct 2022',
            'Nov 2022',
            'Dec 2022'
          ],
          tickPlacement: 'on'
        },
        fill: {
          opacity: 1
        },
        colors: ['#3498db', '#301cd9', '#dd7318'],
        yaxis: {
          labels: {
            formatter: (val) => {
              return val
            }
          }
        },
        legend: {
          position: 'right',
          align: 'left',
          offsetY: 40
        }
      },
      options3: {
        series: [{
          data: [156, 74]
        }],
        chart: {
          type: 'bar',
          height: 250
        },
        plotOptions: {
          bar: {
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val;
          },
          style: {
            fontSize: '12px',
            colors: ["#ffffff"]
          }
        },
        xaxis: {
          categories: ['LEVANTADA', 'RETRASADO'],
        }
      }
    }
  },
  methods: {
    getMonth(data) {
      let datetime = new Date(data);
      let month = datetime.getMonth() + 1;
      return month;
    },
    handleRedirect(path) {
      this.$router.push(path);
    },
    datatimeStyleChange(data) {
      if (data) {
        let datetime = new Date(data);
        let month = datetime.getMonth() + 1;
        let day = datetime.getDay() - 1;
        return day + '/' + month;
      }
    },
    openModal: function (param) {
      this.modalName = param;
    },
    closeModal: function () {
      if (this.modalName === "") this.$store.commit("increaseHint");
      else this.modalName = "";
    },

    confirmChanges: function (info) {
      store.dispatch('update_projects_without_approve', info).then(res => {
        if (res.data.estado == true) {

          this.closeModal();
          this.handleRedirect('proyectos')
          //  this.infoProyectos = res.data.datos
        }

      });
    },
    getPendings: async function () {
      await store.dispatch('get_projects_without_approve').then(res => {
        if (res.data.estado == true) {
          this.infoProyectos = res.data.datos
        }
      });
    },
    callMounted: async function () {
      await store.dispatch("get_infoPerson");
      await store.dispatch("getNameProy").then((response) => {
        this.nameProyecto = response;
      });
      const constraintid = sessionStorage.getItem('constraintid')
      const constraintNameProy = sessionStorage.getItem('constraintNameProy')
      this.selectedFilter1 = sessionStorage.getItem('selectedFilter')?sessionStorage.getItem('selectedFilter'):0
      if (!constraintid && !constraintNameProy) {
        sessionStorage.setItem('constraintid', this.codeproject[this.selectedFilter1])
        sessionStorage.setItem('constraintNameProy', this.projectList[this.selectedFilter1])
      }
      await store.dispatch("get_datos_restricciones").then((response) => {
      });
      const resDataForState = this.$store.state.anaEstado
      const resDataForBars = this.$store.state.whiteproject_rows
      let states = []
      resDataForState.forEach(element => {
        states.push(element.desEstado)
      });
      let datas = []
      let responsables = []
      resDataForBars.forEach(item => {
        let data = item.listaFase[0].listaRestricciones
        if (data.length) {
          data.map(d => {
            if (responsables.indexOf(d.desUsuarioResponsable) == -1) {
              responsables.unshift(d.desUsuarioResponsable)
            }
            datas.unshift(d)
          })
        }
      })
      const currentYear = new Date(datas[0].dayFechaIdentificacion)
      const year = currentYear.getFullYear();
      const months = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Ago',
        'Sep',
        'Oct',
        'Nov',
        'Dec'
      ]
      let option1Categories = []
      for (let m = 0; m < 12; m++) {
        option1Categories.push(`${months[m]} ${year}`)
      }
      // set
      this.options3.xaxis.categories = states
      this.options2.xaxis.categories = responsables
      this.options1.xaxis.categories = option1Categories
      if (datas) {
        let statevalue = []
        let responsablevalue = []
        let restricionesvalue = []
        for (let i = 0; i < states.length; i++) {
          responsablevalue[i] = { name: states[i], data: [] }
          restricionesvalue[i] = { name: states[i], data: [] }
        }
        datas.map(item => {
          for (let i = 0; i < states.length; i++) {
            if (item.desEstadoActividad == states[i]) {
              for (let j = 0; j < responsables.length; j++) {
                if (responsablevalue[i].data[j] == null || responsablevalue[i].data[j] == undefined)
                  responsablevalue[i].data[j] = 0
                if (item.desUsuarioResponsable == responsables[j])
                  responsablevalue[i].data[j]++
              }
              // option1
              for (let k = 0; k < months.length; k++) {
                if (restricionesvalue[i].data[k] == null || restricionesvalue[i].data[k] == undefined)
                  restricionesvalue[i].data[k] = 0
                if (this.getMonth(item.dayFechaIdentificacion) == k + 1)
                  restricionesvalue[i].data[k]++
              }
              if (statevalue[i] == null)
                statevalue[i] = 1
              else
                statevalue[i]++
            }
          }
        })
        // set
        this.options3.series[0].data = statevalue
        this.options2.series = responsablevalue
        this.options1.series = restricionesvalue
        this.restrictionsu = resDataForBars[0].listaFase[0].listaRestricciones
      }
      var chart1 = new ApexCharts(document.querySelector("#chart1"), this.options1);
      chart1.render();
      var chart2 = new ApexCharts(document.querySelector("#chart2"), this.options2);
      chart2.render();
      var chart3 = new ApexCharts(document.querySelector("#chart3"), this.options3);
      chart3.render();

    },
    async filter1(e) {
      sessionStorage.setItem('constraintid', this.codeproject[e.target.value])
      sessionStorage.setItem('constraintNameProy', this.projectList[e.target.value])
      sessionStorage.setItem('selectedFilter',  e.target.value)
      window.location.reload()
      this.callMounted()
    },

  },
  mounted: async function () {
    await store.dispatch('get_restrictions').then((response) => {
    });
    let projects = this.$store.state.restriction_rows_real;
    this.projectList = projects.map(item => { return item.desnombreproyecto });
    this.codeproject = projects.map(item => { return item.codProyecto });
    this.firstLogueo = this.$store.state.user.firstLogin;
    await this.callMounted();
    await this.getPendings();
    if (this.infoProyectos.length > 0) {
      this.modalName = 'ConfirmMultiple'
      console.log(">>> entramos ConfirmMultiple")
    }
  },
  computed: {
    // proyectoPendientes: function() {
    //   return this.infoProyectos
    // },

    // primerLogueo: function(){

    //   return this.$store.state.user.firstLogin;

    // }
  }
}
</script>
