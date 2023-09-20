  <template>
    <div>
      <Bar :id="chartId" :data="chartData" :options="chartOptions" @click="onClick" />
    </div>
  </template>

  <script>
  import { Bar } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)


  export default {
    components: { Bar },
    props: {
      chartId: Number,
      chartData: {
          type: Object,
          required: true
        },
      chartOptions: {
        type: Object,
        default: () => {}
      }
    }, // Añade 'chartData' como una propiedad
    mounted() {

    },

    methods: {

      onClick(event) {
        console.log(event.index)

      }

    },
    watch: {
      chartData: 'renderChart' // Asegúrate de que chartData se pase como una propiedad
    }
  };
  </script>
