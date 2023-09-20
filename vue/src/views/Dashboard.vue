<template>
  <div>
    <BarChart
      :chartData="barData"
      :chartOptions="barOptions"
      :chartId="barChartByWeek"
      @barClicked="updateCharts"
    />
    <BarChart
      :chartData="barDataByState"
      :chartOptions="barOptionsByState"
      :chartId="barChartByState"
      @barClicked="updateCharts"
    />
  </div>
</template>

<script>
import BarChart from '../views/BarChart.vue';
import { mapState } from 'vuex';

Date.prototype.getWeek = function() {
  var onejan = new Date(this.getFullYear(), 0, 1);
  return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
};

export default {
  components: {
    BarChart
  },
  data() {
    return {
      rawData: [
        {id: 1, fecha: '2023/01/01', estado: 'completado'},
        {id: 1, fecha: '2023/01/01', estado: 'completado'},
        {id: 2, fecha: '2023/01/05', estado: 'En proceso'},
        {id: 2, fecha: '2023/01/05', estado: 'Pendiente'},
        {id: 3, fecha: '2023/01/08', estado: 'Pendiente'},
        {id: 4, fecha: '2023/02/09', estado: 'Pendiente'},
        {id: 5, fecha: '2023/02/10', estado: 'En proceso'},
        {id: 6, fecha: '2023/02/10', estado: 'En proceso'},
        {id: 5, fecha: '2023/02/11', estado: 'completado'},
        {id: 6, fecha: '2023/02/11', estado: 'completado'}
      ],
      barData: {},
      barOptions: {
        scales: {
          x: { stacked: true },
          y: { stacked: true }
        }
      },
      barDataByState: {},
      barOptionsByState: {}
    };
  },
  computed: {
    ...mapState(['data']),
    groupedByWeek() {
      const groups = {};
      const statesSet = new Set();

      this.rawData.forEach(row => {
        // Lógica para determinar la semana (esto es un ejemplo)
        const week = `Semana ${new Date(row.fecha).getWeek()}`;

        if (!groups[week]) groups[week] = {};

        if (!groups[week][row.estado]) groups[week][row.estado] = 0;

        groups[week][row.estado]++;
        statesSet.add(row.estado);
      });

      this.availableStates = [...statesSet];

      return groups;
    },
    groupedByState() {
      const groups = {};

      this.rawData.forEach(row => {
        if (!groups[row.estado]) groups[row.estado] = 0;

        groups[row.estado]++;
      });

      return groups;
    },
    barData() {
      return {
        labels: Object.keys(this.groupedByWeek),
        datasets: this.availableStates.map(state => ({
          label: state,
          data: Object.values(this.groupedByWeek).map(w => w[state] || 0),
          // Agregar lógica para los colores si es necesario
        }))
      };
    },
    barDataByState() {
      return {
        labels: Object.keys(this.groupedByState),
        datasets: [{
          data: Object.values(this.groupedByState),
          // Agregar lógica para los colores si es necesario
        }]
      };
    },
    created() {
    // Asume que this.availableStates se inicializará durante la etapa 'created' del ciclo de vida.
      this.availableStates = [];
    },
    barOptions() {
      return {
        scales: {
          x: { stacked: true },
          y: { stacked: true }
        }
      };
    },
    barOptionsByState() {
      return {};
    }
  },
  methods: {
    updateCharts(label) {

      console.log(label)
      // Aquí va tu lógica para actualizar los gráficos basado en el label clickeado
    }
  }
};
</script>
