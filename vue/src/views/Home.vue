<template>
   <Breadcrumb
      :paths="['Inicio', 'Analisis de restricciones', 'Indicadores']"
      :urls ="['home']"
      :settingFlag="false"
      :class="status === 4 ? 'hidden' : 'm-1'"
    />
  <Dashboad />
</template>

<script>

import ConfirmMultiple from "../components/ConfirmMultiple.vue";
import Dashboad from "./Dashboard2.vue";
import store from "../store";
import Breadcrumb from "../components/Layout/Breadcrumb.vue";
export default {
  name: "white-project-component",
  components: {
    Breadcrumb,
    ConfirmMultiple,
    Dashboad
  },
  data: function () {
    return {
              modalName: "",
              infoProyectos: [],
              firstLogueo  : 0
           }
  },
  methods: {
    handleRedirect(path) {
                this.$router.push(path);
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
        console.log(res)
        if(res.data.estado == true){

          this.closeModal();
          this.handleRedirect('proyectos')
        }
      });
    },
    getPendings : async function () {
      await store.dispatch('get_projects_without_approve').then(res => {
        if(res.data.estado == true){
           this.infoProyectos = res.data.datos
        }

      });

    },
  },
  mounted:  async function() {

  //  store.dispatch('get_infoPerson');
  this.firstLogueo  = this.$store.state.user.firstLogin;
  if(this.infoProyectos.length > 0 ){
      this.modalName = 'ConfirmMultiple'
      console.log(">>> entramos ConfirmMultiple")
   }
  },
}
</script>
