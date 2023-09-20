<template>
  <div
    v-if="isLoading == false"
    class="h-full flex justify-center sm:items-start"

  >
  <loading

        v-model:active=isLoadingTrue
        :can-cancel="false"
        :is-full-page=true
        loader="dots"
    />


  </div>
  <div v-if="isLoading">
    <Breadcrumb
      :paths="['Inicio', 'Análisis de restricciones', 'Indicadores']"
      :urls ="['home','restricciones']"
      :settingFlag="true"
    />
    <!-- <Indicator
      :header="'Análisis de restricciones'"
      :paragraph="'Acá podrás visualizar las restricciones de tus proyectos y entrar al detalle de cada uno'"
      :buttonText="'Ver indicadores'"
    /> -->
    <div class="mb-8 border border-[#D0D9F1] rounded-lg">




    </div>

  </div>
</template>

<script>
import Breadcrumb from "../../components/Layout/Breadcrumb.vue";
import Indicator from "../../components/Layout/Indicator.vue";
import AdvertisingBig from "../../components/Layout/AdvertisingBig.vue";
import Success from "../../components/Success.vue";
import Confirm from "../../components/Confirm.vue";
import EditStatus from "../../components/EditStatus.vue";
import SelectUsers from "../../components/SelectUsers.vue";
import SquareBox from "../../components/SquareBox.vue";
import store from "../../store";
import Loading from 'vue-loading-overlay';

export default {
  name: "restrictions-view",
  components: {
    Loading,
    SquareBox,
    Breadcrumb,
    Indicator,
    Success,
    Confirm,
    EditStatus,
    SelectUsers,
  },
  data: function () {
    return {

      pageloadflag:false,


    };
  },

  methods: {
    handleRedirect(path) {
      this.$router.push(path);
    },


  },
  computed: {

    isLoadingTrue(){
       return true
    },
    isLoading: function(){
      return this.pageloadflag
    }
  },
  mounted: async function(){

    this.pageloadflag = true

  }
};
</script>
