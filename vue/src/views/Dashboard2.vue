<template>
  <div>
    <BarChart2
      :tipo = 1
      :chartData="barData"
      :chartOptions="barOptions"
      :periodos = graph2_data
      :filterHidden = filterHidden
      @emitFilters="updateFilters"
      @removeFilters="removeFilters"
      :width  = "'400'"
      :height = "'300'"


    />
    <BarChart2
      :tipo = 2
      :chartData="barDataByState"
      :chartOptions="barOptionsByState"
      :periodos = []
      :filterHidden = filterHidden
      @emitFilters="updateFilters"
      @removeFilters="removeFilters"
      :width  = "'200'"
      :height = "'200'"
    />

    <BarChart2
      :tipo = 3
      :chartData="barDatabyResponsable"
      :chartOptions="barOptions2"
      :periodos = graph2_data
      :filterHidden = filterHidden
      @emitFilters="updateFilters"
      @removeFilters="removeFilters"
      :width  = "'400'"
      :height = "'300'"


    />


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
            <tr v-for="(item, index) in rawData" :id="index">
              <td>{{ item['desActividad'] }}</td>
              <td>{{ item['desTipoRestriccion'] }}</td>
              <td>{{ item['responsable'] }}</td>
              <td>{{ item['dayFechaConciliada'] }}</td>
              <td>{{ item['fecha'] }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>

<script>
import BarChart2 from '../views/BarChart2.vue'; // Asegúrate de que la ruta sea correcta
import { mapState } from 'vuex';

export default {
  components: {
    BarChart2,
  },
  data() {
    return {
      rawDataInicial: [
        {id: 1, fecha: '2023/01/01', estado: 'completado', responsable : 'Diego Warthon', desActividad : 'Encofrado', desTipoRestriccion: 'Arquitectura', dayFechaConciliada: '2020-10-12'},
        {id: 2, fecha: '2023/01/01', estado: 'completado', responsable : 'Diego Warthon', desActividad : 'Enchape', desTipoRestriccion: 'Arquitectura', dayFechaConciliada: '2020-10-12'},
        {id: 3, fecha: '2023/01/05', estado: 'En proceso', responsable : 'Javier Melendez', desActividad : 'Enchape', desTipoRestriccion: 'Arquitectura', dayFechaConciliada: '2020-10-12'},
        {id: 4, fecha: '2023/01/05', estado: 'Pendiente', responsable : 'Juan Perez', desActividad : 'Encofrado 2', desTipoRestriccion: 'Arquitectura', dayFechaConciliada: '2020-10-12'},
        {id: 5, fecha: '2023/01/08', estado: 'Pendiente', responsable : 'Juan Perez', desActividad : 'Cimentacion', desTipoRestriccion: 'Arquitectura', dayFechaConciliada: '2020-10-12'},

        {id: 6, fecha: '2023/02/09', estado: 'Pendiente', responsable : 'Diego Warthon', desActividad : 'Cimentacion', desTipoRestriccion: 'Construccion', dayFechaConciliada: '2020-10-12'},
        {id: 7, fecha: '2023/02/10', estado: 'En proceso', responsable : 'Juan Perez', desActividad : 'Cimentacion', desTipoRestriccion: 'Construccion', dayFechaConciliada: '2020-10-12'},
        {id: 8, fecha: '2023/02/10', estado: 'En proceso', responsable : 'Javier Melendez', desActividad : 'Cimentacion', desTipoRestriccion: 'Construccion', dayFechaConciliada: '2020-10-12'},
        {id: 9, fecha: '2023/02/11', estado: 'completado', responsable : 'Diego Warthon', desActividad : 'Techo', desTipoRestriccion: 'Construccion', dayFechaConciliada: '2020-10-12'},
        {id: 10, fecha: '2023/02/11', estado: 'completado', responsable : 'Javier Melendez', desActividad : 'Techo', desTipoRestriccion: 'Construccion', dayFechaConciliada: '2020-10-12'}
      ],
      rawDataColor:{
        'Pendiente': "#cccccc",
        'En proceso': "#e56b37",
        'completado': "#3ac189",

      },
      orderedColors: [],
      graph2_data : [],
      barData: {},
      barDataByState: {},
      filterEstado:undefined,
      filterPeriodo:undefined,
      filterResponsable:undefined,
      filterHidden:false,
      headerCols: {
        exercise: "Actividad",
        restriction: "Restricción",
        responsible: "Responsable",
        date_conciliad: "F.Conciliada",
        date_required: "D.Requerida",
      },
    };
  },
  mounted() {
    this.orderColors();
  },
  computed: {
    ...mapState(['data']),

    rawData() {
      let filtered = this.rawDataInicial;

      if (this.filterEstado !== undefined) {
        filtered = filtered.filter(item => item.estado === this.filterEstado);
      }

      if (this.filterPeriodo !== undefined) {

        filtered = filtered.filter(item => {
          const date = new Date(item.fecha).getYearAndMonthNumber();
          return date == this.filterPeriodo
        });
      }


      if (this.filterResponsable !== undefined ) {
         filtered = filtered.filter(item => item.responsable.toLowerCase().includes(this.filterResponsable.toLowerCase()));
      }

      // console.log(filtered)

      return filtered;
    },

    uniqueResponsables() {
      const responsable = this.rawData.map(item => item.responsable);
      const uniqueResponsables = [...new Set(responsable)];
      return uniqueResponsables;
    },


    uniqueStates() {
      const states = this.rawData.map(item => item.estado);
      const uniqueStates = [...new Set(states)];
      return uniqueStates;
    },

    groupedByResponsable() {
    const groups    = {};
    const statesSet = new Set();

    this.rawData.forEach(row => {
      // Lógica para determinar la semana (esto es un ejemplo)
      const responsable  =  row.responsable; // `${new Date(row.responsable).getWeek()}`;
    //   const month = `${new Date(row.responsable).getYearAndMonthNumber()}`;

      if (!groups[responsable]) {
        groups[responsable]            = {};
        groups[responsable]['data']    = {};
        // groups[week]['periodo'] = month;
      }


      if (!groups[responsable]['data'][row.estado]) {
          groups[responsable]['data'][row.estado] = 0 // [row.estado] = 0;

      }

      groups[responsable]['data'][row.estado]++;
      statesSet.add(row.estado);
    });

    console.log(">>> vemos el final de las condiocoones")
    console.log(groups)

    for (const responsable in groups) {
      // console.log(`Semana ||| : ${semana}`);
      // console.log(groups[semana]['periodo'])
      // this.graph2_data.push(groups[responsable]['periodo'])

      this.uniqueStates.forEach((estado) => {
        if (groups[responsable]['data'][estado] == undefined){
          groups[responsable]['data'][estado] = 0
        }

      });

    }

    // this.availableStates = [...statesSet];


    const data = {}
    data['responsables']  = groups

    console.log('>>>>>>>>> aqui vemos que tal los responsables')
    console.log(groups)

    return data;
    },



    groupedByWeek() {
      const groups    = {};
      const groups_m  = {};
      const statesSet = new Set();

      this.rawData.forEach(row => {
        // Lógica para determinar la semana (esto es un ejemplo)
        const week = `Sem. ${new Date(row.fecha).getWeek()}`;
        const month = `${new Date(row.fecha).getYearAndMonthNumber()}`;

        if (!groups[week]) {
          groups[week]            = {};
          groups[week]['data']    = {};
          groups[week]['periodo'] = month;
        }
        if (!groups_m[month]) groups_m[month] = {};

        // if (!groups_m[month]){
          if (groups_m?.[month]?.[week] == undefined) {
            groups_m[month][week] = 1
            // groups_m[month][week]['mes'] = 1
            // groups_m[month][week]['anio'] = 1
          }

        // }

        if (!groups[week]['data'][row.estado]) {
            groups[week]['data'][row.estado] = 0 // [row.estado] = 0;

        }

        groups[week]['data'][row.estado]++;
        statesSet.add(row.estado);
      });

      // console.log(groups)

      for (const semana in groups) {
        // console.log(`Semana ||| : ${semana}`);
        // console.log(groups[semana]['periodo'])
        this.graph2_data.push(groups[semana]['periodo'])

        this.uniqueStates.forEach((estado) => {
          if (groups[semana]['data'][estado] == undefined){
            groups[semana]['data'][estado] = 0
          }

        });

      }

      this.availableStates = [...statesSet];

      const groups_months = Object.keys(groups_m).map(key => {
        return {
          title: key.convertToYearAndMonth(),
          ntitle : key,
          cols: Object.keys(groups_m[key]).length
        };
      });

      const data = {}
      data['weeks']  = groups
      data['groups'] = groups_months

      console.log('>>>>>>>>> aqui vemos que tal')
      console.log(groups)

      return data;
    },


    groupedByState() {
      const groups = {};

      this.rawData.forEach(row => {
        if (!groups[row.estado]) groups[row.estado] = 0;

        groups[row.estado]++;
      });

      const ordered = {};

      // Iteramos sobre cada clave en rawDataColor
      Object.keys(this.rawDataColor).forEach((key) => {
      if (groups.hasOwnProperty(key)) {
        ordered[key] = groups[key];
      }
      });

      return ordered;

    },
    barData() {
    const series = {};
    const labels = Object.keys(this.groupedByWeek['weeks']);

    labels.forEach(label => {
      const data = this.groupedByWeek['weeks'][label];

      Object.keys(data['data']).forEach(state => {
        if (!series[state]) {
          series[state] = [];
          series[state]['data']    = [];
          series[state]['periodo'] = data['periodo'];
        }

        series[state]['data'].push(data['data'][state]);
      });
    });

    const ordered = {};

     // Iteramos sobre cada clave en rawDataColor
     Object.keys(this.rawDataColor).forEach((key) => {
      if (series.hasOwnProperty(key)) {
        ordered[key] = series[key];
      }
    });

    const apexSeries = Object.keys(ordered).map(state => ({
      name: state,
      periodo : ordered[state]['periodo'],
      data: ordered[state]['data']
    }));

    console.log(">>>>>>> apexseries del primero ")
    console.log(apexSeries)

    return {
      labels,
      series: apexSeries
    };
    },
    barDataByState() {
      return {
        labels: Object.keys(this.groupedByState),
        datasets: [
          {
            data: Object.values(this.groupedByState),
          },
        ],
      };
    },
    barOptions() {
      return {
        chart: {
          type: 'bar',
          stacked: true,
        },

        colors: this.orderedColors,

        plotOptions: {
              bar: {
                borderRadius: 2,
                dataLabels: {
                  total: {
                    enabled: true,
                    style: {
                      fontSize: '11px',
                      fontWeight: 900
                    }
                  },
                  position: 'center'
                }
              }
        },
        dataLabels: {
          enabled: true
        },
        grid: {
          row: {
            colors: ["#fff", "#f2f2f2"]
          }
        },
        yaxis: {
          title: {
            text: 'Restricciones',
            style: {
                    fontSize: '11px',
                    fontWeight: 700
                  },
          },
        },
        xaxis: {
          categories: Object.keys(this.groupedByWeek['weeks']),
          group: {
                  style: {
                    fontSize: '11px',
                    fontWeight: 700
                  },
                  groups: this.groupedByWeek['groups']
                },
          title: {
            text: 'Semana / Año-Mes',
            style: {
                    fontSize: '12px',
                    fontWeight: 700
                  },
          },

        },
        // title: {
        //   text: "Frontend Test 1 - Stacked Bar Chart",
        // },
        legend: {
              show: true,
              position: 'top',
              offsetY: 40
        },

        // stroke: {
        //   width: [0, 2, 5],
        //   curve: "smooth"
        // },

        // tooltip: {
        //   shared: true,
        //   intersect: false,
        //   y: {
        //     formatter: function(y) {
        //       if (typeof y !== "undefined") {
        //         return y.toFixed(0) + " points";
        //       }
        //       return y;
        //     }
        //   }
        // }

      };
  },

  barOptionsByState() {
      return {
        chart: {
          type: 'bar',
          stacked: true,
          // events: {
          //   click: (event, chartContext, config) => {
          //     this.$emit('barClicked', config.dataPointIndex);
          //   },
          // },
        },
        plotOptions: {
          bar: {
            distributed: true  // Esto asegura que cada barra tenga un color diferente
          }
        },
        // grid: {
        //   row: {
        //     colors: ['#cccccc', '#e56b37', '#3ac189']
        //   }
        // },
        grid: {
          row: {
            colors: ["#fff", "#f2f2f2"]
          }
        },
        colors: this.orderedColors,
        xaxis: {
          categories: Object.keys(this.groupedByState),
          labels: {
            // style: {
            //   colors: ['#cccccc', '#e56b37', '#3ac189'],  // Y aquí también
            // },
          },
        },

        yaxis: {
          title: {
            text: 'Restricciones',
            style: {
                    fontSize: '11px',
                    fontWeight: 700
                  },
          },
        },
        legend: {
              show: false,
        },
      };
    },


  barDatabyResponsable() {
    const series = {};
    const labels = Object.keys(this.groupedByResponsable['responsables']);

    labels.forEach(label => {
      const data = this.groupedByResponsable['responsables'][label];

      Object.keys(data['data']).forEach(state => {
        if (!series[state]) {
          series[state] = [];
          series[state]['data']    = [];
        //   series[state]['periodo'] = data['periodo'];
        }

        series[state]['data'].push(data['data'][state]);

      });
    });

    const ordered = {};

     // Iteramos sobre cada clave en rawDataColor
     Object.keys(this.rawDataColor).forEach((key) => {
      if (series.hasOwnProperty(key)) {
        ordered[key] = series[key];
      }
    });

    const apexSeries = Object.keys(ordered).map(state => ({
      name: state,
    //   periodo : ordered[state]['periodo'],
      data: ordered[state]['data']
    }));

    console.log(">> impresion del apexseries ::")
    console.log(apexSeries)

    return {
      labels,
      series: apexSeries
    };

  },


 barOptions2() {
    return {
        chart: {
          type: 'bar',
          stacked: true,
        },

        colors: this.orderedColors,

        plotOptions: {
              bar: {
                borderRadius: 2,
                horizontal: true,
                dataLabels: {
                  total: {
                    enabled: true,
                    style: {
                      fontSize: '11px',
                      fontWeight: 900
                    }
                  },
                  position: 'center'
                }
              }
        },
        dataLabels: {
          enabled: true
        },
        grid: {
          row: {
            colors: ["#fff", "#f2f2f2"]
          }
        },
        yaxis: {
          title: {
            text: 'Responsables',
            style: {
                    fontSize: '11px',
                    fontWeight: 700
                  },
          },
        },
        xaxis: {
          categories: Object.keys(this.groupedByResponsable['responsables']),
          title: {
            text: 'Restricciones',
            style: {
                    fontSize: '12px',
                    fontWeight: 700
                  },
          },

        },
        // title: {
        //   text: "Frontend Test 1 - Stacked Bar Chart",
        // },
        legend: {
              show: true,
              position: 'top',
              offsetY: 40
        },

        // stroke: {
        //   width: [0, 2, 5],
        //   curve: "smooth"
        // },

        // tooltip: {
        //   shared: true,
        //   intersect: false,
        //   y: {
        //     formatter: function(y) {
        //       if (typeof y !== "undefined") {
        //         return y.toFixed(0) + " points";
        //       }
        //       return y;
        //     }
        //   }
        // }

      };
  },


  },
  methods: {

    datatimeStyleChange(data) {
      if (data) {
        let datetime = new Date(data);
        let month = datetime.getMonth() + 1;
        let day = datetime.getDay() - 1;
        return day + '/' + month;
      }
    },
    orderColors() {
      // Obtener las claves y ordenarlas alfabéticamente
      const orderedKeys = Object.keys(this.rawDataColor);
      // Obtener los valores correspondientes a las claves ordenadas
      this.orderedColors = orderedKeys.map(key => this.rawDataColor[key]);
      // Ahora, this.orderedColors contendrá los colores en el orden alfabético de sus claves
      console.log('Colores ordenados:', this.orderedColors);
    },
    updateFilters(data){
      this.filterEstado       = data.estado;
      this.filterPeriodo      = data.periodo;
      this.filterResponsable  = data.responsable;
    },
    removeFilters(data){
      this.filterEstado   = undefined;
      this.filterPeriodo  = undefined;
      this.filterResponsable  = undefined;
    },
    updateCharts(label) {
      console.log(label);
      // Aquí va tu lógica para actualizar los gráficos
    },
  },
};
</script>
